<?php
/***************************************************************
*  Copyright notice
*
*  (c) Hochschule Esslingen
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_femanagement_model	{
protected $piBase;
protected $formFields;
protected $storagePid;
protected $table;
protected $applicationList;

	public function __construct(&$piBase,$storagePid,$table) {
		$this->piBase = $piBase;
		$this->storagePid = $storagePid;
		$this->table = $table;
		$this->initFormFields();

    $this->piBase->settings['debug'] = true;
	}

	public function setAllApplications($applicationList) {
		foreach ($applicationList as $controllerClass=>$name) {
			$this->applicationList[$controllerClass] = $name;
		}
	}
	
	public function getAllApplicationName($controllerClass) {
		if (isset($this->applicationsList[$controllerClass])) {
			return $this->applicationsList[$controllerClass];
		}
		return '';
	}
	
	function initFormFields() {
	}
	
	function get_user_id() {
		$current_user = $GLOBALS['TSFE']->fe_user->user['uid'];
		return $current_user;
	}
	
	function getUserData($data) {
		$userModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
		$feUserdata = $userModel->selectFields('uid',$data['fe_cruser_id'],'fe_users','name,email');
		if (empty($feUserdata['username'])) {
			$beUserdata = $userModel->selectFields('uid',$data['cruser_id'],'be_users','username');
			$feUserdata = $userModel->selectFields('username',$beUserdata['username'],'fe_users','name,email');
		}
		return $feUserdata;
	}
	
	function get_user_groups_array()	{
		$user_groups_string = $GLOBALS['TSFE']->fe_user->user['usergroup'];
		$user_groups_array = explode(',', $user_groups_string);
		return $user_groups_array;
	}
	
/*
 * ########################## FORMULARE ##########################
 */	
	
	function insertDbEntry(&$formData,$hiddenFieldName='hidden',$hidden=1)  {
		
		$initValues = array(
					'pid' => $this->storagePid,
					'crdate' => time(),
					'tstamp' => time(),
					$hiddenFieldName => $hidden,
					'deleted' => '0',
			);
		$dbData = $this->createDbData($formData,$initValues);
		if ($dbData!==FALSE) {
			return $this->storeFormEntry($formData,$dbData);
		} else {
			return FALSE;
		}
	}

	function updateDbEntry(&$formData,$uid,$hiddenFieldName='hidden',$hidden='') {
		$initValues = array(
					'tstamp' => time(),
			);
		if ($hidden!=='') {
			$initValues[$hiddenFieldName] = $hidden;
		}
		$dbData = $this->createDbData($formData,$initValues);
		if ($dbData!==FALSE) {
			return $this->storeFormEntry($formData,$dbData,$uid);
		} else {
			return FALSE;
		}
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (!empty($uid)) {
			$where = 'uid=' . $uid;
			$res = $this->update($where,$dbData);
		} else {
			$res = $this->insert($dbData);
			$uid = $GLOBALS['TYPO3_DB']->sql_insert_id();
		}
		if (!$res) {
			if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern:', 'fe_managment', 0, $dbData);
			return FALSE;
		}
		return $uid;
	}

	function createFormData(&$formData,&$dbData,$uid='') {	
		$formDataNew = array();
		foreach ($dbData as $dbName=>$value) {
			if (isset($this->formFields[$dbName])) {
				$dbFieldName = $this->formFields[$dbName];
			} else {
				$dbFieldName = $dbName;
			}
			$formDataNew[$dbFieldName] = $value;
			if (isset($formData[$dbFieldName])) {
				$typ = get_class($formData[$dbFieldName]);
				if ($typ=='tx_femanagement_view_field_ajax_select') {
					$modelClass = $formData[$dbFieldName]->getModel();
					$model = t3lib_div::makeInstance($modelClass,$this->piBase,$this->storagePid);
					$title = $model->getFieldData($value);
					$formDataNew['field_' . $dbFieldName] = $title;
				} else if ($typ=='tx_femanagement_view_field_multiselect') {
					$formDataNew[$dbFieldName] = explode(',',$value);
				} elseif ($typ=='tx_femanagement_view_field_dyn_table') {
					$formDataNew[$dbFieldName] = unserialize($value);
				} elseif ($typ=='tx_femanagement_view_field_ajax_feuser_select') {
					$formDataNew[$dbFieldName] = unserialize($value);
				} elseif ($typ=='tx_femanagement_view_file') {
//$new_filename = $_FILES[$fieldname][name];
					$formDataNew[$dbFieldName] = unserialize($value);
				}	
			}
		}
		foreach ($formData as $fieldname=>$entry) {
			$typ = get_class($entry);
/*			
			if ($typ=='tx_femanagement_view_field_dyn_table') {
				$modelClass = $entry->getModel();
				$model = t3lib_div::makeInstance($modelClass,$this->piBase,$this->storagePid);
				$data = $model->getFieldData($uid);
				$formDataNew[$fieldname] = $data;
			}
*/			
		}
		return $formDataNew;
	}
		
	function createDbData(&$formData,$dbData) {

		if (isset($this->formFields)) {
			foreach ($this->formFields as $dbName=>$formularName) {
				if (isset($formData[$formularName])) {
					$typ = get_class($formData[$this->formFields[$dbName]]);
					if ($typ=='tx_femanagement_view_field_multiselect') {
						$dbData[$dbName] = implode(',',$formData[$formularName]->getValue());
					} elseif ($typ=='tx_femanagement_view_field_dyn_table') {
						$dbData[$dbName] = serialize($formData[$formularName]->getValue());
					} elseif ($typ=='tx_femanagement_view_field_ajax_feuser_select') {
						$dbData[$dbName] = serialize($formData[$formularName]->getValue());
					} else  {
						$dbData[$dbName] = $formData[$formularName]->getValue();
					}
				}
			}
			$dbDataNew = $this->cleanDataWrite($dbData);
		} else {
			$dbDataNew =FALSE;
		}	
		return $dbDataNew;
	}

/*
 * ########################## SELECT ##########################
 */	
	
	function fetchData($select) {
		$dataArray = array();
	  while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($select)) {
			$dataArray[] = $this->cleanDataRead($row);
	  }
		return $dataArray;
	}
	
	function getNumRows($select) {
		return $GLOBALS['TYPO3_DB']->sql_num_rows($select);
	}
	
	function buildSqlSelect($sqlQuery) {
		return $GLOBALS['TYPO3_DB']->sql_query($sqlQuery);
	}
	
	function selectData($configArray) {
		$select = $this->buildSelect($configArray);
		return $this->fetchData($select);
	}
	
	function selectSqlData($sqlQuery) {
		$select = $this->buildSqlSelect($sqlQuery);
		return $this->fetchData($select);
	}
	
	function getCount($configArray) {
		$select = $this->buildSelect($configArray);
		return $this->getNumRows($select);
	}
	
	function getSqlCount($sqlQuery) {
		$select = $this->buildSqlSelect($sqlQuery);
		return $this->getNumRows($select);
	}
	
	function buildSelect(&$configArray) {
		$sqlQuery = $this->buildQuery($configArray);
		$select = $this->buildSqlSelect($sqlQuery);
		return $select;
	}

	function buildQuery(&$configArray) {
		if (isset($configArray['fields'])) {
			$fields = $configArray['fields'];
		} else {
			$fields = '*';
		}
		if (isset($configArray['orderBy'])) {
			$orderBy = $configArray['orderBy'];
		} else {
			$orderBy = '';
		}
		if (isset($configArray['groupBy'])) {
			$groupBy = $configArray['groupBy'];
		} else {
			$groupBy = '';
		}
		if (isset($configArray['limit'])) {
			if (isset($configArray['start'])) {
				$limit = $configArray['start'] . ',' . $configArray['limit'];
			} else {
				$limit = '0,' . $configArray['limit'];
			}
		} else {
			$limit = '';
		}
/*
 * -------------------------- SQL-Bedingung --------------------------
 */		
		if (isset($configArray['pid'])) {
			$where = 'pid=' . $configArray['pid'];
		} else if (!isset($configArray['all_pids'])) {
			$where = 'pid=' . $this->storagePid;
		} else {
			$where = 'TRUE ';
		}
		if (!isset($configArray['show_hidden'])) {
			if (isset($configArray['hiddenFieldName'])) {
				$hiddenFieldName = $configArray['hiddenFieldName'];
			} else {
				$hiddenFieldName = 'hidden';
			}
			if (!isset($configArray[$hiddenFieldName])) {
				$where .= ' AND ' . $hiddenFieldName . '=0';
			} else {
				if ($configArray[$hiddenFieldName]=='1') {
					$where .= ' AND ' . $hiddenFieldName . '=1';
				} else if ($configArray[$hiddenFieldName]=='0') {
					$where .= ' AND ' . $hiddenFieldName . '=0';
				}
			}
		}
		if (!isset($configArray['show_deleted'])) {
			if (!isset($configArray['deleted'])) {
				$where .= ' AND deleted=0';
			} else {
				if ($configArray['deleted']=='1') {
					$where .= ' AND deleted=1';
				}else if ($configArray['deleted']=='0') {
					$where .= ' AND deleted=0';
				}
			}
		}
		if (isset($configArray['sqlFilter'])) {
			$where .= ' AND (' . $configArray['sqlFilter'] . ')';
		}
		if (!empty($configArray['table'])) {
			$table = $configArray['table'];
		} else {
			$table = $this->table;
		}
		$query = $GLOBALS['TYPO3_DB']->SELECTquery(
			$fields,  					
	    $table,		
	    $where,		
	    $groupBy,		
	    $orderBy,
	    $limit
	  );
	  return $query;
	}

	function buildJoinQuery($configArray) {
		$sqlJoins = '';
		$sqlGroupBy = '';
		$sqlWhere = $configArray['where'];
		if (!empty($configArray['orderBy'])) {
			$sqlOrderBy = ' ORDER BY ' . $configArray['orderBy'];
		} else {
			$sqlOrderBy = '';
		}
		$fieldsMain = explode(',',$configArray['fields']);
		$sqlFields = $this->table . '.' . implode(',' . $this->table . '.',$fieldsMain);
		$groupByData = array();
		if (is_array($configArray['joins']) && count($configArray['joins'])>0) {
			foreach($configArray['joins'] as $joinData) {
				if (!empty($joinData['fields'])) {
					$fieldsJoin = explode(',',$joinData['fields']);
					if ($joinData['donNotPrependTable']) {
						$sqlFieldsJoin = implode(',',$fieldsJoin);
					} else {
						$sqlFieldsJoin = $joinData['table'] . '.' . implode(',' . $joinData['table'] . '.',$fieldsJoin);
					}
					$sqlFields .= ',' . $sqlFieldsJoin;
				}
				if (!empty($joinData['count'])) {
					$explodeAs = explode('AS',$joinData['count']);
					$sqlCount = 'count(' . $joinData['table'] . '.' . $explodeAs[0] . ')';
					if (count($explodeAs)>0) {
						$sqlCount .= ' AS ' . $explodeAs[1];
					}
					$sqlFields .= ',' . $sqlCount;
				}
				if (!empty($joinData['where'])) {
					$sqlWhere .= ' AND (' . $joinData['where'] . ')';
				}
				if (!empty($joinData['mode'])) {
					$mode = ' ' . $joinData['mode'] . ' ';
				} else {
					$mode = ' INNER JOIN ';
				}
				if ($joinData['donNotPrependTable']) {
					$sqlJoins .= $mode . $joinData['table'] . ' ON ' .
											 $joinData['joinFieldLocal'] . '=' . 
											 $joinData['joinFieldMain'];
				} else {
					$sqlJoins .= $mode . $joinData['table'] . ' ON ' .
							$joinData['table'] . '.' . $joinData['joinFieldLocal'] . '=' .
							$this->table . '.' . $joinData['joinFieldMain'];
				}				
				if (isset($joinData['groupBy'])) {
					$groupByData[] = $joinData['groupBy'];
				}
			}
					
		}
		if (count($groupByData)>0) {
			$sqlGroupBy = ' GROUP BY (' . implode(',',$groupByData) . ')';
		}
		$sqlSelect = 'SELECT ' . $sqlFields . ' FROM ' . $this->table;
		$sqlQuery = $sqlSelect . $sqlJoins . $sqlWhere . $sqlGroupBy . $sqlOrderBy;		
		return $sqlQuery;
	}
	
/*
 * Methode liefert DB-Daten zu einer Id 
 */	
	function getFieldData($uid) {
		return $this->selectField($uid,'title');
	}

	function selectField($uid,$dbField,$configArray=array()) {
		$wert = '';
		if (isset($configArray['sqlFilter'])) {
			$configArray['sqlFilter'] = '(' . $configArray['sqlFilter'] . ') AND (uid=' . $uid . ')';
		} else {
			$configArray['sqlFilter'] = 'uid=' . $uid;
		}
		$configArray['fields'] = $dbField;
		$data = $this->selectData($configArray);
		
		if (count($data)==1) {
			if (isset($data[0][$dbField])) {
				$wert = $data[0][$dbField];
			}
		}
		return $wert;
	}
	
	function selectFieldData($uid,$dbFields,$configArray=array()) {
		$wert = '';
		if (isset($configArray['sqlFilter'])) {
			$configArray['sqlFilter'] = '(' . $configArray['sqlFilter'] . ') AND (uid=' . $uid . ')';
		} else {
			$configArray['sqlFilter'] = 'uid=' . $uid;
		}

		$configArray['fields'] = $dbFields;
		$data = $this->selectData($configArray);
		if (count($data)==1) {
			return $data[0];
		}
		
	}
	
	function selectMmLocal($uid,$mmTable) {
		$configArray['fields'] = 'uid_local';
		$configArray['sqlFilter'] = 'uid_foreign=' . $uid;
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = $mmTable;
		return $this->selectData($configArray);		
	}
	
	function selectMmForeign($uid,$mmTable) {
		$configArray['fields'] = 'uid_foreign';
		$configArray['sqlFilter'] = 'uid_local=' . $uid;
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = $mmTable;
		return $this->selectData($configArray);		
	}
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe='',$pid='',$limit='',$field='uid',$orderBy='',$groupBy='',$table='',$hiddenFieldName='hidden') {
		$data = array();
		if (empty($pid)) {
			$configArray['pid'] = $this->storagePid;
		} else if ($pid!='all') {
			$configArray['pid'] = $pid;
		} else {
			$configArray['all_pids'] = TRUE;
		}
		if (!empty($eingabe)) {
			$configArray['sqlFilter'] =  $field . ' LIKE "%' . $eingabe . '%"';
		}
		if (empty($orderBy)) {
			$orderBy = $field;
		}
		if ($field!='uid') {
			$configArray['fields'] = 'uid,' . $field;
		} else {
			$configArray['fields'] = $field;
		}
		if (!empty($limit)) {
			$configArray['limit'] = $limit;
		}
		$configArray['orderBy'] = $orderBy;
		$configArray['groupBy'] = $groupBy;		
		$configArray['table'] = $table;		
		$configArray['hiddenFieldName'] = $hiddenFieldName;			
		$list = $this->selectData($configArray);		
		foreach ($list as $entry) {
			$data[$entry['uid']] = $entry[$field];
		}
		return $data;
	}

	function getListMmForeign($uid,$mmTable) {
		$data = array();
		$configArray['fields'] = 'uid_foreign';
		$configArray['sqlFilter'] = 'uid_local=' . $uid;
		$configArray['all_pids'] = TRUE;
		$configArray['deleted'] = 'all';
		$configArray['hidden'] = 'all';
		$configArray['table'] = $mmTable;
		$list = $this->selectData($configArray);		
		foreach ($list as $entry) {
			$data[] = $entry['uid_foreign'];
		}
		return $data;
	}
		
	function getListMmForeignList($uidList,$mmTable) {
		$data = array();
		$configArray['fields'] = 'uid_foreign';
		$configArray['sqlFilter'] = 'uid_local IN (' . $uidList . ')';
		$configArray['all_pids'] = TRUE;
		$configArray['deleted'] = 'all';
		$configArray['hidden'] = 'all';
		$configArray['table'] = $mmTable;
		$list = $this->selectData($configArray);		
		foreach ($list as $entry) {
			$data[] = $entry['uid_foreign'];
		}
		return $data;
	}
		
	function getListMmLocal($uid,$mmTable) {
		$data = array();
		$configArray['fields'] = 'uid_local';
		$configArray['sqlFilter'] = 'uid_foreign=' . $uid;
		$configArray['all_pids'] = TRUE;
		$configArray['deleted'] = 'all';
		$configArray['hidden'] = 'all';
		$configArray['table'] = $mmTable;
		$list = $this->selectData($configArray);		
		foreach ($list as $entry) {
			$data[] = $entry['uid_local'];
		}
		return $data;
	}
		
	function getListMmLocalList($uidList,$mmTable) {
		$data = array();
		$configArray['fields'] = 'uid_local';
		$configArray['sqlFilter'] = 'uid_foreign IN (' . $uidList . ')';
		$configArray['all_pids'] = TRUE;
		$configArray['deleted'] = 'all';
		$configArray['hidden'] = 'all';
		$configArray['table'] = $mmTable;
		$list = $this->selectData($configArray);		
		foreach ($list as $entry) {
			$data[] = $entry['uid_local'];
		}
		return $data;
	}
		
/*
 * ########################## INSERT/UPDATE ##########################
 */	
	
	function update($where,$data,$table='') {
		if (empty($table)) {
			$table = $this->table;
		}
		$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table,$where,$data);
		if (!$res && $this->piBase->settings['debug']) {
			t3lib_div::devLog('Fehler beim update: table:' . $table . ', where: ' . print_r($where,true),
												'fe_managment', 0, $data);
		}
		return $res;
	}
	
	function insert($data,$table='') {
		if (empty($table)) {
			$table = $this->table;
		}
		$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery($table,$data);
		if (!$res && $this->piBase->settings['debug']) {
			t3lib_div::devLog('Fehler beim insert: table:' . $table, 'fe_managment', 0, $data);
		}
		return $res;
	}
	
	function delete($where,$table='') {
		if (empty($table)) {
			$table = $this->table;
		}
		return $GLOBALS['TYPO3_DB']->exec_DELETEquery($table,$where);
	}
	
	function deleteElem($uid,$table='') {
		$data['deleted'] = 1;
		$where = 'uid=' . $uid;
		return $this->update($where,$data,$table);
	}
	
	function undeleteElem($uid,$table='') {
		$data['deleted'] = 0;
		$where = 'uid=' . $uid;
		return $this->update($where,$data,$table);
	}
	
	function destroyElem($uid,$table='') {
		$where = 'uid=' . $uid;
		return $this->delete($where,$table);
	}
	
	function hideElem($uid,$hiddenField='hidden',$table='') {
		$data[$hiddenField] = 1;
		$where = 'uid=' . $uid;
		return $this->update($where,$data,$table);
	}
	
	function unhideElem($uid,$hiddenField='hidden') {
		$data[$hiddenField] = 0;
		$where = 'uid=' . $uid;
		return $this->update($where,$data,$table);
	}
	
	function isOwner($uid,$userId) {
		$configArray['all_pids'] = TRUE;
		$configArray['show_hidden'] = TRUE;
		$dbUserId = $this->selectField($uid,'fe_cruser_id',$configArray);
		return $dbUserId==$userId;
	}

/*
 * ########################## CONVERT DATA ##########################
 */	
	
	function cleanDataRead($daten) {
		return $daten;
	}
	
	function cleanDataWrite($daten) {
		return $daten;
	}
	
}	

?>

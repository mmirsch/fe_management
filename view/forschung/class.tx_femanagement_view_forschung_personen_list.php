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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_forschung_personen_list extends tx_femanagement_view_form_list {

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}		
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			$out .= '<td class="' .  $key . '">';
			switch ($key) {
			case 'last_name':
				$title = $this->getTitleCreateLinkSingleView();		
				$viewIndex = array_search('view',$permissions);
				if ($viewIndex!==FALSE) {
					$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['last_name'],'textLink',$title,'_blank',TRUE);
					unset($permissions[$viewIndex]);
				} else {
					$out .= $elem['last_name'];
				}
				break;
			case 'first_name':
			case 'username':
			case 'title':
				$out .= $elem[$key];
				break;	
			case 'email':
				if (!empty($elem['email'])) {
					$out .=  '<a href="mailto:' . $elem['email'] . '">' . $elem['email'] . '</a>';
				}
				break;
			}
			$out .= '</td>';
		}
		$out .= '<td class="actions">';
		$out .= $this->showActions($elem,$permissions);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
		
	function getNewelemButtonTitle() {
		return 'Neue Person anlegen'; 
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		$configArray['all_pids'] = 'TRUE';		
		$configArray['sqlFilter'] = 'TRUE';
		$configArray['fields'] = 'uid,title,first_name,last_name,email,username,genehmigung_veroeff,deleted,hidden';
		if (isset($this->args['deleted'])) {
			if ($this->args['deleted']!='all' && $this->args['deleted']!='') {
				$configArray['where'] .= ' AND tx_cal_location.deleted=' . $this->args['deleted'];
			}
		}
		if (isset($this->args['hidden'])) {
			if ($this->args['hidden']!='all' && $this->args['hidden']!='') {
				$configArray['where'] .= ' AND tx_cal_location.hidden=' . $this->args['hidden'];
			}
		}
		$configArray['all_pids'] = 'TRUE';
		if (isset($this->args['volltextsuche']) && $this->args['volltextsuche']!='') {
			$configArray['where'] .= ' AND (first_name LIKE "%' . $this->args['volltextsuche'] . '%" OR ' .
															 'last_name LIKE "%' . $this->args['volltextsuche'] . '%" OR ' .
															 'username LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		/*
		 * Sortierung prüfen
		*/
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$sortMode = $this->args['sortMode'];
			} else {
				$sortMode = 'ASC';
			}
			switch ($this->args['sortField']) {
				case 'last_name':
				case 'first_name':
				case 'username':
					$sortField = $this->args['sortField'];
					break;
				default:
					$sortField = 'last_name';
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		}
		
		if (!empty($this->args['export'])) {
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'Orte');
			exit();
		} else {
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
			$out .= $this->exitSqlAjaxFilter($sqlQuery,$limit);
			if (!empty($limit)) {
				$sqlQuery .= $limit;
			}
			$daten = $this->model->selectSqlData($sqlQuery);
		
			if (isset($data['urlArgs']['page'])) {
				$page = $data['urlArgs']['page'];
			} else {
				$page = '';
			}
			foreach ($daten as $index=>$elem) {
				$daten[$index]['permissions'] = $this->getPermissions($elem,$page);
			}
			$out .= $this->createDataList($daten);
			return $out;
		}

	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_cal_location_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_cal_location_list.php']);
}

?>
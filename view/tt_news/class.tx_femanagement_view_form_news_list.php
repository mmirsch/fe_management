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

 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_form_news_list extends tx_femanagement_view_form_list {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_news',$this->piBase,$pid);
	}
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'title':
					$out .= '<td class="title">';
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
						$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE);
					} else {
						$out .= $title;
					}
					$newseintragOffline = ($elem['endtime']<=time() && $elem['endtime']>0) ||
																($elem['starttime']>=time() && $elem['starttime']>0) ||
																$elem['hidden'] || 
																$elem['deleted'];
					if ($newseintragOffline) {
						unset($permissions[$viewIndex]);
					}
					$out .= '</td>';
					break;
				case 'tx_hetools_sortierfeld':
					$out .= '<td class="' . $key . '">' .
									$elem[$key] . 
									'</td>';
					break;
				case 'datetime':
				case 'starttime':
				case 'endtime':
				case 'archivedate':
					$out .= '<td class="' . $key . '">';
					if (!empty($elem[$key])) {
						$out .= date('d.m.Y', $elem[$key]);
					} else {
						$out .= ' - ';
					}
					$out .= '</td>';
					break;
				case 'fe_cruser_id':
					$out .= '<td class="fe_cruser_id">';
					if (empty($elem['fe_cruser_id'])) {
						$userdata = $this->model->getUserData($elem);
						if (!empty($userdata)) {
							$out .= '<a href="mailto:' . $userdata['email'] . '?subject=Ihr Newseintrag">' . $userdata['name'] . '</a>';
						}
					} else {
						$out .= '<a href="mailto:' . $elem['email'] . '?subject=Ihr Newseintrag">' . $elem['fe_cruser_id'] . '</a>';
					
					}
					$out .= '</td>';
					break;
			}
		}
		$out .= '<td class="actions">';
		$previewLinkUrl = $this->createPreviewLinkUrl($elem['uid']);
		$out .= $this->showActions($elem,$permissions,$previewLinkUrl);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
	
	function getDataExportFieldTitle($field) {
			switch ($field) {
			case 'title':
				return 'Titel';
			case 'short':
				return 'Untertitel';
			case 'bodytext':
				return 'Text';
			case 'datetime':
				return 'Datum';
			case 'starttime':
				return 'Startdatum';
			case 'endtime':
				return 'Stopdatum';
			case 'archivedate':
				return 'Archivdatum';
		}
		return '';
	}	
	
	function formatExportItem($field,$value) {
		switch ($field) {
			case 'datetime':
			case 'starttime':
			case 'archivedate':
				if (!empty($value)) {
					$out = date('d.m.Y', $value);
				} else {
					$out = ' ';
				}
				break;
			case 'cruser_id':
			case 'fe_cruser_id':
			case 'email':
				$out = '';
				break;
				
			default:
				if (empty($value)) {
					$out = ' ';
				} else {
					$out = $value;
				}
				break;
		}
		return $out;
	}
		
	function createPreview($uid,$text,$linkClass,$title,$target='_blank') {
		$linkUrl = $this->createPreviewLinkUrl($uid);
		$link = '<a class="' . $linkClass . '" target="' . $target . '" class="' . $linkClass . 
					  '" title="' . $title . '" href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}

	function getNewelemButtonTitle() {
		return 'Neue Nachricht anlegen'; 
	}
	
	function getCategories() {
		$newsModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories',$this->piBase,$this->pid);
		return $newsModel->getList('','all');
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		$configArray['joins'] = array(
				array('table'=>'fe_users',
						'fields'=>'name as fe_cruser_id,email',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'fe_cruser_id',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'be_users',
						'fields'=>'realName as be_cruser_id,email',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'cruser_id',
						'mode'=>'LEFT JOIN',
				),
		);
		if (isset($this->args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tt_news.title LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tt_news.short LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tt_news.bodytext LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		if (!empty($this->args['personensuche'])) {
			$configArray['where'] .= ' AND (' .
															 ' fe_users.name LIKE "%' . $this->args['personensuche'] . '%"' .
															 ' OR be_users.realName LIKE "%' . $this->args['personensuche'] . '%"' .
															 ')';
		}
		if (isset($this->args['cat'])) {
			if ($this->args['cat']!='all') {
				$catModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories',$this->piBase,$this->pid);
				$ids = $catModel->getMmEventsForCat($this->args['cat']);
				$idList = implode(',',$ids);
				$configArray['where'] .= ' AND (tt_news.uid IN (' . $idList . '))';
			}
		}
		if (!empty($this->args['datetimeStart'])) {
			$date = explode('.',$this->args['datetimeStart']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tt_news.datetime>=' . $dateTstamp;
		}
		if (!empty($this->args['datetimeEnd'])) {
			$date = explode('.',$this->args['datetimeEnd']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tt_news.datetime<=' . $dateTstamp;
		}
		if (!empty($this->args['starttimeStart'])) {
			$date = explode('.',$this->args['starttimeStart']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tt_news.starttime>=' . $dateTstamp;
		}
		if (!empty($this->args['starttimeEnd'])) {
			$date = explode('.',$this->args['starttimeEnd']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tt_news.starttime<=' . $dateTstamp;
		}
		if ($this->args['hidden']!='all') {
			$configArray['where'] .= ' AND tt_news.hidden=' . $this->args['hidden'];
		}
		if ($this->args['deleted']!='all') {
			$configArray['where'] .= ' AND tt_news.deleted=' . $this->args['deleted'];
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
				case 'fe_cruser_id':
					$sortField = 'fe_users.last_name';
					break;
				default:
					$sortField = 'tt_news.' . $this->args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		}
		
		if (!empty($this->args['export'])) {
			$configArray['fields'] = 'title,short,bodytext,datetime,starttime,endtime,archivedate';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'newsbeitraege');
			exit();
		} else {
			$configArray['fields'] = 'uid,title,cruser_id,fe_cruser_id,datetime,starttime,endtime,archivedate,tx_hetools_sortierfeld,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			//t3lib_div::debug($sqlQuery);			
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

	function deleteEntry($data) {
		$erg = parent::deleteEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function undeleteEntry($data) {
		$erg = parent::undeleteEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function hideEntry($data) {
		$erg = parent::hideEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function unhideEntry($data) {
		$erg = parent::unhideEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function postProcessing($data) {
		if (isset($data['ctrl'])) {
			$controller = t3lib_div::makeInstance($data['ctrl']);
			$controller->postProcessingDataChange($data['args']);
		}
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_news_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_news_list.php']);
}

?>
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
class tx_femanagement_view_form_cal_event_list extends tx_femanagement_view_form_list {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createDataList(&$dataList) {				
		if (isset($this->urlArgs['page'])) {
			$page = $this->urlArgs['page'];
		} else {
			$page = '';
		}
		$fieldList = $this->controller->getListViewFields();
		$out .= '<table id="fe_management_view_data_list">';
		$rowClass = 'odd';
		$out .=  $this->showColTitles($fieldList);
		foreach ($dataList as $elem) {
			$permissions = $this->getPermissions($elem,$page);
			if (count($permissions)>0) {
				$out .=  $this->showListItem($elem,$fieldList,$permissions,$rowClass);
				if ($rowClass == 'odd') {
					$rowClass = 'even';
				} else {
					$rowClass = 'odd';
				}
			}
		}
		$out .= '</table>';
		$out .= '<script type="text/javascript">
					$(".sortTitle").click(function(event) {
						var field = $(this).attr("id");
						sortReload(field);
					});
					</script>
					';
		$out .= $this->createDataListJs();		
		return $out;
	}
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';

		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'title':
					$out .= '<td class="title">';
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE && !$elem['hidden'] && !$elem['deleted']) {
						$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE,'',TRUE);
					} else {
						unset($permissions[$viewIndex]);
						$out .= $elem['title'];
					}
					$out .= '</td>';
					break;
				case 'start_date':
					$out .= '<td class="datum">';
					$out .= date('d.m.Y', $elem['start_date']);
					$out .= '</td>';
					break;
				case 'end_date':
					$out .= '<td class="datum">';
					$out .= date('d.m.Y', $elem['end_date']);
					$out .= '</td>';
					break;
				case 'start_time':
					$out .= '<td class="datum">';
					$start = $elem['start_time'];
					$stunden = $start / 3600;
					$minuten = ($start % 3600) / 60;
					$von = sprintf("%02d:%02d",$stunden,$minuten);
					$out .= $von;
					$out .= '</td>';
					break;
				case 'end_time':
					$out .= '<td class="datum">';
					$start = $elem['end_time'];
					$stunden = $start / 3600;
					$minuten = ($start % 3600) / 60;
					$von = sprintf("%02d:%02d",$stunden,$minuten);
					$out .= $von;
					$out .= '</td>';
					break;
				case 'location_id':
					$out .= '<td class="ort">';
					$out .= $elem['location_id'];
					$out .= '</td>';
					break;
				case 'organizer_id':
					$out .= '<td class="veranstalter">';
					$out .= $elem['organizer_id'];
					$out .= '</td>';
					break;
				case 'fe_cruser_id':
					$out .= '<td class="user">';
					if (empty($elem['fe_cruser_id'])) {
						$userdata = $this->model->getUserData($elem);
						if (!empty($userdata)) {
							$out .= '<a href="mailto:' . $userdata['email'] . '?subject=Ihr Kalendereintrag">' . $userdata['name'] . '</a>';
						}
					} else {
						$out .= '<a href="mailto:' . $elem['email'] . '?subject=Ihr Kalendereintrag">' . $elem['fe_cruser_id'] . '</a>';
						
					}
					$out .= '</td>';
					break;
			}
		}
		$out .= '<td class="actions">';
		$previewLinkUrl = $this->createCalPreviewLinkUrl($elem['uid']);
		
		$out .= $this->showActions($elem,$permissions,$previewLinkUrl);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
	
	function showActions($elem,$permissions,$previewLinkUrl='') {
		$out = parent::showActions($elem,$permissions,$previewLinkUrl='');
		if (in_array('infoscreen',$permissions)) {
			$datum = $elem['start_date'];
			$jahr = date('Y',$datum);
			$monat = date('m',$datum);
			$tag = date('d',$datum);
			$zeit = $elem['start_time'];
			$stunde = gmdate('G',$zeit);
			$minute = gmdate('i',$zeit);
			$linkUrl = 'http://www.hs-esslingen.de/index.php?id=129045&datSim=1' . 
									'&j=' . $jahr . '&m=' . $monat . '&t=' . $tag . '&s=' . $stunde . '&mi=' . $minute;
			$out .= '<a class="icon-actions t3-icon-x-content-template" href="' . $linkUrl . '" target="_blank"></a>';
		}
		return $out;
	}
	
	function getDataExportFieldTitle($field) {
			switch ($field) {
			case 'title':
				return 'Titel';
			case 'description':
				return array('Beschreibung',
										 'Referent/in',
										 'Zielgruppe',
										 'Link');
			case 'location_id':
				return 'Veranstaltungsort';
			case 'organizer_id':
				return 'Veranstalter';
			case 'start_date':
				return 'Startdatum';
			case 'end_date':
				return 'Enddatum';
			case 'start_time':
				return 'Startzeit';
			case 'end_time':
				return 'Endzeit';
		}
		return '';
	}
	
	function formatExportItem($field,$value) {
		switch ($field) {
			case 'start_date':
			case 'end_date':
				if (!empty($value)) {
					$out = date('d.m.Y', $value);
				} else {
					$out = ' ';
				}
				break;
			case 'start_time':
			case 'end_time':
				if (!empty($value)) {
					$stunden = $value / 3600;
					$minuten = ($value % 3600) / 60;
					$out = sprintf("%02d:%02d",$stunden,$minuten);
				} else {
					$out = ' ';
				}
				break;
			case 'cruser_id':
			case 'fe_cruser_id':
			case 'email':
				$out = '';
				break;
			case 'description':
				if (!empty($value)) {
					$beschreibung = $this->zeileExtrahieren('<b>Beschreibung</b>:','<b>Referent/in</b>:',$value);
					$referent = $this->zeileExtrahieren('<b>Referent/in</b>:','<b>Zielgruppe</b>:',$value);
					$zielgruppe = $this->zeileExtrahieren('<b>Zielgruppe</b>:','<b>Link</b>:',$value);
					$link = $this->zeileExtrahieren('<b>Link</b>:',FALSE,$value);
					if (empty($beschreibung) && empty($referent) && 
							empty($zielgruppe) && empty($link)) {
						$beschreibung = $value;
					}
				} else {
					$beschreibung = ' ';
					$referent = ' ';
					$zielgruppe = ' ';
					$link = ' ';
				}
				$out = array($beschreibung,
										 $referent,
										 $zielgruppe,
										 $link);
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
	
	function getNewelemButtonTitle() {
		return 'Neuen Termin anlegen'; 
	}
		
	function createCalPreviewLinkUrl($uid) {
		$configArray['show_hidden'] = 1;
		$configArray['all_pids'] = 1;
		$startDate = $this->model->selectField($uid,'start_date',$configArray);
		$linkUrl = $this->controller->createPreviewLink($uid,$startDate,$this->previewPid);
		return $linkUrl;
	}
		
	function createCalPreview($uid,$text,$linkClass,$title,$target='_blank') {
		$linkUrl = $this->createCalPreviewLinkUrl($uid);
		$link = '<a class="' . $linkClass . '" target="' . $target . '" class="' . $linkClass . 
					  '" title="' . $title . '" href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}

	function getCategories() {
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$this->piBase,$this->pid);
		return $catModel->getList();
	}
	
	function ajaxFilter($data) {
		$username = $GLOBALS['TSFE']->fe_user->user['username'];
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		$configArray['joins'] = array(
																array('table'=>'tx_cal_location',
																			'fields'=>'name as location_id',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'location_id',
																			'mode'=>'LEFT JOIN',
																),
																array('table'=>'tx_cal_organizer',
																			'fields'=>'name as organizer_id',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'organizer_id',
																			'mode'=>'LEFT JOIN',
																),
																array('table'=>'fe_users',
																			'fields'=>'name as fe_cruser_id,email,username',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'fe_cruser_id',
																			'mode'=>'LEFT JOIN',
																),
																array('table'=>'be_users',
																		'fields'=>'realname,username',
																		'joinFieldLocal'=>'uid',
																		'joinFieldMain'=>'cruser_id',
																		'mode'=>'LEFT JOIN',
																),
				
															); 
		$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
		if ($this->controller->isReviser($domainInfoscreen)) {
			$configArray['where'] .= ' AND fe_users.username = "' . $username . '"';
		}
		if (isset($this->args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tx_cal_event.title LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tx_cal_event.description LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tx_cal_organizer.name LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tx_cal_location.name LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		if (!empty($this->args['personensuche'])) {
			$configArray['where'] .= ' AND (' . 
															 'fe_users.name LIKE "%' . $this->args['personensuche'] . '%"' . 
															 ' OR be_users.realname LIKE "%' . $this->args['personensuche'] . '%"' . 
															 ')';
		}
		
		if (isset($this->args['cat']) && $this->args['cat']!='all') {
			$calCatModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$this->piBase,$this->pid);
			$ids = $calCatModel->getMmEventsForCat($this->args['cat']);
			$idList = implode(',',$ids);
			$configArray['where'] .= ' AND (tx_cal_event.uid IN (' . $idList . '))';
		} else if ($this->args['calMode']=='Infoscreen') {
			$calCatModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$this->piBase,$this->pid);
			$catListInfoscreen = implode(',',$calCatModel->getCatIdList('Infoscreen%'));
			$ids = $calCatModel->getMmEventsForCatList($catListInfoscreen);
			$idList = implode(',',$ids);
			$configArray['where'] .= ' AND (tx_cal_event.uid IN (' . $idList . '))';
		}
		if (!empty($this->args['dateStart'])) {
			$dateStart = explode('.',$this->args['dateStart']);
			$dateStringStart = sprintf('%04d%02d%02d',$dateStart[2],$dateStart[1],$dateStart[0]);
			if (!empty($this->args['dateEnd'])) {
				$dateEnd = explode('.',$this->args['dateEnd']);
				$dateStringEnd = sprintf('%04d%02d%02d',$dateEnd[2],$dateEnd[1],$dateEnd[0]);
				if ($dateStringStart==$dateStringEnd) {
					$configArray['where'] .= ' AND (tx_cal_event.start_date=' . $dateStringStart .
																	 ' OR (tx_cal_event.start_date<>tx_cal_event.end_date AND ' .
																	 '(tx_cal_event.start_date<=' . $dateStringStart . ' AND tx_cal_event.end_date>=' . $dateStringEnd . ')))';
				} else {
					$configArray['where'] .= ' AND (tx_cal_event.start_date>=' . $dateStringStart . ' AND tx_cal_event.start_date<=' . $dateStringEnd . ')';
				}
			} else {
				$configArray['where'] .= ' AND (tx_cal_event.start_date>=' . $dateStringStart . ')';
			}
		} else {
			if (!empty($this->args['dateEnd'])) {
				$date = explode('.',$this->args['dateEnd']);
				$dateString = sprintf('%04d%02d%02d',$date[2],$date[1],$date[0]);
				$configArray['where'] .= ' AND (tx_cal_event.end_date<=' . $dateString . ')';
			}
		}
		 
		 
		if ($this->args['hidden']!='all' && $this->args['hidden']!='') {
			$configArray['where'] .= ' AND tx_cal_event.hidden=' . $this->args['hidden'];
		}
		if ($this->args['deleted']!='all' && $this->args['deleted']!='') {
			$configArray['where'] .= ' AND tx_cal_event.deleted=' . $this->args['deleted'];
		}
		if ($this->args['self']) {
			$configArray['where'] .= ' AND fe_users.username = "' . $username . '"';
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
			case 'location_id':
				 $sortField = 'tx_cal_location.name';
				 break;
			case 'organizer_id':
				 $sortField = 'tx_cal_organizer.name';
				 break;
			case 'fe_cruser_id':
				 $sortField = 'fe_users.last_name';
				 break;
			default:
				 $sortField = 'tx_cal_event.' . $this->args['sortField'];
				 break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		}
		
		if (!empty($this->args['export'])) {
			$configArray['fields'] = 'title,description,start_date,end_date,start_time,end_time';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'kalendertermine');
			exit();
		} else {
			$configArray['fields'] = 'uid,title,cruser_id,start_date,end_date,start_time,end_time,deleted,hidden';
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
	
	function destroyEntry($data) {
		$erg = parent::destroyEntry($data);
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
			$controller->postProcessingDataChange($data['uid']);
		}
	}
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_cal_event_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/cal/class.tx_femanagement_view_form_cal_event_list.php']);
}

?>
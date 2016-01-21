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
class tx_femanagement_view_events_list extends tx_femanagement_view_form_list {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_events',$this->piBase,$pid);
	}
				
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		$model = t3lib_div::makeInstance('tx_femanagement_model_events',$this->piBase,$this->pid);
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'title':
					$out .= '<td class="title">';
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
						$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE,'',TRUE);
					} else {
						$out .= $title;
					}
					$out .= '</td>';
					break;
				case 'description':
					$descriptionClean = strip_tags($elem[$key]);
					$descriptionClean = nl2br($descriptionClean);
					$descriptionClean = substr($descriptionClean,0,200);
					$out .= '<td class="beschreibung">';
					$out .= $descriptionClean;
					$out .= '</td>';
					break;
/*
				case 'event_date':
					$out .= '<td class="datum">';
					$out .= date('d.m.Y', $elem[$key]);
					$out .= '</td>';
					break;
				case 'start':
				case 'end':
					$out .= '<td class="datum">';
					$stunden = $elem[$key] / 3600;
					$minuten = ($elem[$key] % 3600) / 60;
					$time = sprintf("%02d:%02d",$stunden,$minuten);
					$out .= $time;
					$out .= '</td>';
					break;		
*/
			}
		}
		$out .= '<td class="actions">';
		$out .= $this->showActions($elem,$permissions);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}

	function getNewelemButtonTitle() {
		return 'Neuen Eintrag anlegen'; 
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		if (isset($this->args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tx_femanagement_events.title LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		if ($this->args['hidden']!='all') {
			$configArray['where'] .= ' AND tx_femanagement_events.hidden=' . $this->args['hidden'];
		}
		if ($this->args['deleted']!='all') {
			$configArray['where'] .= ' AND tx_femanagement_events.deleted=' . $this->args['deleted'];
		}
/*		
		if (!empty($this->args['dateStart'])) {
			date_default_timezone_set('UTC');
			$dateStart = explode('.',$this->args['dateStart']);
			$timestampStart = mktime(0, 0, 0, $dateStart[1], $dateStart[0], $dateStart[2]);
			if (!empty($this->args['dateEnd'])) {
				$dateEnd = explode('.',$this->args['dateEnd']);
				$timestampEnd = mktime(0, 0, 0, $dateEnd[1], $dateEnd[0], $dateEnd[2]);
				$configArray['where'] .= ' AND (tx_femanagement_events.event_date>=' . $timestampStart . ' AND tx_femanagement_events.event_date<=' . $timestampEnd . ')';
			} else {
				$configArray['where'] .= ' AND (tx_femanagement_events.event_date>=' . $timestampStart . ')';
			}
		} else {
			if (!empty($this->args['dateEnd'])) {
				$date = explode('.',$this->args['dateEnd']);
				$timestamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
				$configArray['where'] .= ' AND (tx_femanagement_events.end_date<=' . $timestamp . ')';
			}
		}		
*/		
		/*
		 * Sortierung prüfen
		*/
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$sortMode = $this->args['sortMode'];
			} else {
				$sortMode = 'ASC';
			}
			$sortField = 'tx_femanagement_events.' . $this->args['sortField'];
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'tx_femanagement_events.title ASC';
		}
		
		$configArray['fields'] = 'uid,title,description,street,city,zip,building,room,email_text,pic,hidden,deleted';
		$sqlQuery = $this->model->buildJoinQuery($configArray);

		$out = '';
		
		$out .= $this->exitSqlAjaxFilter($sqlQuery,$limit);
	
		if (!empty($limit)) {
			$sqlQuery .= $limit;
		}
	
		$daten = $this->model->selectSqlData($sqlQuery);
		foreach ($daten as $index=>$elem) {
			$daten[$index]['permissions'] = $this->getPermissions($elem,$page,$this->model);
		}
		$out .= $this->createDataList($daten);
		return $out;
	}

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/events/class.tx_femanagement_view_events_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/events/class.tx_femanagement_view_events_list.php']);
}

?>
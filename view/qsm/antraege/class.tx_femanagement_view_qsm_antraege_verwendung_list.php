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
class tx_femanagement_view_qsm_antraege_verwendung_list extends tx_femanagement_view_qsm_antraege_list {

	function __construct(&$piBase,$pid,$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}		
		
	function createDataList(&$dataList) {
		if (empty($dataList)) {
			$out = '<h2>Für Ihre Auswahl gibt es (noch) keine Anträge</h2>';
		} else {
			$bereichsDaten = array();
			foreach ($dataList as $eintrag) {
				if (empty($bereichsDaten[$eintrag['bereichs_titel']])) {
					$bereichsDaten[$eintrag['bereichs_titel']] = array();
				}
				$bereichsDaten[$eintrag['bereichs_titel']][] = $eintrag;
			}
			$personenModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users');
			$budgetModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_budgets');
			$out = '';
			foreach ($bereichsDaten as $bereich=>$eintraege) {
				$out .= '<table class="verwendungsdaten">';
				$out .= '<thead><tr><th class="zentriert" colspan="5">' . $bereich . '</th></tr></thead>
				<tr class="ueberschrift">
				<td class="titel">Maßnahme</th>
				<td class="semester">Bezugssemester</th>
				<td class="verantw">Verantwortliche / Verantwortlicher</th>
				<td class="budget">Budget</th>
				<td class="stellen">Anzahl Stellen</th>
				</tr>
				</thead>';
				$out .= '<tbody>';
				$rowClass = 'odd';
			
				foreach ($eintraege as $eintragsDaten) {
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
					$titel = $this->createLinkText($eintragsDaten['uid'],'&tx_femanagement[mode]=view',$eintragsDaten['title'],'textLink','');
			
					$bezugssemester = $eintragsDaten['bezugssemester'];
					$budgetSumme = $budgetModel->gibAntragsBudgetSumme($eintragsDaten['uid'],'bewbudget');
					if (!empty($budgetSumme)) {
						$budget = $budgetSumme . '&nbsp;&euro;';
					} else {
						$budget = '';
					}
					$verantwortliche = $personenModel->gibAntragsVerantwortliche($eintragsDaten['uid']);
					if (count($verantwortliche)>0) {
						$verantwortliche = implode('<br />',$verantwortliche);
					} else {
						$verantwortliche = $verantwortliche[0];
					}
					if (empty($eintragsDaten['persstellen'])) {
						$persstellen = '';
					} else {
						$persstellen = $eintragsDaten['persstellen'];
					}
					$out .= '
					<tr class="' . $rowClass . '">
					<td class="titel">' . $titel . '</td>
					<td class="semester">' . $bezugssemester . '</td>
					<td class="verantw">' . $verantwortliche . '</td>
					<td class="budget">' . $budget . '</td>
					<td class="stellen">' . $persstellen . '</td>
					</tr>';
				}
				$out .= '</tbody></table>
				';
			}
		}
		return $out;
	}

	function createLinkText($uid,$additionalParams,$text,$linkClass,$title,$target='_blank',$popup=FALSE,$id='',$norefresh=FALSE) {
		$id = $this->pageId;
/* 
 * Seitenmodus mitübertragen
 */		
		$get = t3lib_div::_GET();
		if (isset($get['tx_femanagement']['page'])) {
			$additionalParams .= '&tx_femanagement[page]=' .
														$get['tx_femanagement']['page'];
		}
		$hash = md5($additionalParams . $uid);
		$idParam = '&tx_femanagement[uid]=' . $uid;
		$linkUrl = 'index.php?id=' . $id . $additionalParams . $idParam;
		$additionalCode = '';
		$link = '<a class="' . $linkClass . '" id="link_' . $hash . '" target="' . $target . '" class="' . $class . '" title="' . $title . '" ' . 
					  'target="blank" ' . $additionalCode . 'href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}

	function showListItem(&$personenModel,&$budgetModel,$elem,$fieldList,$permissions,$rowClass='',$actions=TRUE) {
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
				case 'start':
					$out .= '<td class="datum">';
					$out .= date('d.m.Y', $elem[$key]);
					$out .= '</td>';
					break;
				case 'ende':
					$out .= '<td class="datum">';
					$out .= date('d.m.Y', $elem[$key]);
					$out .= '</td>';
					break;
				case 'bereich':
					$out .= '<td class="bereich">';
					$out .= $elem[$key];
					$out .= '</td>';
					break;
				case 'antragsteller':
					$out .= '<td class="user">';
					$urlPersonenSeite = 'index.php?id=' .  $elem['antragsteller_profilseite'];
					$out .= '<a target="_blank" href="' . $urlPersonenSeite . '">' . $elem['antragsteller_name'] . '</a>';
					$out .= '</td>';
					break;
				case 'masnanr':
					$out .= '<td class="' . $key . '">';
					$out .= $elem[$key];
					$out .= '</td>';
					break;
				case 'persstellen':
					$out .= '<td class="' . $key . '">';
					if (!empty($elem[$key])) {
						$out .= $elem[$key];
					}
					$out .= '</td>';
					break;
				case 'bezugssemester':
					$out .= '<td class="' . $key . '">';
					$out .= $elem[$key];
					$out .= '</td>';
					break;
				case 'status':
					$wert = tx_femanagement_model_qsm_antraege::gibAntragsStatus($elem[$key]);
					$out .= '<td class="' . $key . '">';
					$out .= $wert;
					$out .= '</td>';
					break;
				case 'bewbudget':
					$out .= '<td class="' . $key . '">';
					$budgetSumme = $budgetModel->gibAntragsBudgetSumme($elem['uid'],'bewbudget');
					if (!empty($budgetSumme)) {
						$out .= $budgetSumme . '&nbsp;&euro;';
					} else {
						$out .= 0;
					}
					$out .= '</td>';
					break;
				case 'verantwortliche':
					$out .= '<td class="' . $key . '">';
					$verantwortliche = $personenModel->gibAntragsVerantwortliche($elem['uid']);
					if (count($verantwortliche)>0) {
						$out .= implode('<ber />',$verantwortliche);
					}
					$out .= '</td>';
					break;
			}
		}
		if ($actions) {
			$out .= '<td class="actions">';
			$out .= $this->showActions($elem,$permissions);
			$out .= '</td>';
		}
		$out .= '</tr>';
		return $out;
	}
	
	function getNewelemButtonTitle() {
		return 'Neuen Antrag anlegen'; 
	}
		
	function initAjaxFilter(&$data) {
		$this->args = $data['args'];
		$this->uid = $data['uid'];
		$this->pid = $data['pid'];
		$this->page = $data['page'];
		$this->az = $data['az'];
		$this->previewPid = $data['previewPid'];
		$this->setPageId($data['id']);
		$this->setPage($this->page);
		$this->setModelName($data['model']);
		$this->setControllerName($data['ctrl']);
		$this->singlePageConfig = $this->controller->getSinglePageConfig();
		if (isset($data['tx_femanagement'])) {
			$this->urlArgs = $data['tx_femanagement'];
		}
	}

	function ajaxFilter($data) {
		$this->controller = t3lib_div::makeInstance($data['ctrl'],'',$data);
		$this->initAjaxFilter($data);
		$out = $this->controller->createAjaxData($this,$data);
		return $out;
	}
	
	function getDataExportFieldTitle($field) {
		switch ($field) {
			case 'uid':
				return 'Nr.';
			case 'title':
				return 'Titel';
			case 'short_title':
				return 'Kurztitel';
			case 'ziel':
				return 'Ziel';
			case 'start':
				return 'Beginn';
			case 'ende':
				return 'Ende';
			case 'antragsteller_name':
				return 'Antragsteller';
			case 'verantw':
				return 'Verantwortliche';
			case 'bereichs_titel':
				return 'Bereich';
			case 'einrichtungs_titel':
				return 'Einrichtung';
		}
		return '';
	}
	
	function formatExportItem($field,$value) {
		switch ($field) {
			case 'uid':
			case 'title':
			case 'short_title':
			case 'bereichs_titel':
			case 'einrichtungs_titel':
				$out .= $value;
				break;
			case 'ziel':
				$out .= $value;
				break;
			case 'start':
				$out .= date('d.m.Y', $value);
				break;
			case 'ende':
				$out .= date('d.m.Y', $value);
				break;
			case 'antragsteller_name':
				$out .= $value;
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
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/antraege/class.tx_femanagement_view_qsm_antraege_verwendung_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/antraege/class.tx_femanagement_view_qsm_antraege_verwendung_list.php']);
}

?>
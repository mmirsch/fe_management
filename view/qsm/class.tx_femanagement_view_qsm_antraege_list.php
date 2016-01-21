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

require_once(t3lib_extMgm::extPath('fe_management') . 'view/class.tx_femanagement_view_form_list.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/qsm/class.tx_femanagement_model_qsm_antraege.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/qsm/class.tx_femanagement_model_qsm_mittel.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_qsm_antraege_list extends tx_femanagement_view_form_list {

	function __construct(&$piBase=NULL,$pid,$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}		
		
	function createDataList(&$dataList,$actions=TRUE) {
		$templateCode = $this->controller->getTemplateCodeListView();
		$fieldList = $this->controller->getListViewFields();
		if (isset($this->urlArgs['page'])) {
			$page = $this->urlArgs['page'];
		} else {
			$page = '';
		}
		if (!empty($templateCode)) {
			$rowClass = 'odd';
			if ($this->controller->showTitlesListView()) {
				$out .=  $this->showColTitlesTemplate($fieldList,$templateCode);
			}
			foreach ($dataList as $elem) {
				if (is_array($elem['permissions']) && count($elem['permissions'])>0) {
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
					$out .=  $this->showTemplateListItem($templateCode,$elem,$fieldList,$elem['permissions'],$rowClass);
				}
			}
			$out .= '</table>';
		} else {
			$out .= '<table id="fe_management_view_data_list">';
			$rowClass = 'odd';
			$out .=  $this->showColTitles($fieldList,$actions);
			$personenModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users');
			$budgetModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_budgets');
			foreach ($dataList as $elem) {
				if (is_array($elem['permissions']) && count($elem['permissions'])>0) {
					$out .=  $this->showListItem($personenModel,$budgetModel,$elem,$fieldList,$elem['permissions'],$rowClass,$actions);
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
				}
			}
			$out .= '</table>';
		}
		$out .= '<script type="text/javascript">
					$(".sortTitle").click(function(event) {
						var field = $(this).attr("id");
						sortReload(field);
					});
					</script>
					';
		return $out;
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
	
	function showActions($elem,$permissions,$previewLinkUrl='') {
		$out = '';
		foreach($permissions as $action) {
			switch ($action) {
				case 'view': 
					$out .= $this->createLinkSingleView($elem['uid'],$previewLinkUrl);
					break;
				case 'edit': 
					$out .= $this->createLinkSingleEdit($elem['uid']);
					break;
				case 'copy': 
					$out .= $this->createLinkSingleCopy($elem['uid']);
					break;
				case 'delete': 
					if (!$elem['deleted']) {
						$out .= $this->createLinkSingleDelete($elem['uid']);
					} 
					break;
				case 'undelete': 
					if ($elem['deleted']) {
						$out .= $this->createLinkSingleUndelete($elem['uid']);
					}
					break;
				case 'destroy': 
					if ($elem['deleted']) {
						$out .= $this->createLinkSingleDestroy($elem['uid']);
					} 
					break;
				case 'hide': 
					if (!$elem['deleted'] && !$elem['hidden']) {
						$out .= $this->createLinkSingleHide($elem['uid']);
					} else {
						if (!$elem['deleted'] && $elem['hidden']) {
							$out .= $this->createLinkSingleUnhide($elem['uid']);
						}
					}
					break;
				case 'ablehnen': 
					$out .= $this->createLinkSingleAblehnen($elem['uid']);
					break;
				case 'bewilligen': 
					$out .= $this->createLinkSingleBewilligen($elem['uid']);
					break;
				case 'verbuchen': 
					$out .= $this->createLinkSingleVerbuchen($elem['uid']);
					break;
				case 'verlaengern': 
					$out .= $this->createLinkSingleVerlaengern($elem['uid']);
					break;
				case 'drucken': 
					$out .= $this->createLinkSingleDrucken($elem['uid']);
					break;
				case 'pdf': 
					$out .= $this->createLinkSinglePdf($elem['uid']);
					break;
			}
		}
		return $out;
	}
	
	function createLinkSingleAblehnen($uid) {
		$additionalParams = '&tx_femanagement[mode]=ablehnen';
		$iconClass = 'icon-actions ablehnen';
		$title = 'Antrag ablehnen';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
	function createLinkSingleBewilligen($uid) {
		$additionalParams = '&tx_femanagement[mode]=bewilligen';
		$iconClass = 'icon-actions bewilligen';
		$title = 'Antrag bewilligen';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
	function createLinkSingleVerbuchen($uid) {
		$additionalParams = '&tx_femanagement[mode]=verbuchen';
		$iconClass = 'icon-actions verbuchen';
		$title = 'Antrag verbuchen';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
	function createLinkSingleVerlaengern($uid) {
		$additionalParams = '&tx_femanagement[mode]=verlaengern';
		$iconClass = 'icon-actions verlaengern';
		$title = 'Antrag verlaengern';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
	function createLinkSingleDrucken($uid) {
		$additionalParams = '&tx_femanagement[mode]=drucken';
		$iconClass = 'icon-actions drucken';
		$title = 'Antrag ausdrucken';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
	function createLinkSinglePdf($uid) {
		$additionalParams = '&tx_femanagement[mode]=pdf';
		$iconClass = 'icon-actions pdf';
		$title = 'PDF erstellen';
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_antraege_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_antraege_list.php']);
}

?>
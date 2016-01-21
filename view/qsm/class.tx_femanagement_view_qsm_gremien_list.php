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
class tx_femanagement_view_qsm_gremien_list extends tx_femanagement_view_form_list {

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
			case 'title':
				$out .= '<td class="' . $key . '">';
				$out .= $elem[$key];
				$out .= '</td>';
				break;
			case 'kuerzel':
				$out .= '<td class="' . $key . '">';
				$out .= $elem[$key];
				$out .= '</td>';
				break;
			case 'mitglieder':
				$out .= '<td class="' . $key . '">';
				$mitgliederModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users');
				$mitgliederNamen = array();
				foreach ($elem[$key] as $mitglied) {
					$mitgliederNamen[] = $mitgliederModel->getFieldData($mitglied);
				}
				$out .= implode('<br />',$mitgliederNamen);
				$out .= '</td>';
				break;
			}
		}
		$out .= '<td class="actions">';
		$out .= $this->showActions($elem,$permissions);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
	
	function createDataList(&$dataList) {
		$gremienDaten = array();
		foreach ($dataList as $eintrag) {
			if (empty($gremienDaten[$eintrag['title']])) {
				$gremienDaten[$eintrag['title']] = $eintrag;
			}
		}
		$personenModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users');
		$out = '';
		foreach ($gremienDaten as $gremiumTitel=>$gremium) {
			$gremiumBearbeitungsLink = $this->createLinkSingleEdit($gremium['uid']);
			$out .= '<h1>' . $gremiumTitel . $gremiumBearbeitungsLink . '</h1>';
					$rowClass = 'even';
			if (count($gremium['admins'])>0) {
				$out .= '<h3>Gremien-Leitung</h3>';
				foreach ($gremium['admins'] as $mitgliedsDaten) {
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
					$gremienMitglied = $personenModel->getFieldData($mitgliedsDaten['username']);
					$out .= '<div class="' . $rowClass . '">' . $gremienMitglied . '</div>';
				}
			}
			$rowClass = 'even';
			if (count($gremium['mitglieder'])>0) {
				$out .= '<h3>Gremienmitglieder</h3>';
				foreach ($gremium['mitglieder'] as $mitgliedsDaten) {
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
					$gremienMitglied = $personenModel->getFieldData($mitgliedsDaten['username']);
					$out .= '<div class="' . $rowClass . '">' . $gremienMitglied . '</div>';
				}
			}
		}
		return $out;
	}

	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		
		$configArray = array();
		$configArray['sqlFilter'] = 'TRUE';
		$configArray['fields'] = 'uid,title,kuerzel,deleted,hidden';
		if (isset($this->args['suche'])) {
			$configArray['sqlFilter'] .= ' AND title LIKE "%' . $this->args['suche'] . '%"';
		}
		if (isset($this->args['bereich']) && $this->args['bereich']!='all') {
			$configArray['sqlFilter'] .= ' AND (kuerzel="' . $this->args['bereich'] . '")';
		}
		if (isset($this->args['hidden'])) {
			$configArray['hidden'] = $this->args['hidden'];
		}
		if (isset($this->args['deleted'])) {
			$configArray['deleted'] = $this->args['deleted'];
		}
		
		$out .= $this->exitAjaxFilter($configArray);
		$daten = $this->model->selectData($configArray);
		foreach($daten as $index=>$werte) {
			$admins = $this->model->gibMitglieder($werte['uid'],$data['pid'],'uid','admin');
			if (count($admins)>0) {
				$daten[$index]['admins'] = $admins;
			}
			$mitglieder = $this->model->gibMitglieder($werte['uid'],$data['pid'],'uid','mitglied');
			if (count($mitglieder)>0) {
				$daten[$index]['mitglieder'] = $mitglieder;
			}
		}
		$out .= $this->createDataList($daten);
		return $out;
	}

	function getNewelemButtonTitle() {
		return 'Neues Gremium anlegen'; 
	}

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_gremien_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_gremien_list.php']);
}

?>
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

class tx_femanagement_model_qsm_mittel	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_qsm_mittel');
	}

		function initFormFields() {
 		$this->formFields = array(
				'title' => 'title',
				'betrag' => 'betrag',
				'kostenstelle' => 'kostenstelle',
 				'antrag' => 'antrag',
 		);
	}
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

	function buildSelect(&$configArray) {
		$sqlQuery = $this->buildQuery($configArray);
		$select = $this->buildSqlSelect($sqlQuery);
		return $select;
	}

	function selectData($configArray) {
		$select = $this->buildSelect($configArray);
		return $this->fetchData($select);
	}
	
	
	function getFieldData($uid) {
		$configArray['fields'] = 'title,betrag,kostenstelle';
		$configArray['sqlFilter'] = 'antrag=' . $uid;
		$configArray['orderBy'] = 'sorting';		
		return $this->selectData($configArray);
	}

	function storeFieldData($antragsId,&$data) {
		/*
		 * Vorhandene Einträge löschen
		 */
		$whereDeleteMittel = 'antrag=' . $antragsId;
		$res = $this->delete($whereDeleteMittel,'tx_qsm_mittel');
		if (!$res) {
			if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Löschen der Mittel:', 'fe_managment', 0);
			return FALSE;
		}
		$sorting = 1;
		foreach($data as $eintrag) {
			if (!empty($eintrag['title']) && 
					!empty($eintrag['betrag']) && 
					!empty($eintrag['kostenstelle'])) {
				$saveData = array(
							'pid' => $this->storagePid,
							'crdate' => time(),
							'tstamp' => time(),
							'hidden' => '0',
							'deleted' => '0',
							'antrag' => $antragsId,
							'title' => $eintrag['title'],
							'betrag' => $eintrag['betrag'],
							'kostenstelle' => $eintrag['kostenstelle'],
							'sorting' => $sorting,
					);
				$sorting++;
				$res = $this->insert($saveData,'tx_qsm_mittel');
				if (!$res) {
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern der Mittel:', 'fe_managment', 0, $saveData);
					return FALSE;
				}
			}
		}
		return TRUE;
	}
}
?>

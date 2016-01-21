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

class tx_femanagement_model_qsm_budgets	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_qsm_budgets');
	}

		function initFormFields() {
 		$this->formFields = array(
 				'antrag' => 'antrag',
 				'zeitraum' => 'zeitraum',
				'budget' => 'budget',
 				'mode' => 'mode',
 				'version' => 'version',
 		);
	}
		
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

	function getFieldData($antrag,$mode) {
		$configArray['fields'] = 'zeitraum,budget,mode,version';
		$configArray['sqlFilter'] = 'antrag=' . $antrag . ' AND mode="' . $mode . '"';
		$configArray['orderBy'] = 'sorting';
		return parent::selectData($configArray);
	}

	function storeFieldData($antragsId,&$data) {
		/*
		 * Vorhandene Einträge löschen
		 */
		$mode = $data[0]['mode'];
		$version = $data[0]['version'];
		$whereDeleteMittel = 'antrag=' . $antragsId . ' AND mode="' . $mode . '"';
		$res = $this->delete($whereDeleteMittel,'tx_qsm_budgets');
		if (!$res) {
			if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Löschen der Mittel:', 'fe_managment', 0);
			return FALSE;
		}
		$sorting = 1;
		foreach($data as $eintrag) {
			if (!empty($eintrag['zeitraum']) && 
					!empty($eintrag['budget'])) {
				$saveData = array(
							'pid' => $this->storagePid,
							'crdate' => time(),
							'tstamp' => time(),
							'hidden' => '0',
							'deleted' => '0',
							'antrag' => $antragsId,
							'zeitraum' => $eintrag['zeitraum'],
							'budget' => $eintrag['budget'],
							'mode' => $mode,
							'version' => $version,
							'sorting' => $sorting,
					);
				$sorting++;
				$res = $this->insert($saveData,'tx_qsm_budgets');
				if (!$res) {
					$this->piBase->settings['debug'] = TRUE;
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern der Mittel:', 'fe_managment', 0, $saveData);
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	function gibAntragsBudgetSumme($antrag,$mode) {
		$budgetSumme = 0;
		$antragsVerantwortliche = array();
		$configArray['sqlFilter'] =  'antrag=' . $antrag . ' AND mode="' . $mode . '"';
		$configArray['fields'] = 'budget';
		$configArray['orderBy'] = 'sorting';
		$configArray['all_pids'] = TRUE;
		$configArray['hiddenFieldName'] = 'hidden';
		$data = $this->selectData($configArray);
		if (count($data)>0) {
			foreach ($data as $eintrag) {
				$budgetSumme += $eintrag['budget'];
			}
		}
		return $budgetSumme;
	}
	
	
}
?>

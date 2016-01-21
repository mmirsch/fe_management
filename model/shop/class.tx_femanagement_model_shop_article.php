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

class tx_femanagement_model_shop_article	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_hebest_artikel');
	}
	
	function initFormFields() {
 		$this->formFields = array(
			'produktname' => 'produktname',
			'artikelnummer' => 'artikelnummer',
			'anzeigen_bis' =>'anzeigen_bis',
			'ansprechpartner' => 'ansprechpartner',
			'bemerkung' => 'bemerkung',
			'interne_bemerkung' => 'interne_bemerkung',
			'link' => 'link',
			'linktext' => 'linktext',
			'bild' => 'bild',
			'preis' => 'preis',
 			'hersteller_bezeichnung' => 'hersteller_bezeichnung',
 			'hauptkategorie' => 'hauptkategorie',
 			'unterkategorie' => 'unterkategorie',
 			'eigenschaft1' => 'eigenschaft1',
 			'eigenschaft2' => 'eigenschaft2',
 			'hersteller' => 'hersteller',
 			'lieferant' => 'lieferant',
 				
		);
	}
	
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		return $formDataNew;
	}
		
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		return $uidNeu;
	}

	function selectData($configArray) {
		$data = parent::selectData($configArray);
		if (isset($configArray['externalData'])) {
			$externalFields = array();
			foreach ($configArray['externalData'] as $field=>$fieldData) {
				$model = t3lib_div::makeInstance($fieldData['model'],
																				$this->piBase,
																				$this->storagePid);
				$externalFields[$field] = array('model' => $model,
																				'field' => $fieldData['field']);
			}
			$dataNew = array();
			foreach ($data as $daten) {
				foreach ($externalFields as $field=>$externelData) {
					if (isset($daten[$field])) {
						$daten[$field] = $externelData['model']->selectField($daten[$field],$externelData['field']);
					}
				}
				$dataNew[] = $daten;
			}
			return $dataNew;
		} else {
			return $data;
		}
	}
	
	function gibFeldwert($id,$feld) {
		$configArray['all_pids'] = TRUE;
		return $this->selectField($id,$feld,$configArray);
	}
	
	function selectFieldData($id,$felder) {
		$configArray['show_hidden'] = TRUE;
		$configArray['show_deleted'] = TRUE;
		$configArray['all_pids'] = TRUE;
		return parent::selectFieldData($id,$felder,$configArray);
	}
	
/*
 * ########################## CONVERT DATA ##########################
 */	
	
	function cleanDataRead($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
			default: 
				$ergebnisDaten[$key] = $value;
			}
		}
		return $ergebnisDaten;
	}
	
	function cleanDataWrite($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
			default: 
				$ergebnisDaten[$key] = $value;
			}
		}
		return $ergebnisDaten;
	}
	
	
}
?>

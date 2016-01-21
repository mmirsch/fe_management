<?php
/***************************************************************
 *	Copyright notice
*
*	(c) Hochschule Esslingen
*	All rights reserved
*
*	This script is part of the TYPO3 project. The TYPO3 project is
*	free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*
*	The GNU General Public License can be found at
*	http://www.gnu.org/copyleft/gpl.html.
*
*	This script is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
*	GNU General Public License for more details.
*
*	This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_femanagement_model_promotionen extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_promotionen');
	}
	
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		return $formDataNew;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (empty($uid) || empty($dbData['fe_cruser_id'])) {
			$dbData['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		}

		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		return $uidNeu;
	}
	
	function initFormFields() {
 		$this->formFields = array(
			'pid' => 'pid',
 			'fe_cruser_id' => 'fe_cruser_id',
			'title' => 'title',
			'promovend_vorname' => 'promovend_vorname',
			'promovend_nachname' => 'promovend_nachname',
			'promovend_email' => 'promovend_email',
			'fakultaet' => 'fakultaet',
			'faku_link' => 'faku_link',
			'kooperations_uni' => 'kooperations_uni',
			'erst_betreuer' => 'erst_betreuer',
			'zweit_betreuer' => 'zweit_betreuer',
			'start_datum' => 'start_datum',
			'end_datum' => 'end_datum',
			'beschreibung_kurz' => 'beschreibung_kurz',
			'beschreibung_lang' => 'beschreibung_lang',
			'grafik' => 'grafik',
			'bildunterschrift' => 'bildunterschrift',
		);
	}
	
	function getFieldVal($uid,$field) {
		$configArray = array();
		$configArray['fields'] = $field;
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['show_hidden'] = TRUE;
		$configArray['show_deleted'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0][$field];
	}

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

	function geTitle($uid) {
		$configArray = array();
//		$configArray['fields'] = 'title,start_datum,end_datum';
		$configArray['fields'] = 'title';
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);

//		$startDatum = date('d.m.Y', $data[0]['start_datum']);
//		$endDatum = date('d.m.Y', $data[0]['end_datum']);
		$title = $data[0]['title'];
//		return $title . ' (' . $startDatum . ' - ' . $endDatum . ')';
		return $title;
	}

	function getTitleList($list) {
		$titles = array();
		foreach ($list as $uid=>$title) {
			$titles[$uid] = $this->geTitle($uid);
		}
		return $titles;
	}

	function getList() {
		$titles =  parent::getList('','all','','title');
		return $this->getTitleList($titles);
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/promotionen/class.tx_femanagement_model_promotionen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/promotionen/class.tx_femanagement_model_promotionen.php']);
}
?>

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

class tx_femanagement_model_modules_en extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_he_modules_en');
	}
	
	function initFormFields() {
 		$this->formFields = array(
 				'pid' => 'pid',
 				'cruser_id' => 'cruser_id',
				'title' => 'title',
 				'zusatz' => 'zusatz',
 				'campus' => 'campus',
 				'studiengang' => 'studiengang',
 				'fakultaet' => 'fakultaet',
 				'studiengang' => 'studiengang',
 				'verantwortliche' => 'verantwortliche',
 				'download' => 'download',
 				'link' => 'link',
 				'credits' => 'credits',
 				'level' => 'level',
 				'semester' => 'semester',
 		);
	}
		
	function insertDbEntry(&$formData,$hiddenFieldName='hidden',$hidden=0)  {
		return parent::insertDbEntry($formData,'hidden',$hidden);
	}
		
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		if (!empty($dbData['verantwortliche'])) {
			$formDataNew['verantwortliche'] =  unserialize($dbData['verantwortliche']);
		}
		if (!empty($dbData['studiengang'])) {
			$formDataNew['studiengang'] =  unserialize($dbData['studiengang']);
		}
		return $formDataNew;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (isset($formData['verantwortliche'])) {
			$verantwortlicheData = $formData['verantwortliche']->getValue();
			$dbData['verantwortliche'] = serialize($verantwortlicheData);
		} 
		if (isset($formData['studiengang'])) {
			$studiengangData = $formData['studiengang']->getValue();
			$dbData['studiengang'] = serialize($studiengangData);
			$dbData['fakultaet'] = $this->gibStudiengangFakultaet($studiengangData[0]['studiengang']);
			$dbData['campus'] = $this->gibFakultaetsStandort($dbData['fakultaet']);
		} 
		if (empty($uid) || empty($dbData['cruser_id'])) {
			$dbData['cruser_id'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		}
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		return $uidNeu;
	}
	
	/*
	 * ########################## LISTS ##########################
	*/
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}
	
	function getFieldData($uid) {
		$configArray['fields'] = 'title,title_en';
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$configArray['orderBy'] = 'sorting';
		return parent::selectData($configArray);
	}
	
	function getSelectList($pid) {
		$configArray['pid'] = $pid;
		$configArray['fields'] = 'uid,title,title_en';
		$configArray['orderBy'] = 'title';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem['title_en'];
		}
		return $result;
	}
	
	function getTitle($uid, $lang='en') {
		$configArray = array();
		if ($lang=='de') {
			$configArray['fields'] = 'title';
		} else {
			$configArray['fields'] = 'title_en';
		}
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['title'];
	}
	
	function gibStandortListe($lang='en',$orderBy='') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = 'uid,' . $titleField;
		if (empty($orderBy)) {
			$configArray['orderBy'] = $titleField;
		} else {
			$configArray['orderBy'] = $orderBy;
		}
		
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_standorte';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem[$titleField];
		}
		return $result;
	}
	
	function gibStandortTitel($uid, $lang='de') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = $titleField;
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_standorte';
		$data = parent::selectData($configArray);
		return $data[0][$titleField];
	}
	
	function gibFakultaetsListe($lang='en') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = 'uid,' . $titleField;
		$configArray['orderBy'] = $titleField;
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_fakultaeten';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem[$titleField];
		}
		return $result;
	}
	
	function gibFakultaetsTitel($uid, $lang='de') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = $titleField;
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_fakultaeten';
		$data = parent::selectData($configArray);
		return $data[0][$titleField];
	}
	
	function gibFakultaetsKuerzel($uid) {
		$configArray['fields'] = 'kuerzel';
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_fakultaeten';
		$data = parent::selectData($configArray);
		return $data[0]['kuerzel'];
	}
	
	function gibFakultaetsStandort($uid) {
		$configArray['fields'] = 'standort';
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_fakultaeten';
		$data = parent::selectData($configArray);
		return $data[0]['standort'];
	}

	function gibStudiengangListe($lang='en') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = 'uid,' . $titleField;
		$configArray['orderBy'] = $titleField;
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_studiengaenge';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem[$titleField];
		}
		return $result;
	}
	
	function gibStudiengangTitel($uid, $lang='de') {
		if ($lang=='de') {
			$titleField = 'title';
		} else {
			$titleField = 'title_en';
		}
		$configArray['fields'] = $titleField;
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_studiengaenge';
		$data = parent::selectData($configArray);
		return $data[0][$titleField];
	}

	function gibStudiengangFakultaet($uid) {
		$configArray['fields'] = 'fakultaet';
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_studiengaenge';
		$data = parent::selectData($configArray);
		return $data[0]['fakultaet'];
	}

	function gibStudiengangKuerzel($uid) {
		$configArray['fields'] = 'kuerzel';
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = 'tx_he_studiengaenge';
		$data = parent::selectData($configArray);
		return $data[0]['kuerzel'];
	}

	function isOwner($uid,$userId) {
		$configArray['all_pids'] = TRUE;
		$configArray['show_hidden'] = TRUE;
		$dbUserId = $this->selectField($uid,'cruser_id',$configArray);
		return $dbUserId==$userId;
	}
	
	function gibSemesterListe() {
		return array(
				'1' => 'Nur Sommersemester',
				'2' => 'Nur Wintersemester',
				'3' => 'Im Sommer- und Wintersemester',
		);
	}
	
	function gibSemesterTitel($semester) {
		$semesterListe = $this->gibSemesterListe();
		return $semesterListe[$semester];
	}
	
	function gibLevelListe() {
		return array(
				'1'=>'Bachelor Level A (1.-2. Sem.)',
				'2'=>'Bachelor Level B (3.-7. Sem.)',
				'3'=>'Bachelor Level A und B (1.-7. Sem.)',
		);
	}
	
	function gibLevelTitel($level) {
		$levelListe = $this->gibLevelListe();
		return $levelListe[$level];
	}
	
	function gibFakultaetenMitModulen($campus,$lang='de') {
		$configArray['fields'] = 'fakultaet';
		$configArray['sqlFilter'] = 'campus=' . $campus ;
		$configArray['all_pids'] = TRUE;
		$configArray['orderBy'] = 'uid';
		$configArray['table'] = 'tx_he_modules_en';
		$list = parent::selectData($configArray);
		$fakultaetsListe = array();
		foreach($list as $elem) {
			if (!in_array($elem['fakultaet'],$fakultaetsListe)) {
				$fakultaetsListe[] = $elem['fakultaet'];
			}
		}
		$fakultaeten = array();
		foreach ($fakultaetsListe as $fakultaet) {
			$fakultaeten[$fakultaet] = $this->gibFakultaetsTitel($fakultaet,$lang);
		}
		return $fakultaeten;
	}

	function gibFakultaetsModule($fakultaet) {
		$configArray['fields'] = '*';
		$configArray['sqlFilter'] = 'fakultaet=' . $fakultaet ;
		$configArray['all_pids'] = TRUE;
		$configArray['orderBy'] = 'title';
		$configArray['table'] = 'tx_he_modules_en';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem;
		}
		return $result;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/modules_en/class.tx_femanagement_model_modules_en_campus.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/modules_en/class.tx_femanagement_model_modules_en_campus.php']);
}
?>

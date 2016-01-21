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

class tx_femanagement_model_forschung extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_forschungsprojekte');
	}
	
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		if (!empty($dbData['wiss_leitung_alt'])) {
			$formDataNew['wiss_leitung_alt'] =  unserialize($dbData['wiss_leitung_alt']);
		}
		return $formDataNew;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (isset($formData['wiss_leitung_alt'])) {
			$wissLeitungData = $formData['wiss_leitung_alt']->getValue();
			$dbData['wiss_leitung_alt'] = serialize($wissLeitungData);
		} 
		if (isset($formData['foerdersumme'])) {
			$foerdersumme = $formData['foerdersumme']->getValue();
			/* Alle Zeichen, die keine Ziffer sind entfernen */
			$zahl = str_ireplace(",-","",$foerdersumme);
			$zahl = preg_replace("/\./","",$zahl);
			$zahl = str_ireplace(",",".",$zahl);
			$foerdersummeClean = round($zahl,2);
			$dbData['foerdersumme'] = $foerdersummeClean;
		} 
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
 			'leitende_einrichtung' => 'leitende_einrichtung',
 			'projektnummer' => 'projektnummer',
      'foerderkennzeichen' => 'foerderkennzeichen',
 			'fakultaet' => 'fakultaet',
 			'faku_link' => 'faku_link',
 			'foerderung_wer' => 'foerderung_wer',
 			'foerdersumme' => 'foerdersumme',
			'kooperationspartner' => 'kooperationspartner',
			'start_datum' => 'start_datum',
			'end_datum' => 'end_datum',
			'wiss_leitung' => 'wiss_leitung',
			'wiss_leitung_alt' => 'wiss_leitung_alt',
			'wiss_mitarbeiter' => 'wiss_mitarbeiter',			
			'webseite' => 'webseite',
			'beschreibung_kurz' => 'beschreibung_kurz',
			'beschreibung_lang' => 'beschreibung_lang',
 			'downloads' => 'downloads',
			'downloads_beschriftung' => 'downloads_beschriftung',
 			'medien1' => 'medien1',
	 		'bildunterschrift1' => 'bildunterschrift1',
 			'medien2' => 'medien2',
			'bildunterschrift2' => 'bildunterschrift2',
 			'veroeff_title' => 'veroeff_title',
			'veroeff_link' => 'veroeff_link',
			'diss' => 'diss',
			'anzahl_stud' => 'anzahl_stud',
      'nachhaltigkeitsbezug_oekologisch' => 'nachhaltigkeitsbezug_oekologisch',
      'nachhaltigkeitsbezug_oekonomisch' => 'nachhaltigkeitsbezug_oekonomisch',
      'nachhaltigkeitsbezug_sozial' => 'nachhaltigkeitsbezug_sozial',
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
			case 'foerdersumme':
				$ergebnisDaten[$key] = number_format($value, 2, ',', '.');
				break;
			default: 
				$ergebnisDaten[$key] = $value;
			}
		}
		return $ergebnisDaten;
	}
	
  function istWissenschaftlicherLeiter($uid, $userId) {
    $result = FALSE;
    $configArray = array();
    $configArray['table'] = 'fe_users';
    $configArray['fields'] = 'email';
    $configArray['sqlFilter'] = 'uid=' . $userId;
    $configArray['all_pids'] = TRUE;
    $configArray['hiddenFieldName'] = 'disable';
    $userData = parent::selectData($configArray);
    if (isset($userData[0])) {
      if (isset($userData[0]['email'])) {
        $userEmail = strtolower($userData[0]['email']);
        $wissenschaftlicheLeiter = unserialize($this->getFieldVal($uid,'wiss_leitung'));
        $leiterEmails = array();
        if (is_array($wissenschaftlicheLeiter) && count($wissenschaftlicheLeiter)>0) {
          foreach($wissenschaftlicheLeiter as $eintrag) {
            $leiterEmails[] = strtolower($eintrag['email']);
          }
          $result = in_array($userEmail,$leiterEmails);
        }
      }
    }
    return $result;
  }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/forschung/class.tx_femanagement_model_forschung.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/forschung/class.tx_femanagement_model_forschung.php']);
}
?>

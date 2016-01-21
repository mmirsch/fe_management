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

class tx_femanagement_model_qsm_antraege extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_qsm_antraege');
	}

	function initFormFields() {
 		$this->formFields = array(
			'title' => 'title',
			'short_title' => 'short_title',
			'ziel' => 'ziel',
 				
			'antragsteller' => 'antragsteller',
			'bereich' => 'bereich',
			'einrichtung' => 'einrichtung',
			'start' => 'start',
			'ende' => 'ende',
 			'bezugssemester' => 'bezugssemester',
 				
			'entscheidung' => 'entscheidung',
 				
			'anmerkungen' => 'anmerkungen',
			'persstellen' => 'persstellen',
			
			'zustudgeb' => 'zustudgeb',
			'zupersstell' => 'zupersstell',
			'zuzentauf' => 'zuzentauf',
			'zustudpro' => 'zustudpro',
 			
 			'status' => 'status',
			'masnanr' => 'masnanr',
			'fina_bereich1' => 'fina_bereich1',
			'fina_bereich2' => 'fina_bereich2',
			'kommentar' => 'kommentar',
 		);
	}
	
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		/*
		 * Mittel behandeln
		 */
		if (!empty($dbData['uid'])) {
			$modelMittel = t3lib_div::makeInstance('tx_femanagement_model_qsm_mittel',
																			 $this->piBase,
																			 $this->storagePid);
			$formDataNew['mittel'] = $modelMittel->getFieldData($dbData['uid']);
			$modelBudgets = t3lib_div::makeInstance('tx_femanagement_model_qsm_budgets',
																			 $this->piBase,
																			 $this->storagePid);
			$formDataNew['beanbudget'] = $modelBudgets->getFieldData($dbData['uid'],'beanbudget');
			$formDataNew['bewbudget'] = $modelBudgets->getFieldData($dbData['uid'],'bewbudget');
			$verantwortliche = $this->gibVerantwortliche($dbData['uid']);
			$formDataNew['verantw'] = $verantwortliche;
		}
		return $formDataNew;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		/*
		 * Mittel behandeln
		 */
		if (isset($formData['mittel'])) {
			$mittelData = $formData['mittel']->getValue();
		}
		if (isset($formData['beanbudget'])) {
			$beanBudgets = $formData['beanbudget']->getValue();
		}
		if (isset($formData['bewbudget'])) {
			$bewBudgets = $formData['bewbudget']->getValue();
		}
		if (isset($formData['verantw'])) {
			$verantwData = $formData['verantw']->getValue();
		}
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		if (is_array($mittelData) && $uidNeu) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_qsm_mittel',
																			 $this->piBase,
																			 $this->storagePid);
			$res = $model->storeFieldData($uidNeu,$mittelData);
		}
		if (uidNeu && (is_array($beanBudgets))) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_qsm_budgets',
																			 $this->piBase,
																			 $this->storagePid);
			$res = $model->storeFieldData($uidNeu,$beanBudgets);
		}
		if (uidNeu && (is_array($bewBudgets))) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_qsm_budgets',
																			 $this->piBase,
																			 $this->storagePid);
			$res = $model->storeFieldData($uidNeu,$bewBudgets);
		}
		if (is_array($verantwData) && $uidNeu) {
			$res = $this->speichereVerantwortliche($uidNeu,$verantwData);
		}
		return $res;
	}

	function speichereVerantwortliche($antragsId,&$data) {
		/*
		 * Vorhandene Einträge löschen
		 */
		$whereDelete = 'antrag=' . $antragsId;
		$res = $this->delete($whereDelete,'tx_qsm_antraege_verantwortliche');
		if (!$res) {
			if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Löschen der Mittel:', 'fe_managment', 0);
			return FALSE;
		}
		$sorting = 1;
		foreach($data as $eintrag) {
			if (!empty($eintrag['value']) && 
					!empty($eintrag['valueSelect'])) {
				$saveData = array(
							'pid' => $this->storagePid,
							'crdate' => time(),
							'tstamp' => time(),
							'hidden' => '0',
							'deleted' => '0',
							'antrag' => $antragsId,
							'username' => $eintrag['value'],
							'name' => $eintrag['valueSelect'],
							'sorting' => $sorting,
					);
				$sorting++;
				$res = $this->insert($saveData,'tx_qsm_antraege_verantwortliche');
				if (!$res) {
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern der Verantwortlichen:', 'fe_managment', 0, $saveData);
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	function gibVerantwortliche($antragsId) {
		$configArray['table'] = 'tx_qsm_antraege_verantwortliche';
		$configArray['fields'] = 'username,name';
		$configArray['sqlFilter'] = 'antrag=' . $antragsId;
		$configArray['orderBy'] = 'sorting';		
		$verantwortliche = parent::selectData($configArray);
		$verantwortlichenListe = array();
		if (is_array($verantwortliche) && !empty($verantwortliche)) {
			foreach ($verantwortliche as $eintrag) {
				$verantwortlichenListe[] = array('value'=>$eintrag['username'],'valueSelect'=>$eintrag['name']);
			}
		}
		return $verantwortlichenListe;
	}
	
	public static function gibAntragsStatus($status) {
		$antragsStatus = '';
		switch ($status) {
			case '1':
				$antragsStatus = "Entwurf";
				break;
			case '2':
				$antragsStatus = "beantragt";
				break;
			case '3':
				$antragsStatus = "genehmigt";
				break;
			case '4':
				$antragsStatus = "abgelehnt";
				break;
			case '5':
				$antragsStatus = "zurückgestellt";
				break;
			case '6':
				$antragsStatus = "in Bearbeitung (FINA)";
				break;
			case '7':
				$antragsStatus = "verbucht";
				break;
			case '8':
				$antragsStatus = "Maßnahme beendet";
				break;
		}
		return $antragsStatus;
	}
	
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}
	
	public static function getListAntragsStatus() {
		return tx_femanagement_controller_qsm_main::$antragsStati;
	}
	
	public static function getListFina1() {
		return array(1=>'Studiengebühren der Fakultät (Bereich 1)',
								 2=>'Personalstellen der Fakultät (Bereich 2)',
								 3=>'Zentrale Aufgaben der Fakultät (Bereich 3)',
								 4=>'Studentische Projekte (Bereich 4)',
				);
	}
	
	public static function getListFina2() {
		return  array (
				'1' => 'Zusätzliches Lehrpersonal',
				'11' => 'Professorenstellen',
				'12' => 'befristetes wissenschaftliches Personal',
				'13' => 'unbefristetes wissenschaftliches Personal',
				'14' => 'studentische / wissenschaftliche Hilfskräfte ',
				'19' => 'Lehrbeauftragte',
				'2' => 'Bibliothek',
				'3' => 'Lehrbezogene technische Ausstattung (auch EDV)',
				'4' => 'Beratung',
				'5' => 'Internationales / Auslandsamt',
				'6' => 'Studium Generale, Kurse f. Schlüsselqualifikationen/Fremdsprachen',
				'7' => 'Qualitätssicherung/Evaluation, Hochschuldidaktik',
				'8' => 'Sonstiges',
				'9' => 'Verwaltung der Studiengebühren',
		);
	}
	
	function getAdditionalCondition($page) {
		$username = $GLOBALS['TSFE']->fe_user->user['username'];
		$additionalCondition = '';
		switch($page) {
		case 'meineAntraege':
			$additionalCondition = ' (' . 
															'tx_qsm_antraege.antragsteller="' . $username . '"' . 
															')';
			break;
		case 'gremienAntraege':
			$additionalCondition = ' (' . 
					' ("' . $username . '" IN (' .
					' 	SELECT username FROM tx_qsm_gremien_personen' .
					' 	INNER JOIN tx_qsm_gremien ON tx_qsm_gremien_personen.gremium= tx_qsm_gremien.uid' .
					' 	where tx_qsm_gremien.kuerzel = tx_qsm_antraege.bereich' .
					'	)) AND (' . 
					'	tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_eingereicht . 
					' )' . 
					')';
			$additionalCondition = ' (' . 
															'tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_eingereicht . 
															')';
			//			$additionalCondition = ' TRUE ';
			break;
		case 'finaAntraege':
			$additionalCondition = ' (' . 
															'tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_bew_gremium . 
															' OR tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_bearb_fina . 
															')';
			break;
		case 'alleAntraege':
			break;
		case 'verwendung':
		default: 
			$additionalCondition = ' (' . 
															'tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_bew_fina . 
															' OR tx_qsm_antraege.status=' . tx_femanagement_controller_qsm_main::$status_beendet .
															')';
			break;
		}
		return $additionalCondition;
	}
				
	function createDataListConfig(&$args,$page,&$configArray) {
		$configArray = array();
		$configArray['joins'] = array(
				array('table'=>'fe_users',
						'fields'=>'name as antragsteller_name,tx_hepersonen_profilseite as antragsteller_profilseite,last_name',
						'joinFieldLocal'=>'username',
						'joinFieldMain'=>'antragsteller',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'tx_qsm_gremien',
						'fields'=>'title as bereichs_titel',
						'joinFieldLocal'=>'kuerzel',
						'joinFieldMain'=>'bereich',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'tx_qsm_einrichtungen',
						'fields'=>'title as einrichtungs_titel',
						'joinFieldLocal'=>'kuerzel',
						'joinFieldMain'=>'einrichtung',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'tx_qsm_zeitraeume',
						'fields'=>'title as bezugssemester',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'bezugssemester',
						'mode'=>'LEFT JOIN',
				),
		);
		$configArray['where'] = ' WHERE ';
		$additionalCondition = $this->getAdditionalCondition($page);
		if (empty($additionalCondition)) {
			$configArray['where'] .= ' TRUE';
		} else {
			$configArray['where'] .= $additionalCondition;
		}
		if (isset($args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tx_qsm_antraege.title LIKE "%' . $args['volltextsuche'] . '%"' . 
															 ' OR tx_qsm_antraege.masnanr LIKE "%' . $args['volltextsuche'] . '%")';
		}
		if (!empty($args['personensuche'])) {
			$configArray['where'] .= ' AND fe_users.name LIKE "%' . $args['personensuche'] . '%"';
		}
		if (!empty($args['bereich'])) {
			if ($args['bereich']!='all') {
				$configArray['where'] .= ' AND tx_qsm_antraege.bereich="' . $args['bereich'] . '"';
			}
		}
		if (!empty($args['bezugssemester'])) {
			if ($args['bezugssemester']!='all') {
				$configArray['where'] .= ' AND tx_qsm_antraege.bezugssemester="' . $args['bezugssemester'] . '"';
			}
		}
		if (isset($args['hidden'])) {
			$configArray['hidden'] = $args['hidden'];
		}
		if (isset($args['deleted'])) {
			$configArray['deleted'] = $args['deleted'];
		}
		if (isset($args['status'])) {
			if ($args['status']!='all') {
				$configArray['where'] .= ' AND tx_qsm_antraege.status=' . $args['status'];
			}
		}
		if (isset($args['dateStart'])) {
			if (!empty($args['dateStart'])) {
				$date = explode('.',$args['dateStart']);
				$timestamp = mktime(0,0,0,$date[1],$date[0],$date[2]);
				$configArray['where'] .= ' AND tx_qsm_antraege.start>=' . $timestamp;
			}
		}
		if (isset($args['dateEnd'])) {
			if (!empty($args['dateEnd'])) {
				$date = explode('.',$args['dateEnd']);
				$timestamp = mktime(0,0,0,$date[1],$date[0],$date[2]);
				$configArray['where'] .= ' AND tx_qsm_antraege.start<=' . $timestamp;
			}
		}
		/*
		 * Sortierung prüfen
		*/
		if (!empty($args['sortField'])) {
			if (!empty($args['sortMode'])) {
				$sortMode = $args['sortMode'];
			} else {
				$sortMode = 'ASC';
			}
			switch ($args['sortField']) {
				case 'title':
					$sortField = 'tx_qsm_antraege.title';
					break;
				case 'bereich':
					$sortField = 'tx_qsm_antraege.bereich';
					break;
				case 'start':
					$sortField = 'tx_qsm_antraege.start';
					break;
				case 'ende':
					$sortField = 'tx_qsm_antraege.ende';
					break;
				case 'semester':
					$sortField = 'tx_qsm_zeitraeume.start';
					break;
				case 'antragsteller':
					$sortField = 'fe_users.last_name';
					break;
				default:
					$sortField = 'tx_qsm_antraege.' . $args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		}
	}
	
}
?>

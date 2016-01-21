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
class tx_femanagement_controller_qsm_antraege_verwendung extends tx_femanagement_controller_qsm_antraege {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Antrag',
																							'single_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege_verwendung');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_verwendung_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',$this->piBase,$this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Antrag',
																							'list_left verwendung',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege_verwendung');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function getFieldSettings(&$fieldSettings,$mode) {
		parent::getFieldSettings($fieldSettings,$mode);
	}
		
	function createFormSingle(&$formData,&$parameter,$mode) {		
		$personenFelder = array(
													 'bereich','einrichtung','bezugssemester',
													 'antragsteller','verantw',
											);				
		$antragsFelder = array(
													 'title','short_title','ziel',
													 'begruendung','anlage',
		);
		$datumsFelder = array('start','ende','entscheidung');	
		$budgetMittelFelder = array('beanbudget','bewbudget','anmerkungen','persstellen','mittel',);	
		$bereichsFelder = array('fina_bereich1','fina_bereich2',);
		
		$personenDatenContainer = $this->createContainer($personenFelder,$formData);
		$antragsDatenContainer = $this->createContainer($antragsFelder,$formData);
		$datumsContainer = $this->createContainer($datumsFelder,$formData);
		$budgetMittelContainer = $this->createContainer($budgetMittelFelder,$formData);
		$bereichsContainer = $this->createContainer($bereichsFelder,$formData);
		
		$containerList = array($personenDatenContainer,
													 $antragsDatenContainer,
													 $datumsContainer,
													 $budgetMittelContainer,
													 $bereichsContainer,
					);
		$this->formView->addFieldset($containerList);		#Create new Fieldset with all container
		$this->formView->addFormSingleButtons(array('speichern'=>'Maßnahme speichern'));
	}

	function showListView($aktuelleSeite) {
		if (empty($aktuelleSeite)) {
			$aktuelleSeite = $this->defaultPage;
		}
		parent::initMenu($aktuelleSeite);
		$filterListe = array();
		$buttonListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
//		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichData = $bereicheModel->getSelectList($this->getPid());
		$filterListe[20] = $this->formView->createFilter('select','bereich','Bereich',$sessionDaten,$bereichData,0,FALSE,'bereich');
		
		/* Zeitraumliste erzeugen */
		$zeitraeumeModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_zeitraeume');
		$alleZeitraeume = $zeitraeumeModel->getSelectList($this->getPid());
		$zeitraumListe = array();
		foreach ($alleZeitraeume as $uid=>$zeitraum) {
			$zeitraumListe[$uid] = $zeitraum;
		}
// Zeitraum des vergangenen Semesters anzeigen (aktuelle Zeit minus 180 Tage)		
		$aktuellesSemester = $zeitraeumeModel->gibAktuellenZeitraum($this->getPid(),180);
		$sessionDaten['bezugssemester'] = $aktuellesSemester;
/*		
		if (!isset($sessionDaten['bezugssemester'])) {
			$aktuellesSemester = $zeitraeumeModel->gibAktuellenZeitraum($this->getPid());
			if (!empty($aktuellesSemester)) {
			}
		}
*/		
		$options = array('hideOptionSelectAll'=>TRUE);
		$filterListe[30] = $this->formView->createFilter('select','bezugssemester','Bezugssemester',$sessionDaten,$zeitraumListe,0,FALSE,'bezugssemester',$options);
//		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
//		$filterListe[21] = $this->formView->createFilter('date','dateStart','von',$sessionDaten,'','',TRUE);
//		$filterListe[22] = $this->formView->createFilter('date','dateEnd','bis',$sessionDaten,'','',TRUE);
//		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
/*		
		if ($this->isAdmin()) {
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterListe[23] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[24] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
			$filterListe[31] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
			$filterListe[32] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		} else if ($this->isEditor()) {
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
			$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		}		
*/
		$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
		$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function getListViewFields() {
			return array('title'=>'Maßnahme',
					'verantwortliche'=>'Verantwortliche / Verantwortlicher',
					'bereich'=>'Bereich',
					'bezugssemester'=>'Semester',
					'bewbudget'=>'Budget',
					'persstellen'=>'Anzahl Stellen',
			);
	}

	function createAjaxData(&$view,&$data) {
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',
				'',$data['pid']);
		if (isset($data['tx_femanagement']['page'])) {
			$page = $data['tx_femanagement']['page'];
		} else {
			$page = '';
		}
		$configArray = array();
		$this->model->createDataListConfig($data['args'],$page,$configArray);
		if (!empty($data['args']['export'])) {
			$configArray['fields'] = 'uid,status,titleshort_title,,masnanr,ziel,start,ende,bereich,einrichtung';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$fieldList = explode(',','uid,status,title,short_title,masnanr,ziel,start,ende,bereichs_titel,einrichtungs_titel,antragsteller_name');
			$view->createDataExport($daten,$data['args']['export'],'QSM-Anträge',$fieldList);
			exit();
		} else {
			$configArray['fields'] = 'uid,status,title,masnanr,start,ende,bereich,einrichtung,antragsteller,persstellen,deleted,hidden';
			$configArray['orderBy'] = 'tx_qsm_antraege.bereich';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
/*
			$out .= $this->exitSqlAjaxFilter($view,$data['args'],$sqlQuery,$limit);
			if (!empty($limit)) {
				$sqlQuery .= $limit;
			}
*/			
			$daten = $this->model->selectSqlData($sqlQuery);
/*
			foreach ($daten as $index=>$elem) {
				$daten[$index]['permissions'] = $this->getPermissions($elem,$page,$this->model);
			}
*/			
			// Keine Aktionen anzeigen
			$out .= $view->createDataList($daten);
			return $out;
		}
	}
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/antraege/class.tx_femanagement_controller_qsm_antraege_verwendung.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/antraege/class.tx_femanagement_controller_qsm_antraege_verwendung.php']);
}

?>
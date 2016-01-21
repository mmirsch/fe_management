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
class tx_femanagement_controller_qsm_antraege_gremien extends tx_femanagement_controller_qsm_antraege {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView($title='Antrag') {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							$title,
																							'single_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege_gremien');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',$this->piBase,$this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Antrag',
																							'list_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege_gremien');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function getFieldSettings(&$fieldSettings,$mode) {

		$mainFieldSettings = array();
		parent::getFieldSettings($mainFieldSettings,$mode);
		$hiddenFields = explode(',','status,uid');
		$showFields = explode(',','masnanr,title,short_title,ziel,start,ende,entscheidung,beanbudget,bewbudget,anmerkungen,persstellen,mittel');
		$editFields = explode(',','bewbudget,anmerkungen');
		foreach($showFields as $field) {
			if (isset($mainFieldSettings[$field])) {
				if (!in_array($field,$editFields)) {
					$mainFieldSettings[$field]['viewonly'] = true;
				}
				$fieldSettings[$field] = $mainFieldSettings[$field];
			}
		}		
		foreach($hiddenFields as $field) {
			if (isset($mainFieldSettings[$field])) {
				$fieldSettings[$field]['type'] = 'hidden';
			}
		}
		$fieldSettings['anmerkungen']['title'] = 'Anmerkungen zum Bewilligten Budget';
		$fieldSettings['kommentar'] = array(
				'type'=>'text',
		);
		if ($mode=='ablehnen') {
			$fieldSettings['kommentar']['title'] = 'Anmerkungen zur Ablehnung';
		} else if ($mode=='bewilligen') {
			$fieldSettings['kommentar']['title'] = 'Anmerkungen zur Bewilligung';
		} else {
			$fieldSettings['kommentar']['title'] = 'Anmerkungen';
		}
	}
		
	function createFormSingle(&$formData,&$parameter,$mode) {		
		$personenFelder = array(
													 'bereich','einrichtung','bezugssemester',
													 'antragsteller','verantw',
											);				
		$antragsFelder = array(
													 'status','title','short_title',	
		);
		$datumsFelder = array('start','ende','entscheidung');	
		$budgetMittelFelder = array('beanbudget','bewbudget','anmerkungen','kommentar',);	
//		$bereichsFelder = array('fina_bereich1','fina_bereich2',);
		
//		$personenDatenContainer = $this->createContainer($personenFelder,$formData);
		$antragsDatenContainer = $this->createContainer($antragsFelder,$formData);
		$datumsContainer = $this->createContainer($datumsFelder,$formData);
		$budgetMittelContainer = $this->createContainer($budgetMittelFelder,$formData);
//		$bereichsContainer = $this->createContainer($bereichsFelder,$formData);
		
		$containerList = array(
													 $antragsDatenContainer,
													 $datumsContainer,
													 $budgetMittelContainer,
					);
		$this->formView->addFieldset($containerList);		#Create new Fieldset with all container
		if ($this->editMode=='ablehnen') {
			$this->formView->addFormSingleButtons(array('ablehnen'=>'Antrag ablehnen'));
		} else if ($this->editMode=='bewilligen') {
			$this->formView->addFormSingleButtons(array('bewilligen'=>'Antrag bewilligen'));
		} else {
			$this->formView->addFormSingleButtons(array('speichern'=>'Änderung speichern'));
		}
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
		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichData = $bereicheModel->getSelectList($this->getPid());
		$filterListe[20] = $this->formView->createFilter('select','bereich','Bereich',$sessionDaten,$bereichData);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[21] = $this->formView->createFilter('date','dateStart','von',$sessionDaten,'','',TRUE);
		$filterListe[22] = $this->formView->createFilter('date','dateEnd','bis',$sessionDaten,'','',TRUE);
		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		
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
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function getListViewFields() {
		return array('title'=>'Titel',
				'antragsteller'=>'Antragsteller',
				'bereich'=>'Bereich',
				'bezugssemester'=>'Semester',
				'masnanr'=>'M-Nr.',
				'status'=>'Status',
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
			$configArray['fields'] = 'uid,status,title,masnanr,start,ende,bereich,einrichtung,antragsteller,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
			$out .= $this->exitSqlAjaxFilter($view,$data['args'],$sqlQuery,$limit);
	
			if (!empty($limit)) {
				$sqlQuery .= $limit;
			}
			$daten = $this->model->selectSqlData($sqlQuery);
			foreach ($daten as $index=>$elem) {
				$daten[$index]['permissions'] = $this->getPermissions($elem,$page,$this->model);
			}
			$out .= $view->createDataList($daten);
			return $out;
		}
	}
	
	function handle_ablehnen(&$parameter,$post,$uid,$aktuelleSeite='') {
		$this->editMode = 'ablehnen';
		$this->initSingleView('Antrag ablehnen');
		if (isset($parameter['abort'])) {
			return $this->specialRedirect('abort');
		}
		if (isset($parameter['saved'])) {
			return $this->specialRedirect('formSaved');
		}
		$formData = array();
		$this->initFormSingle($formData,'ablehnen');
		if (!$post) {
			$parameter = $this->getFormDataDbSingle($formData,$uid);
		}
		$parameter['uid'] = $uid;
		$this->initFormData($formData,$parameter,$post);
		$permissions = $this->getPermissions($parameter,$aktuelleSeite);
		if (!in_array('ablehnen',$permissions)) {
			return 'Kein Zugriff (ablehnen)';
		}
		if (isset($parameter['save'])) {
			return $this->saveForm($formData,$parameter,$uid,'','edit');
		} else if (isset($parameter['ablehnen'])) {
			return $this->saveForm($formData,$parameter,$uid,'','ablehnen');
		} else {
			return $this->showDataFormSingle($formData,$parameter,'edit',$aktuelleSeite);
		}
	}
	
	function handle_bewilligen(&$parameter,$post,$uid,$aktuelleSeite='') {
		$this->editMode = 'bewilligen';
		$this->initSingleView('Antrag bewilligen');
		if (isset($parameter['abort'])) {
			return $this->specialRedirect('abort');
		}
		if (isset($parameter['saved'])) {
			return $this->specialRedirect('formSaved');
		}
		$formData = array();
		$this->initFormSingle($formData,'bewilligen');
		if (!$post) {
			$parameter = $this->getFormDataDbSingle($formData,$uid);
		}
		
		$this->initFormData($formData,$parameter,$post);
		$permissions = $this->getPermissions($parameter,$aktuelleSeite);
		if (!in_array('bewilligen',$permissions)) {
			return 'Kein Zugriff (bewilligen)';
		}		
		if (isset($parameter['save'])) {
			return $this->saveForm($formData,$parameter,$uid,'','edit');
		} else if (isset($parameter['bewilligen'])) {
			return $this->saveForm($formData,$parameter,$uid,'','bewilligen');
		} else{
			return $this->showDataFormSingle($formData,$parameter,'edit',$aktuelleSeite);
		}
	}
	
	function handleStatus(&$formData,&$parameter,$uid,$hidden,$mode) {
		switch($mode) {
			case 'bewilligen':
				if ($parameter['status']==self::$status_eingereicht) {
					$parameter['status'] = self::$status_bew_gremium;
				}
				break;
			case 'ablehnen':
				if ($parameter['status']==self::$status_eingereicht) {
					$parameter['status'] = self::$status_abgelehnt;
				}
				break;
		}
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/antraege/class.tx_femanagement_controller_qsm_antraege_gremien.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/antraege/class.tx_femanagement_controller_qsm_antraege_gremien.php']);
}

?>
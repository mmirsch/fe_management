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
class tx_femanagement_controller_qsm_gremien extends tx_femanagement_controller_qsm_main {
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {
		$viewClassName = 'tx_femanagement_view_qsm_gremien_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Gremium',
																							'single',
																							$this->eidViewHandler);
		
		$this->formView->setControllerName('tx_femanagement_controller_qsm_gremien');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_gremien');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_qsm_gremien_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Gremien',
																							'list_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_gremien');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_gremien');																		
	}
	
	function initFormSingle(&$formData,$mode='new') {		
		$fieldSettings['kuerzel'] = array(
												'title'=>'Kürzel des Gremiums',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['title'] = array(
												'title'=>'Bezeichnung des Gremiums',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['admins'] = array(
												'title'=>'Gremien-Leitung',
												'type'=>'feuser_select',
												'limit'=>'25',
												'minLength'=>3,
												'model'=>'tx_femanagement_model_qsm_fe_users',
												'username' => $this->feUser,
												'usernameDisplay' => $usernameDisplay,
												'dynList' => TRUE,
												'newRowTitle' => 'Weiteres Mitglied der Gremienleitung hinzufügen',
												);
		$fieldSettings['mitglieder'] = array(
												'title'=>'Mitglieder (nur Lese-Berechtigung)',
												'type'=>'feuser_select',
												'limit'=>'25',
												'minLength'=>3,
												'model'=>'tx_femanagement_model_qsm_fe_users',
												'username' => $this->feUser,
												'usernameDisplay' => $usernameDisplay,
												'dynList' => TRUE,
												'newRowTitle' => 'Weiteres Gremienmitglied hinzufügen',
												);
		$fieldSettings['save'] = array(
										 'value'=>'Gremium speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
			);
		$formData = $this->createFormFields($fieldSettings);
	}
												
	function getListViewFields() {
		return array(
			'kuerzel'=>'Kürzel',
			'title'=>'Titel',
			'mitglieder'=>'Mitglieder',
		);
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
//		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
//		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichData = $bereicheModel->getSelectList($this->getPid());
		$filterListe[20] = $this->formView->createFilter('select','bereich','Bereich',$sessionDaten,$bereichData,0,FALSE,'bereich');
				
		$filterListe[31] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
		$filterListe[32] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
		$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
		$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function createFormSingle(&$formData,$mode='new') {		
		$titelFelder = array('kuerzel','title','admins','mitglieder',);										
		$titelcontainer = $this->createContainer($titelFelder,$formData);
		$containerList = array($titelcontainer);
		$this->formView->addFieldset($containerList);		
		$buttonFelder = array('save');	
		$containerButtons = $this->createContainer($buttonFelder,$formData,FALSE,'buttons');
		$containerList = array($containerButtons);						
		$this->formView->addFieldset($containerList,'','');						
	}
	
	function saveFormData(&$formData,$uid,$hidden) {
		if (!empty($uid)) {
			$res = $this->model->updateDbEntry($formData,$uid);
		} else {
			$res = $this->model->insertDbEntry($formData,'hidden',0);
		}
		return $res;
	}
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_gremien.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_gremien.php']);
}

?>
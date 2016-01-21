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
class tx_femanagement_controller_calendar_location extends tx_femanagement_controller_calendar_main {
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_cal_location_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_cal_location',
																							 $this->piBase,
																							 $this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Orte',
																							'cal_single',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_calendar_location');																		
		$this->formView->setModelName('tx_femanagement_model_cal_location');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_form_cal_location_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_cal_location',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Orte',
																							'cal_list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_calendar_location');																		
		$this->formView->setModelName('tx_femanagement_model_cal_location');																		
	}

	function initFormSingle(&$formData,$mode='new') {		
		$fieldSettings['name'] = array(
												'title'=>'Bezeichnung des Veranstaltungsorts',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['tx_femanagement_cal_campus'] = array(
												'title'=>'Standort',
												'type'=>'select',
												'selectData'=>$this->model->getCampusList(),
												);
		$fieldSettings['tx_femanagement_cal_building'] = array(
												'title'=>'Gebäude',
												'type'=>'input',
												);
		$fieldSettings['tx_femanagement_cal_room'] = array(
												'title'=>'Raum',
												'type'=>'input',
												);
		
		if (parent::isAdmin()) {
			$fieldSettings['description'] = array(
													'title'=>'Beschreibung',
													'type'=>'rte',
													);
			$fieldSettings['street'] = array(
													'title'=>'Straße',
													'type'=>'input',
													);
			$fieldSettings['zip'] = array(
													'title'=>'PLZ',
													'type'=>'input',
													);
			$fieldSettings['city'] = array(
													'title'=>'Ort',
													'type'=>'input',
													);
			$fieldSettings['phone'] = array(
													'title'=>'Tel.',
													'type'=>'input',
													);
			$fieldSettings['email'] = array(
													'title'=>'E-Mail',
													'type'=>'input',
													);
			$fieldSettings['image'] = array(
													'title'=>'Bild',
													'type'=>'file',
													);
			$fieldSettings['link'] = array(
													'title'=>'Link zur Webseite',
													'type'=>'input',
													);
		}
		$fieldSettings['save'] = array(
										 'value'=>'Veranstaltungsort speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
			);
		$formData = $this->createFormFields($fieldSettings);
	}
												
	function showListView($aktuelleSeite) {
		parent::initListViewMenu($aktuelleSeite);
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$buttonListe = array($this->formView->createButton('newElem',$this->params));		
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[40] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		
		if ($this->isAdmin()) {
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterListe[30] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[31] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
		} else if ($this->isEditor()) {
			$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		}			
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}

	function getListViewFields() {
		return array('name'=>'Titel',
								 'tx_femanagement_cal_campus'=>'Standort',
								 'tx_femanagement_cal_room'=>'Raum',
								 'tx_femanagement_cal_building'=>'Gebäude',
								 'anz_termine'=>'Anz. Termine',
		);
	}

	function createFormSingle(&$formData,$mode='new') {		
		if (parent::isAdmin()) {
			$titelFelder = array('name','tx_femanagement_cal_campus','tx_femanagement_cal_building','tx_femanagement_cal_room','description',);										
			$adressFelder1 = array('street');	
			$adressFelder2 = array('zip','city',);	
			$restFelder = array('email','image','link',);	
			$titelcontainer = $this->createContainer($titelFelder,$formData);
			$adressContainer1 = $this->createContainer($adressFelder1,$formData);
			$adressContainer2 = $this->createContainer($adressFelder2,$formData,FALSE,'field_col2');
			$restContainer = $this->createContainer($restFelder,$formData);
			$containerList = array($titelcontainer,$adressContainer1,$adressContainer2,$restContainer);
		} else {
			$titelFelder = array('name','tx_femanagement_cal_room');										
			$titelcontainer = $this->createContainer($titelFelder,$formData);
			$containerList = array($titelcontainer);
		}
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar_location.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar_location.php']);
}

?>
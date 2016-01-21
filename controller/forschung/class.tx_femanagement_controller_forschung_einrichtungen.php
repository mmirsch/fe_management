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
class tx_femanagement_controller_forschung_einrichtungen extends tx_femanagement_controller {

	function __construct(&$piBase='',&$params='') {										//Erzeugt ein Objekt für die Klasse news_controller
		parent::__construct($piBase,$params);											//Erzeugt ein Objekt für die Vaterklasse controller
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/forschung/css/forschung.css"/>
		';
	}
	
	function initSingleView() {															//Vorbereitung des Single Views
		$viewClassName = 'tx_femanagement_view_forschung_einrichtungen_single';						//Übergeben der speziellen View Klasse
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				//Aufruf der Funktion eidViewHandler mit dem Single View
		
		$this->model = t3lib_div::makeInstance											//Erzeugen einer News-Model-Instanz (in der Variablen protected $model der Vaterklasse)(Datenbankzugriffe)
		(
			'tx_femanagement_model_forschung_einrichtungen',									//Übergeben der richtigen Model Klasse (als Parameter)
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid()				//Übergeben der Konstanten (mit Speicher PID für News)
		);
		$this->formView = t3lib_div::makeInstance										//Erzeugen einer Single-View Objekt (in der Variablen protected $formView der Vaterklasse)(Ansicht für Einzelansicht)
		(
			$viewClassName,																//Auswahl der entsprechenden Klasse
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid(),									//Übergeben der Konstanten (mit Speicher PID für News)
			'Forschungs-Einrichtung',																//Überschrift über dem Formular
			'forschungseinrichtung_single',																//Bezeichnung der DIV Klasse um das Formular
			$this->eidViewHandler														//Definition der eidURL
		);
		$this->formView->setControllerName('tx_femanagement_controller_forschung_einrichtungen');			//Funktionsaufruf von setControllerName der Vaterklasse auf das erstellte Viewobjekt 																
		$this->formView->setModelName('tx_femanagement_model_forschung_einrichtungen');					//Funktionsaufruf von setModelName der Vaterklasse auf das erstellte Viewobjekt
	}
	
	function initListView() {															//Vorbereitung des List Views
		$viewClassName = 'tx_femanagement_view_forschung_einrichtungen_list';							//siehe oben wie bei initSingleView
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_forschung_einrichtungen',
			$this->piBase,$this->getPid()
		);
		
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Forschungseinträge',
			'news_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_forschung_einrichtungen');																		
		$this->formView->setModelName('tx_femanagement_model_forschung_einrichtungen');																		
	}
				
	function initFormSingle(&$formData,$mode) {		
		$fieldSettings['title'] = array(
												'title'=>'Titel',
												'type'=>'input',
												'validate'=>'required',
												);

		$fieldSettings['pid'] = array(
											 	'type'=>'hidden',
												'prefill'=>$this->getPid(),
											);

		$fieldSettings['save'] = array(
										 'value'=>'Forschungs-Einrichtung speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
		);
		$formData = $this->createFormFields($fieldSettings);
	}

	function createFormSingle(&$formData,&$parameter,$mode) {		
		$hauptFelder = array('title','admins','pid');										
		$hauptContainer = $this->createContainer($hauptFelder,$formData);

		$containerListMain = array($hauptContainer);
		$this->formView->addFieldset($containerListMain);
	}
	
	function getListViewFields() {
		return array('title'=>'Titel',
				'admins'=>'Administratoren',
		);
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/forschung/class.tx_femanagement_controller_forschung_einrichtungen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/forschung/class.tx_femanagement_controller_forschung_einrichtungen.php']);
}

?>
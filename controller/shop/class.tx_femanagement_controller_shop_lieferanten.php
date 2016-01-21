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
class tx_femanagement_controller_shop_lieferanten extends tx_femanagement_controller_shop_main {
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function handle_view($uid) {
		$viewClassName = 'tx_femanagement_view_form_shop_lieferanten_single';
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Lieferanten',
			'lieferanten_single',
			''
		);
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_shop_lieferanten',
			$this->piBase,
			$this->getPid()
		);
		return $this->formView->showSingleData($this->model,$uid);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_shop_lieferanten_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_shop_lieferanten',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Lieferanten',
																							'lieferanten_single',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_shop_lieferanten');																		
		$this->formView->setModelName('tx_femanagement_model_shop_lieferanten');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_form_shop_lieferanten_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_shop_lieferanten',$this->piBase,$this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Lieferanten',
																							'lieferanten_list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_shop_lieferanten');																		
		$this->formView->setModelName('tx_femanagement_model_shop_lieferanten');																		
	}
	
	function initFormSingle(&$formData,$mode='new') {		
		$fieldSettings['title'] = array(
												'title'=>'Lieferant',
												'type'=>'input',
												'validate'=>'string',
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
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		$buttonListe = array();
		$index = 10;
		foreach ($this->shopConfig['filterFields'] as $field=>$title) {
			switch ($field) {
				case 'az':
					$filterListe[$index] = $this->formView->createFilter('hidden','az','','','','all');
					$index++;
					break;
				case 'keyword1':
					$keywords1 = $this->formView->gibKeywords1();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$keywords1);
					$index++;
					break;
				case 'keyword2':
					$keywords1 = $this->formView->gibKeywords2();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$keywords1);
					$index++;
					break;
				case 'volltextsuche':
					$filterListe[$index] = $this->formView->createFilter('search','volltextsuche',$title,'');
					$index++;
					break;
			}
		}
		
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,$this->shopConfig['limit'],TRUE);
		$filterListe[31] = $this->formView->createFilter('reset','reset','Formular zurücksetzen','Alle Formularfilter (Volltextsuche, Kategorien etc.) zurücksetzen');
		$filterListe[100] = $this->formView->createFilter('hidden','sortField','','','',$this->sortField);
		$filterListe[101] = $this->formView->createFilter('hidden','sortMode','','','','ASC');
		$filterListe[102] = $this->formView->createFilter('hidden','page','','','','0');
		$filterListe[103] = $this->formView->createFilter('hidden','page_id','','','',$this->pageId);
		$filterListe[104] = $this->formView->createFilter('hidden','hidden','','','','0');
		$filterListe[105] = $this->formView->createFilter('hidden','deleted','','','','0');
		$filterListe[999] = $this->formView->createFilter('hidden','shop_id','','','',$this->shopId);
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite,array('shopId'=>$this->shopId));
	}
	
	function createFormSingle(&$formData,$mode='new') {		
		if (parent::isAdmin()) {
			$titelFelder = array('title','beschreibung',);										
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
	
	function getSearchFields() {
		return $this->shopConfig['filterSearchFields'];
	}
	
	function getSinglePageConfig() {
		return array('pageId' => $this->shopConfig['singleViewPageId'],
								 'mode' => $this->shopConfig['singleview']['mode']);
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_shop_lieferanten.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_shop_lieferanten.php']);
}

?>
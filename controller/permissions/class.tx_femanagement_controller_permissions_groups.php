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
class tx_femanagement_controller_permissions_groups extends tx_femanagement_controller_permissions_main {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
		
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_permissions_groups_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups',
																							 $this->piBase,
																							 $this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Anwendung',
																							'single',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_permissions_groups');																		
		$this->formView->setModelName('tx_femanagement_model_permissions_groups');																		
	}
	
	function initListView() {
		$viewClassName = 'tx_femanagement_view_form_permissions_groups_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Anwendung',
																							'list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_permissions_groups');																		
		$this->formView->setModelName('tx_femanagement_model_permissions_groups');																		
	}
				
	function initFormSingle(&$formData,$mode) {			
		$fieldSettings['title'] = array(
												'title'=>'Bezeichnung',
												'type'=>'text',
												'validate'=>'string',
												);
		$fieldSettings['description'] = array(
												'title'=>'Beschreibung',
												'type'=>'text',
												'CONST_TEXTAREA_ROWS' => $this->piBase->settings['CONST_TEXTAREA_ROWS'],
								  			'CONST_TEXTAREA_COLS' => $this->piBase->settings['CONST_TEXTAREA_COLS'],
												);
		$dataProviderFeGroups = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups');
		$feGroupsCallbacks = array(
															'php' => array('object'=>$dataProviderFeGroups,
																						 'method'=>'getFeGroupsList',
																						 'getTitles' => 'getFegroupsTitles',
																						 'pid'=>$this->getPid()),
														);
		$fieldSettings['usergroup'] = array(
												'title'=>'Benutzergruppen',
												'type'=>'multiselect',
												'validate'=>'required', 
												'options'=>array('searchable'=>TRUE,'height'=>300,'width'=>400),
												'callbacks'=>$feGroupsCallbacks,
												);

		$urlNewRole = $this->piBase->cObj->typoLink_URL($linkConf);
		$model = 	t3lib_div::makeInstance('tx_femanagement_model_permissions_roles',$this->piBase,$this->getPid());
		$rollen = $model->getList();
		$fieldSettings['role'] = array(
												'title'=>'Rolle',
												'type'=>'select',
												'selectData'=>$rollen,
												'emptySelectTitle'=>'Bitte auswählen',
												'validate'=>'required',
												);
		
		$model = 	t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->getPid());
		$bereiche = $model->getList();
		$fieldSettings['domain'] = array(
										'title'=>'Bereich',
										'type'=>'select',
										'selectData'=>$bereiche,
										'emptySelectTitle'=>'keinen Bereich festlegen',
		);
		
		$model = 	t3lib_div::makeInstance('tx_femanagement_model_permissions_apps',$this->piBase,$this->getPid());
		$domains = $model->getList();
		$fieldSettings['application'] = array(
												'title'=>'Anwendung',
												'type'=>'select',
												'selectData'=>$domains,
												'emptySelectTitle'=>'Bitte auswählen',
												'validate'=>'required',
		);
		
		$formData = $this->createFormFields($fieldSettings);
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {		
		$felder = array('title','description',
										'usergroup','role','domain','application',
										);										
		$datenContainer = $this->createContainer($felder,$formData);
		$containerList = array($datenContainer);
		$this->formView->addFieldset($containerList);		
		$this->formView->addFormSingleButtons(array('speichern'=>'Berechtigung speichern'));
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_permissions_groups.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_permissions_groups.php']);
}

?>
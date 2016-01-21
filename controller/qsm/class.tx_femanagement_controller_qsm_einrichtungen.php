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
class tx_femanagement_controller_qsm_einrichtungen extends tx_femanagement_controller_qsm_main {
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {
		$viewClassName = 'tx_femanagement_view_qsm_einrichtungen_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_einrichtungen',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Einrichtung',
																							'single',
																							$this->eidViewHandler);
		
		$this->formView->setControllerName('tx_femanagement_controller_qsm_einrichtungen');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_einrichtungen');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_qsm_einrichtungen_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_einrichtungen',$this->piBase,$this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Einrichtungen',
																							'list_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_einrichtungen');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_einrichtungen');																		
	}
	
	function initFormSingle(&$formData,$mode='new') {		
		$fieldSettings['kuerzel'] = array(
												'title'=>'Kürzel der Einrichtung',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['title'] = array(
												'title'=>'Bezeichnung der Einrichtung',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['save'] = array(
										 'value'=>'Einrichtung speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
			);
		$formData = $this->createFormFields($fieldSettings);
	}
												
	function getListViewFields() {
		return array(
			'kuerzel'=>'Kürzel',
			'title'=>'Titel',
		);
	}

	function createFormSingle(&$formData,$mode='new') {		
		$titelFelder = array('kuerzel','title',);										
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

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_einrichtungen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_einrichtungen.php']);
}

?>
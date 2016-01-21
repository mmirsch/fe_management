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
class tx_femanagement_controller_permissions_domains extends tx_femanagement_controller_permissions_main {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
		
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_permissions_domains_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',
																							 $this->piBase,
																							 $this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Rollen',
																							'single',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_permissions_domains');																		
		$this->formView->setModelName('tx_femanagement_model_permissions_domains');																		
	}
	
	function initListView() {
		$viewClassName = 'tx_femanagement_view_form_permissions_domains_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->piBase->settings['STORAGE_PID'],
																							'Rollen',
																							'list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_permissions_domains');																		
		$this->formView->setModelName('tx_femanagement_model_permissions_domains');																		
	}

	function initFormSingle(&$formData,$mode) {
		$fieldSettings['title'] = array(
												'title'=>'Bereich',
												'type'=>'text',
												'validate'=>'string',
												);
		$formData = $this->createFormFields($fieldSettings);
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {
		$allgemeineFelder = array('title');										
		$container = $this->createContainer($allgemeineFelder,$formData);
		$containerList = array($container);
		$this->formView->addFieldset($containerList);		
		$this->formView->addFormSingleButtons(array('speichern'=>'Bereich speichern'));
	}	
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_permissions_domains.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_permissions_domains.php']);
}

?>
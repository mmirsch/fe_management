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

require_once(PATH_tslib.'class.tslib_pibase.php');

define('femanagement_extKey','fe_management');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_femanagement_pi1';
	var $scriptRelPath = 'pi1/class.tx_femanagement_pi1.php';
	var $extKey        = 'fe_management';
	var $upload_dir    = 'uploads/tx_femanagement/';
	var $pi_checkCHash = true;
	var $post;
	var $get;
	var $settings;
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		
		$this->pi_setPiVarDefaults();														#SET pi Vars	
		$this->pi_loadLL();																	#LOAD Local Language
		$this->pi_USER_INT_obj = 1;															#Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!		
		$this->post = t3lib_div::_POST();  													#LOAD the post values from forms
		$this->get = t3lib_div::_GET();  			#LOAD the post values from forms
		$this->conf = $conf;																#LOAD constants from typoscript
		$this->initHeader();																#Initialisierung des Header --> Funktion zur Einbindung der javascripte
		$this->initSettings();																#Initialisierung der Einstellungen --> Funktion zur Auswahl zwischen Konstanten und Flexform Einstellungen
		
		$mode = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mode','ansicht');	#SELECT view mode from flexform
    switch ($mode) {
			case "PERMISSIONS":
				$content = $this->handlePermissions();
				break;
			case "NEWS":
				$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'news','ansicht');		
				$content = $this->handleApplication($app);
				break;
			case "FORSCHUNG":
				$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'forschung','ansicht');		
				$content = $this->handleApplication($app);
				break;
      case "PROMOTIONEN":
        $app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'promotionen','ansicht');
        $content = $this->handleApplication($app);
        break;
			case "CAL":
				$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'cal','ansicht');		
				$content = $this->handleApplication($app);
				break;
			case "SHOP":
				$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'shop','ansicht');		
				$content = $this->handleApplication($app);
				break;
			case "QSM":
    		$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'qsm','ansicht');		
				$content = $this->handleApplication($app);
				break;	
			case "MODULES_EN":
    		$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'modules_en','ansicht');		
				$content = $this->handleApplication($app);
				break;	
			case "EVENTS":
    		$app = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'events','ansicht');		
				$content = $this->handleApplication($app);
				break;	
		}
		return $this->pi_wrapInBaseClass($content);
	}
	
	function handlePermissions() {
		if (!empty($this->get['tx_femanagement']['page'])) {
			if ($this->get['tx_femanagement']['page']=='apps') {
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_permissions_apps',$this,$this->get);
			} else if ($this->get['tx_femanagement']['page']=='roles') {
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_permissions_roles',$this,$this->get);
			} else if ($this->get['tx_femanagement']['page']=='domains') {
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_permissions_domains',$this,$this->get);
			} else {
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_permissions_groups',$this,$this->get);		
			}
		} else {
			$controller = t3lib_div::makeInstance('tx_femanagement_controller_permissions_groups',$this,$this->get);		
		}
		$content = $this->handleEvent($controller);
		return $content;	
	}
	
	function handleApplication($app) {
		if (!empty($this->post) && count($this->post)>0) {
			$post = TRUE;
		} else {
			$post = FALSE;
		}	
		switch ($app) {
			case "CAL_EVENTS":
				if (!empty($this->get['tx_femanagement']['page'])) {
					if ($this->get['tx_femanagement']['page']=='events') {
						$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_event',$this,$this->get);
					} else if ($this->get['tx_femanagement']['page']=='locations') {
						$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_location',$this,$this->get);
					} else if ($this->get['tx_femanagement']['page']=='organizers') {
						$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_organizer',$this,$this->get);
					}
				} else {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_event',$this,$this->get);		
				}
				$content = $this->handleEvent($controller);
				break;
			case "CAL_CREATE_EVENT":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_event',$this,$this->get);
				$content = $controller->handle_new($this->post,$post,$this->get['tx_femanagement']);
				break;	
			case "CAL_CREATE_LOCATION":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_location',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "CAL_CREATE_ORGANIZER":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_calendar_organizer',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "NEWS_EVENTS":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_news',$this,$this->get,$this->settings);
				$content = $this->handleEvent($controller);
				break;
			case "NEWS_CREATE_EVENT":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_news',$this,$this->get,$this->settings);
				$content = $controller->handle_new($this->post,$post,$this->get['tx_femanagement']);
				break;
			case "FORSCHUNG_MANAGEMENT":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_forschung',$this,$this->get,$this->settings);
				$content = $this->handleEvent($controller);
				break;
			case "FORSCHUNG_CREATE":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_forschung',$this,$this->get,$this->settings);
				$content = $controller->handle_new($this->post,$post,$this->get['tx_femanagement']);
				break;
			case "FORSCHUNG_LISTVIEW":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_forschung',$this,$this->get,$this->settings);
				$content = $this->handleEvent($controller);
				break;
			case "FORSCHUNG_PERSONEN":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_forschung_personen',$this,$this->get,$this->settings);
				$content = $this->handleEvent($controller);
				break;
      case "PROMOTIONEN_MANAGEMENT":
        $controller = t3lib_div::makeInstance('tx_femanagement_controller_promotionen',$this,$this->get,$this->settings);
        $content = $this->handleEvent($controller);
        break;
      case "PROMOTIONEN_CREATE":
        $controller = t3lib_div::makeInstance('tx_femanagement_controller_promotionen',$this,$this->get,$this->settings);
        $content = $controller->handle_new($this->post,$post,$this->get['tx_femanagement']);
        break;
			case 'PROMOTIONEN_LISTVIEW':
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_promotionen',$this,$this->get,$this->settings);
				$content = $this->handleEvent($controller);
				break;
      case "EVENTS_MANAGEMENT":
        $controller = t3lib_div::makeInstance('tx_femanagement_controller_events',$this,$this->get);
        $content = $this->handleEvent($controller);
        break;
      case "EVENTS_BOOKING":
        $controller = t3lib_div::makeInstance('tx_femanagement_controller_events_anmeldungen',$this,$this->get);
        $content = $this->handleEvent($controller);
        break;
      case "EVENTS_BOOKING_MANAGEMENT":
        $controller = t3lib_div::makeInstance('tx_femanagement_controller_events_anmeldungen',$this,$this->get);
        $content = $this->handleEvent($controller);
        break;
      case "MODULES_EN_MANAGEMENT":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_modules_en',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "SHOP_ARTICLE":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_shop_article',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "SHOP_LIEFERANTEN":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_shop_lieferanten',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "SHOP_HERSTELLER":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_shop_hersteller',$this,$this->get);
				$content = $this->handleEvent($controller);
				break;
			case "SHOP_ARTICLE_CREATE":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_shop_article',$this,$this->get);
				$content = $controller->handle_new($this->post,$post,$this->get['tx_femanagement']);
				break;
			case "SHOP_CART":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_shop_article',$this,$this->get);
				$content = $controller->showCart();
				break;
			case "QSM_CREATE":
			case "QSM_LIST":
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_general',$this);
				$content = $controller->handleGeneralEvent($app);
				break;
		}
		return $content;
	}
	
	function initSettings() {
		$this->pi_initPIflexForm();
		$flexform = array();
				
		foreach ($this->cObj->data['pi_flexform']['data'] as $sheet => $data ) {
    		foreach ($data['lDEF'] as $key => $val ) {
        		$this->settings[$key] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $key, $sheet, 'lDEF');
   	 		}
		}
		foreach ($this->conf as $key => $val ) {								# Geht einzelne Konstantenwerte durch
			if(array_key_exists($key, $this->settings)) {						# Gibt es den Key als Flexform?
				if($this->settings[$key] == '') {								# wenn ja prüfen ob flexform leer ist
					$this->settings[$key] = $this->conf[$key];
				}
			} else {
				$this->settings[$key] = $this->conf[$key];
			}
		}

	}
	
	function initHeader() {
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
				<script src="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/messages_de.js" type="text/javascript"></script>
				<script src="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/femanagement.js" type="text/javascript"></script>
				<script src="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/delay.js" type="text/javascript"></script>
				<link rel="stylesheet" type="text/css" href="/typo3/sysext/t3skin/stylesheets/sprites/t3skin.css"/>
				<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/jquery.ui.all.css"/>
				<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/jquery.ui.datepicker.css"/>
				<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/femanagement.css"/>
				';
/*
 * ########################## FILE UPLOAD ##########################
 */	
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
				<script src="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/file_upload/jquery.iframe-transport.js" type="text/javascript"></script>
				<script src="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/file_upload/jquery.fileupload.js" type="text/javascript"></script>
				<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->extKey) . 'res/file_upload/jquery.fileupload-ui.css"/>
				';
	}
/*
 * ########################## handle Event ##########################
 * Auswahl welche Ansicht angezeigt werden soll
 */	
	function handleEvent($controller) {
		if (!empty($this->post) && count($this->post)>0) {
			$post = TRUE;
		} else {
			$post = FALSE;
		}

		if (isset($this->get['tx_femanagement']['mode'])) {
			if ($this->get['tx_femanagement']['uid']) {
				$uid = $this->get['tx_femanagement']['uid'];
			} else {
				$uid = '';
			}
						
			switch ($this->get['tx_femanagement']['mode']) {
			case 'new':
				$content = $controller->handle_new($this->post,$post);
				break;	
			case 'edit':
				$content = $controller->handle_edit($this->post,$post,$uid);
				break;	
			case 'delete':
				$content = $controller->handle_delete($uid);
				break;	
			case 'view':
				$content = $controller->handle_view($uid);
				break;
			case 'copy':
				$content = $controller->handle_copy($this->post,$post,$uid);
				break;
			}
		} else {
			if (isset($this->get['tx_femanagement']['page'])) {
				$seite = $this->get['tx_femanagement']['page'];
			} else {
				$seite = '';
			}
			$content = $controller->handle_list_view($seite);
		}
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/pi1/class.tx_femanagement_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/pi1/class.tx_femanagement_pi1.php']);
}

?>
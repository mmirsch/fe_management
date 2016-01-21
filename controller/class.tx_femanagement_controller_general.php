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
class tx_femanagement_controller_general {
var $parent;
	
	function __construct(&$parent) {
		$this->parent = &$parent;
		$this->initHeader();
	}
	
	function initHeader() {
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
		<script src="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/messages_de.js" type="text/javascript"></script>
		<script src="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/femanagement.js" type="text/javascript"></script>
		<script src="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/delay.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="/typo3/sysext/t3skin/stylesheets/sprites/t3skin.css"/>
		<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/jquery.ui.all.css"/>
		<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/jquery.ui.datepicker.css"/>
		<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/femanagement.css"/>
		';
		/*
		 * ########################## FILE UPLOAD ##########################
		*/
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
		<script src="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/file_upload/jquery.iframe-transport.js" type="text/javascript"></script>
		<script src="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/file_upload/jquery.fileupload.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath($this->parent->extKey) . 'res/file_upload/jquery.fileupload-ui.css"/>
		';
	}
	
	/*
	 * ########################## handle Event ##########################
	* Auswahl welche Ansicht angezeigt werden soll
	*/
	function handleEvent(&$controller) {
		if (!empty($this->parent->post) && count($this->parent->post)>0) {
			$post = TRUE;
		} else {
			$post = FALSE;
		}
		if (isset($this->parent->get['tx_femanagement']['page'])) {
			$aktuelleSeite = $this->parent->get['tx_femanagement']['page'];
		} else {
			$aktuelleSeite = '';
		}
		if (isset($this->parent->get['tx_femanagement']['mode'])) {
			$mode = $this->parent->get['tx_femanagement']['mode'];
			if ($this->parent->get['tx_femanagement']['uid']) {
				$uid = $this->parent->get['tx_femanagement']['uid'];
			} else {
				$uid = '';
			}
			switch ($mode) {
				case 'new':
					$content = $controller->handle_new($this->parent->post,$post,$this->get['tx_femanagement'],$aktuelleSeite);
					break;
				case 'edit':
					$content = $controller->handle_edit($this->parent->post,$post,$uid,$aktuelleSeite);
					break;
				case 'copy':
					$content = $controller->handle_copy($this->parent->post,$post,$uid,$aktuelleSeite);
					break;
				case 'delete':
					$content = $controller->handle_delete($uid);
					break;
				case 'view':
					$content = $controller->handle_view($uid);
					break;
				default:
					$content = $controller->handleCustomMode($controller,$mode,$this->parent->post,$post,$uid,$aktuelleSeite);
					break;
			}
		} else {
			$content = $controller->handle_list_view($aktuelleSeite);
		}
		return $content;
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_general.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_general.php']);
}

?>
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
class tx_femanagement_controller_calendar_main extends tx_femanagement_controller {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initListViewMenu($aktuelleSeite) {
		$events = $this->formView->createMenuEntry('Termine','events');
		$locations = $this->formView->createMenuEntry('Orte','locations');
		$organizers = $this->formView->createMenuEntry('Veranstalter','organizers');
		$menu = array($events,$locations,$organizers);
		$this->formView->setMenu($menu,'menu_top');
	}

	function showListView($aktuelleSeite) {
		$this->initListViewMenu($aktuelleSeite);
		return parent::showListView($aktuelleSeite);
	}	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar.php']);
}

?>
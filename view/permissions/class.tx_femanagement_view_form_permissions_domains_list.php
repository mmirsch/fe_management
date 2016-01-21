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

class tx_femanagement_view_form_permissions_domains_list extends tx_femanagement_view_form_list {

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$pid);
	}
	
	function getNewelemButtonTitle() {
		return 'Neuen Bereich anlegen'; 
	}

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/permissions/class.tx_femanagement_view_form_permissions_domains_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/permissions/class.tx_femanagement_view_form_permissions_domains_list.php']);
}

?>
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

#require_once(t3lib_extMgm::extPath('fe_management').'model/class.tx_femanagement_cal_model.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_field_hidden extends tx_femanagement_view_field {

	public function __construct($elements) {
		parent::__construct($elements);
	}
	
	function createLabel() {
		return '';
	}
	
	function editData()	{
		if (!empty($this->value)) {
			$value = ' value="' . $this->value . '" ';
		} else {
			$value = ' value="' . $this->prefill . '" ';
		}
		$out = $label . 
					 '<input id="' . $this->name . '" name="' . $this->name . '"' . 
					 ' type="hidden" ' . $value . '/>';
		
		return $out;
	}
	
	function viewData()	{
		return '';
	}
	
	public static function getDyntableCode($key, $row, $name, $val) {
		return '<input class="' . $key . '" type="hidden" ' . 
						'name="' . $name . '[' . $row . '][' . $key . ']" ' . 
						'value="' . $val . '" />';
	}
	
	public static function getDyntableCodeNewElem($key, $name, $val) {
		return '<input type="hidden" ' .
	         'class="' . $key . '"' . $val .
	         ' name="' . $name . '[###numRows###][' . $key . ']" />';
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_hidden.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_hidden.php']);
}

?>
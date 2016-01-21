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
class tx_femanagement_view_field_checkbox extends tx_femanagement_view_field {

	public function __construct($elements) {
		parent::__construct($elements);
	}
	
	function setValue($value)	{
		$this->value = $value;
	}
	
	function getValue()	{
		return $this->value;
	}
	
	function convertPostParameter($value) {
		if ($value=='on') {
			return 1;
		} else {
			return 0;
		}
	}
	
	function viewData()	{
		if ($this->value=='on') {
			$value =  'ja';
		} else {
			$value =  'nein';
		}
		return '<span class="value">' . $value . '</span>';
	}

  function dataWrap($output)	{
    $this->stdWrap = '<div class="field_data checkbox">|</div>';
    $wrappedOutput = $this->cObj->stdWrap($output,
      array(
        'wrap' => $this->stdWrap
      ));
    return $wrappedOutput;
  }

  function editData()	{
		$validateClass = $this->createValidation();
		if ($this->value==TRUE) {
			$checked = ' checked="checked" ';
		} else {
			$checked = '';
		}
		if (!empty($this->tooltip)) {
			$tooltip = ' title="' . $this->tooltip . '" ';
		} else {
			$tooltip = '';
		}
		$out = '<input type="hidden" name="' . $this->name . '" value="off" />' . 
					 '<input ' . $tooltip . ' id="' . $this->name . '" name="' . $this->name . '"' . 
					 ' type="checkbox" ' . $checked  . $validateClass . '/>';
		return $out;
	}
	
	public static function getDyntableCode($key, $row, $name, $val) {
		if ($val=='on') {
			$checked = ' checked="checked" ';
		} else {
			$checked = '';
		}
		return '<input class="' . $key . '" type="hidden" ' .
					 'name="' . $name . '[' . $row . '][' . $key . ']" value="off" /> ' .
					 '<input class="' . $key . '" type="checkbox" ' .
					 'name="' . $name . '[' . $row . '][' . $key . ']"' . $checked . ' />';
	}
	
	public static function getDyntableCodeNewElem($key, $name, $val) {
		return '<input type="hidden" name="' . $name . '[###numRows###][' . $key . ']" value="off"/>' . 
					 '<input type="checkbox" class="' . $key . '"' . 
	         'name="' . $name . '[###numRows###][' . $key . ']" />';
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_checkbox.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_checkbox.php']);
}

?>
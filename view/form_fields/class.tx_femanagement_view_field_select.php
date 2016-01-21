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
class tx_femanagement_view_field_select extends tx_femanagement_view_field {
	var $selectData;
	var $emptySelectTitle = 'Bitte auswählen';
	
	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['selectData'])) {
			$this->selectData = $elements['selectData'];
		}
		if (!empty($elements['emptySelectTitle'])) {
			$this->emptySelectTitle = $elements['emptySelectTitle'];
		}
	}
	
	function viewData()	{
		return '<span class="value">' . $this->valueSelect . '</span>';
	}
	
	function csvData()	{
		return $this->valueSelect;
	}
	
	function editData()	{
		$validateClass = $this->createValidation('select');
	
		$select = '<select ' . $validateClass . 'name="' . $this->name . '" id="' . $this->name . '">' . "\n";
		$selected = '';
		if (empty($this->value)) {
			$selected = ' selected="selected" ';
		}
		$select .= '<option ' . $selected . 'value="">' . $this->emptySelectTitle . '</option>' . "\n";
		foreach ($this->selectData as $key=>$val) {
			$selected = '';
			if ($key==$this->value) {
				$selected = ' selected="selected" ';
			}
			$select .= '<option ' . $selected . 'value="'. $key . '">' .
					$val .
					'</option>' . "\n";
		
		}
		$select .= '</select>' . "\n";
		$out = '';
		$out .= '<div id="select_' . $this->name . '" class="selectElem">';
		$out .= $select;
		$out .= '</div>';
		return $out;
	}
	
	public static function getDyntableCode($key, $row, $name, $val, &$colData) {
		$res = '<select class="' . $key . '" name="' . $name . '[' . $row . '][' . $key . ']">';
		foreach ($colData as $selctVal=>$label) {
			if ($selctVal==$val) {
				$selected = ' selected="selected" ';
			} else {
				$selected = '';
			}
			$res .= '<option ' . $selected . 'value="' . $selctVal . '">' . $label . '</option>';
		}
		return $res;
	}
	
	public static function getDyntableCodeNewElem($key, $name, $val, &$colData) {
		$res = '<select class="' . $key . '"' . $val . 'name="' . $name . '[###numRows###][' . $key . ']">';
		foreach ($colData as $selctVal=>$label) {
			$res .= '<option value="' . $selctVal . '">' . $label . '</option>';
		}
		return $res;
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_select.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_select.php']);
}

?>
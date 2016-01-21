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
class tx_femanagement_view_field_radio extends tx_femanagement_view_field {
	var $selectData;
	
	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['selectData'])) {
			$this->selectData = $elements['selectData'];
		}
	}
	
	function viewData()	{
		return '<span class="value">' . $this->valueSelect . '</span>';
	}
	
	function csvData()	{
		return $this->valueSelect;
	}
	
	function editData()	{
		$validateClass = $this->createValidation('radio_' . $this->name);
		foreach ($this->selectData as $key=>$val) {
			$checked = '';
			if ($key==$this->value) {
				$checked = ' checked="checked" ';
			}
			$radio .= '<input ' . $validateClass . '" id="' . $this->name . $key . '" name="' . $this->name . '"' . 
					 ' type="radio" ' . $checked . ' value="' . $key . '" />' . 
					 ' <label for="' . $this->name . $key . '">' . $val . "</label>\n";
		
		}
		$out = "\n";
		$out .= '<div class="radio">';
		$out .= $radio;
		$out .= '</div>';
		if ($this->validate=='required') {
			$out .= '<script type="text/javascript">
				function validateRadio' . $this->name . '() {
					var checkedValue = $("input[name=' . $this->name . ']:checked").val();
					if (typeof checkedValue == "undefined") {
						return "Bitte wählen Sie einen Eintrag aus";
					} else {
						return true;
					}
				}
				';
			foreach ($this->selectData as $key=>$val) {
				$out .= '$.tools.validator.fn("#' . $this->name . $key . '", function(element, value) {
					return validateRadio' . $this->name . '();		
				});
				';
			}
			$out .= '</script>
			';
		}
		return $out;
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_radio.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_radio.php']);
}

?>
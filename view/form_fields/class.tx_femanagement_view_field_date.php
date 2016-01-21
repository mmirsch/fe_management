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
class tx_femanagement_view_field_date extends tx_femanagement_view_field {

protected $jQueryDateFormat = 'dd.mm.yy';
protected $dateFormat = 'd.m.Y';
	
	public function __construct($elements) {
		if (isset($elements['dateFormat'])) {
			if (!empty($elements['dateFormat'])) {
				$this->dateFormat = $elements['dateFormat'];
			}
		}
		parent::__construct($elements);
	}
	
	function setValue($value) {
		if (!empty($value)) {
			$valueNew = date($this->dateFormat,$value);
			$this->value = $valueNew;
		} else {
			$this->value = '';
		}
	}
	
	function getValue()	{
		if (!empty($this->value)) {
			$date = DateTime::createFromFormat($this->dateFormat, $this->value);
			return $date->getTimestamp();
		} else {
			return 0;
		}
	}
	
	function convertPostParameter($value) {
		if (!empty($value)) {
			$date = DateTime::createFromFormat($this->dateFormat, $value);
			return $date->getTimestamp();
		} else {
			return 0;
		}
	}
	
	function editData()	{
		$validateClass = $this->createValidation('date');
		if (!empty($this->value)) {
			$value = ' value="' . $this->value . '" ';
		} else {
			$value = '';
		}
		$out = '';
		if (isset($this->icons['delete'])) {
			$out .= '<span class="icon-actions t3-icon-edit-delete empty-field-val" title="Datumsfeld löschen" id="empty_' . $this->name . '"></span>';
		}
		$out .= '<input id="' . $this->name . '" name="' . $this->name . '"' . 
					 ' type="input" ' . $value . $validateClass . '/>
					 ';
		$out .= '<script type="text/javascript">
							function validateDate_' . $this->name . '(value) {
								if ("' . $this->validate . '"=="required") {
									if (value.match(/^\d\d?\.\d\d?\.\d\d\d\d$/)) {
										var errorLabelId = "error_' . $this->name . '";
										$("#" + errorLabelId).detach();
									}
								} else {
									if (value!="") {
										var errorLabelId = "error_' . $this->name . '";
										$("#" + errorLabelId).detach();
									}
								}
							}
							$("#empty_' . $this->name . '").click(function() {
								$("#' . $this->name . '").attr("value","");
							}); 
					 		$("#' . $this->name . '").datepicker({ 
							"dateFormat": "' . $this->jQueryDateFormat . '",
								"class": "picker_' . $this->name . '",
								"changeMonth": 1,
								"changeYear": 1,
								"showButtonPanel": 1,
								"onSelect": function(value) { validateDate_' . $this->name . '(value); },
								"onClose": function(value) { validateDate_' . $this->name . '(value); },
						 });
						 ';
		$out .= '$.tools.validator.fn("#' . $this->name . '", function(element, value) {
						';
		if ($this->validate=='required') {
			$out .= 'if (value=="") {
							    return "Bitte wählen Sie ein Datum aus";
								} 
								';
		} else {
			$out .= 'if (value=="") {
					return true;
			}
			';
		}
		$out .= ' if (value.match(/^\d\d?\.\d\d?\.\d\d\d\d$/)) {
								return true;
					    } else {
							   return "Bitte geben Sie das Datum im folgenden Format ein: TT.MM.JJJJ";
							}
						});	
						</script>
			';
			return $out;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_date.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_date.php']);
}

?>
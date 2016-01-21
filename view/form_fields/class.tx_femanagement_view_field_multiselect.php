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
class tx_femanagement_view_field_multiselect extends tx_femanagement_view_field {
static protected  $JS_INCLUDED = FALSE;
static protected  $selectedElems;
protected $preselect;
protected $exclusive_ids;
protected $exclusive_new_ids;

	public function __construct($elements) {
		parent::__construct($elements);
		if (self::$JS_INCLUDED == FALSE) {
			$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
				<script src="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/jquery.multi-select.js" type="text/javascript"></script>
				<script src="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/jquery.quicksearch.js" type="text/javascript"></script>
				<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/multi-select.css"/>
					';
			self::$JS_INCLUDED = TRUE;
		}
		if (isset($elements['selectedElems'])) {
			$this->selectedElems = $elements['selectedElems'];
		}
		if (isset($elements['preselect'])) {
			$this->preselect = $elements['preselect'];
		}
		if (isset($elements['exclusive_ids'])) {
			$this->exclusive_ids = $elements['exclusive_ids'];
		}
		if (isset($elements['exclusive_new_ids'])) {
			$this->exclusive_new_ids = $elements['exclusive_new_ids'];
		}
	}
	
	function viewData()	{
		$phpHandler = $this->callbacks['php'];
		$phpObject =  $phpHandler['object'];
		$phpMethod =  $phpHandler['getTitles'];
		$titles = $phpObject->$phpMethod($this->value,$pid);
		return '<span class="value">' . $titles . '</span>';		
	}
	
	function csvData()	{
		return $this->valueSelect;
	}
	
	function setValue($value)	{
		$this->value = $value;
	}
	
	function getValue()	{
		return $this->value;
	}
	
	function editData()	{
		$validateClass = $this->createValidation('select');
		$out = '';
		$phpHandler = $this->callbacks['php'];
		$phpObject =  $phpHandler['object'];
		$phpMethod =  $phpHandler['method'];
		$pid =  $phpHandler['pid'];
		if (!empty($this->exclude)) {
			$excludeIds = $this->exclude;
		} else {
			$excludeIds = array();
		}
		if (!empty($this->exclusive_ids)) {
			$exclusiveIds = $this->exclusive_ids;
		} else {
			$exclusiveIds = array();
		}
		if (isset($this->options['height'])) {
			$selectHeight = $this->options['height'];
		} else {
			$selectHeight = 150;
		}
		if (isset($this->options['width'])) {
			$selectWidth = $this->options['width'];
		} else {
			$selectWidth = 220;
		}
		$selectedItems = $this->value;
		if (count($this->mandatory)>0) {
			foreach ($this->mandatory as $field) {
				if (!in_array($field,$this->value)) {
					$this->value[] = $field;
				}
			}
		}
		if (count($this->preselect)>0) {
			foreach ($this->preselect as $field) {
				if (!in_array($field,$this->value)) {
					$this->value[] = $field;
				}
			}
		}
		if (count($this->dontRemove)>0) {
			foreach ($this->dontRemove as $field) {
				if (!in_array($field,$this->mandatory) && in_array($field,$selectedItems)) {
					$this->mandatory[] = $field;
				}
			}
		}
		
		
		$elemList = $phpObject->$phpMethod();
		if (count($this->exclusive_ids)>0) {
			$elemListNew = array();
			foreach($elemList as $key=>$title) {
				if (in_array($key,$this->exclusive_ids)) {
					$elemListNew[$key] = $title;
				}
			}
			$elemList = $elemListNew;
		}	else if (count($this->exclusive_new_ids)>0) {
			foreach($elemList as $key=>$title) {
				if (!in_array($key,$this->value) && 
						!in_array($key,$this->exclusive_new_ids)) {
					unset($elemList[$key]);
				}
			}
		}	elseif (count($this->exclude)>0) {
			foreach($elemList as $key=>$title) {
				if (in_array($key,$this->exclude)) {
					unset($elemList[$key]);
				}
			}
		}
		
		$options = tx_femanagement_view_form::getOptionList($elemList,$this->value);
		
//		$calModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
//		$datenOrig = $calModel->getList('',$pid);
/*				
		if (count($exclusive)>0) {
			foreach($datenOrig as $key=>$title) {
				if (in_array($key,$exclusive)) {
					$daten[$key] = $title;
				}
			}
		} else if (count($exclude)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$exclude)) {
					$daten[$key] = $title;
				}
			}
		} else {
			$daten = $datenOrig;
		}
*/		
		
		
		if (empty($this->selectedElems)) {
			$title = 'ausgewählte Elemente';
		} else {
			$title = $this->selectedElems;
		}
		$multiSelect = '<select name="' . $this->name. '[]" multiple="multiple" id="' . $this->name . '"> 
								 ' . $options . '
								 </select>';
		$out .= $multiSelect;
		if ($this->options['searchable']) {
			$searchBox = 'selectableHeader : \'<input type="text" id="search" autocomplete = "off" />\',';
		} else {
			$searchBox = '';
		}
		if (!empty($this->validate)) {
/*			
			$validation = '$("form").submit(function(elem) {
							if ($(".cancel").attr("clicked")) {
								return true;
							}
							var firstSelected = $(".ms-selection li").attr("ms-value");
							if (firstSelected!=undefined) {
								return true;
							} else {
								$("#error_' . $this->name . '").detach();
								$(\'<span id="error_' . $this->name . '" class="error">Bitte wählen Sie ein Element aus.</span>\').insertBefore($("#ms-' . $this->name . '"));
								return false;
							}
			';
*/
			$validation = '$.tools.validator.fn("#' . $this->name . '", function(element, value) {
					var firstSelected = $("#ms-' . $this->name . ' .ms-selection li").attr("ms-value");
					if (firstSelected!=undefined) {
						return true;
					} else {
						return "Bitte wählen Sie ein Element aus";
					}
				});
			';
			
		} else {
			$validation = '';
		}
		if (!empty($this->preselect)) {
			$preselect = 'mandatory: new Array("' .
															 implode('","',$this->mandatory) . '"),
			';
		} else {
			$mandatory = '';
		}
		if (!empty($this->mandatory)) {
			$mandatory = 'mandatory: new Array("' .
															 implode('","',$this->mandatory) . '"),
			';
		} else {
			$mandatory = '';
		}
		if (!empty($this->mandatoryMsg)) {
			$mandatoryMsg = 'mandatoryMsg: "' . htmlspecialchars($this->mandatoryMsg) . '",
			';
		} else {
			$mandatoryMsg = '';
		}

		$out .= '	<script type="text/javascript">		
				$("#' . $this->name . '").multiSelect({
 			     ' . $searchBox . '
								keepOrder: true, 
								' . $mandatory . $mandatoryMsg . '
		   					height: "' . $selectHeight . '", 
		   					width: "' . $selectWidth . '",
								selectableTitle: "Bitte auswählen",
								selectedTitle: "' . $title . '",
								elem: "' . $this->name . '"
							});
					    $("#search").quicksearch("#ms-' . $this->name . ' .ms-selectable li");
					  	' . $validation . '
						  </script>
		';
		return $out;
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_multiselect.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_multiselect.php']);
}

?>
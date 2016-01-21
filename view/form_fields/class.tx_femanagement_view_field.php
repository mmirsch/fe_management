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
class tx_femanagement_view_field {
protected $title = '';
protected $tooltip = '';
protected $name = '';
protected $type = '';
protected $fieldDescription = '';
protected $callbacks = '';
protected $validate = '';
protected $value = '';
protected $valueSelect = '';
protected $width = '';
protected $cssClass = '';
protected $readonly = FALSE;
protected $viewonly = FALSE;
protected $dynList = FALSE;
protected $icons;
protected $linkTitle = '';
protected $selected = FALSE;
protected $error = FALSE;
protected $errorMsg = 'Bitte füllen Sie dieses Feld aus';
protected $stdWrap = '';
protected $cObj;
protected $pid;
protected $model;
protected $prefill;
protected $mandatory;
protected $mandatoryMsg;
protected $exclude;
protected $showRequiredFields = TRUE;
protected $requiredSymbol = '*';
protected static $definitions = array();

	public function __construct($elements) {
 		if (is_array($elements) && count($elements)>0) {
			foreach ($elements as $feld=>$wert) {
				$this->$feld = $wert;
			}
 		}
 		$this->cObj = new tslib_cObj();
 		if (!empty($elements['prefill'])) {
 			$this->value = $elements['prefill'];
 		}
	}
	
	function setValue($value)	{
		if ($this->dynList) {
			$this->value = $value;
		} else {
			$this->value = htmlspecialchars($value);
		}
	}
	
	function setSelectValue($valueSelect)	{
		$this->valueSelect = htmlspecialchars($valueSelect);
	}
	
	function isDynList()	{
		return $this->dynList;
	}
	
	function getValue()	{
		if ($this->dynList) {
			return $this->value;
		} else {
			return htmlspecialchars_decode($this->value);
		}
	}
	
	function getFieldType() {
		return $this->type;
	}
	
	function getName() {
		return $this->name;
	}
	
	function getModel() {
		return $this->model;
	}
	
	function getUploadDir() {
		return $this->upload_dir;
	}
	
	function convertPostParameter($value) {
		return $value;
	}
	
	function show($mode)	{
		
		if ($this->dynList) {
			if ($mode=='view') {
				$out = $this->viewDynData();
			} else if ($mode=='new' || $mode=='copy' || $mode=='edit') {
				$out = $this->viewEditDynData();
			} else if ($mode=='csv') {
				$out = $this->csvDynData();
			}
		} else {
			if ($mode=='view' || $this->viewonly) {
				$out = $this->viewSingleData();
			} else if ($mode=='new' || $mode=='copy' || $mode=='edit') {
				$out = $this->viewEditData();
			} else if ($mode=='csv') {
				$out = $this->csvData();
			}
		}
		return $out;
	}
	
	function viewData()	{
		return '<span class="value">' . $this->value . '</span>';
	}
	
	function viewDescription()	{
		$fieldDescription = '';
		if (!empty($this->fieldDescription)) {
			$fieldDescription = '<span class="description">' . $this->fieldDescription . '</span>';
		}
		return $fieldDescription;
	}
	
	function viewSingleData()	{
		$label = $this->createLabel();
		$fieldData = $this->viewData();
		$content = $label . $fieldData;
		return $this->fieldWrap($content);
	}

	function viewDynData()	{
		$val = explode(',',$this->value);
		$out = '<span class="label">' . $this->title . ': </span>' .
					 '<span class="value">' . $val . '</span>';
		return $out;
	}
	
	function viewEditData()	{
		$errorMsg = $this->createErorMsg();
		$label = $this->createLabel();
		$fieldDescription = $this->viewDescription();
		$fieldFormData = $this->editData();
		$fieldData = $this->dataWrap($fieldDescription . 
																 $fieldFormData);
		$content = $errorMsg .
					 		 $label . 
					 		 $fieldData;
		return $this->fieldWrap($content);
	}
	
	function viewEditDynData()	{
		$errorMsg = $this->createErorMsg();
		$fieldData = $this->dataWrap($this->editDynData());
		$content = $errorMsg .
					 		 $fieldData;
		return $this->fieldWrap($content);
	}
	
	function editDynData()	{
		return $this->editData();
	}
	
	function fieldWrap($output)	{
		$this->stdWrap = '<div id="field_' . $this->name . '" class="field">|</div>';
		$wrappedOutput = $this->cObj->stdWrap($output,
										 		array(
										 			'wrap' => $this->stdWrap
										 		));
		return $wrappedOutput;
	}
	
	function dataWrap($output)	{
		$this->stdWrap = '<div class="field_data">|</div>';
		$wrappedOutput = $this->cObj->stdWrap($output,
										 		array(
										 			'wrap' => $this->stdWrap
										 		));
		return $wrappedOutput;
	}
	
	function csvData()	{
		return $this->value;
	}
	
	function csvDynData()	{
		$val = explode(',',$this->value);
		return $val;
	}
	
	function createLabel($classInfo='',$title='')	{
		
		if (!empty($classInfo)) {
			$cssCass = 'class="' . $classInfo . ' ' . $this->type . '"';
		} else {
			$cssCass = 'class="' . $this->type . '"';
		}
		if (empty($title)) {
			$title = $this->title;
		} 
		if ($this->showRequiredFields && !empty($this->validate)) {
			$title .= '<span class="notify_required">' . $this->requiredSymbol . '</span>';
		}
		$title .= ':';
		if (!empty($this->tooltip_popup) && !empty($this->tooltip)) {
			$title .= '<span class="help" title="' . $this->tooltip . '" data-tooltip="' . $this->tooltip_popup . '"></span>';
		}
		$label =  '<label ' . $cssCass . ' for="' . $this->name . '">' . $title . '</label>';
		return $label;
	}
	
	function createErorMsg()	{
		$msg = '';
		if ($this->error) {
			$msg = '<div class="error">' .$this->errorMsg . '</div>'; 
		}
		return $msg;
	}
	
	function createValidation($additionalClass='')	{
		$required = '';
		if (!empty($this->validate)) {
			$required = ' required="' .  $this->validate . '" ';
		}
		$elemClass = '';
		if (!empty($additionalClass)) {
			$elemClass = $additionalClass;
		}
		if (!empty($this->cssClass)) {
			$elemClass = $this->cssClass . ' ' . $elemClass;
		}
		$classDef = $required . ' class="' .  $elemClass . '" ';
	
		return $classDef;
	}
	
	function validate() {
		$valid = TRUE;
		if (!empty($this->validate)) {
			switch($this->validate) {
			case 'string':
				if (empty($this->value)) {
					$valid = FALSE;
					$this->error = TRUE;
				}
				break;
			default:
				if (empty($this->value)) {
					$valid = FALSE;
					$this->error = TRUE;
				}
			}
		}
		return $valid;
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field.php']);
}

?>
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

require_once(t3lib_extMgm::extPath('fe_management').'view/filter/class.tx_femanagement_view_filter.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */

class tx_femanagement_view_filter_select extends tx_femanagement_view_filter {
var $hideOptionSelectAll;
	public function __construct($name='',$title='',$value='',$data,$toggle=FALSE,$additionalCssClass='',$defaultValue='',$hideOptionSelectAll=FALSE) {
		parent::__construct('select',$name,$title,$value,$data,$toggle,$additionalCssClass,$defaultValue);
		$this->hideOptionSelectAll = $hideOptionSelectAll;
	}
	
	function show()	{
//t3lib_div::debug($this->data,'data');
//t3lib_div::debug($this->value,'value');
		$selectedSet = FALSE;
		$id = $this->getId();
		if (!empty($this->defaultValue)) {
			$default = ' data-default="' . $this->defaultValue . '" ';
		} else {
			$default = '';
		}
		$out = '<label for="' . $id . '">' . $this->title . '</label>
						<select class="selectFilter" id="' . $id . '"' . $default . ' />
						';
		if ($this->value=='all') {
			$selected = ' selected="selected" ';
			$selectedSet = TRUE;
		} else {
			$selected = '';
		}
		if (!$this->hideOptionSelectAll) {
			$out .= '<option ' . $selected . 'value="all">
								Alle anzeigen
								</option>';
		}
		foreach ($this->data as $value=>$text) {
			if (!$selectedSet && $this->value==$value) {
				$selected = ' selected="selected" ';
				$selectedSet = TRUE;
			} else {
				$selected = '';
			}
			
			$out .= '<option ' . $selected . 'value="' . $value . '">' . 
							$text .
							'</option>';
		}
		$out .= '</select>';
		return $out;
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter_select.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter_select.php']);
}

?>
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

class tx_femanagement_view_field_button extends tx_femanagement_view_field {
protected $buttonType;
	public function __construct(&$elements) {
		if (!empty($elements['buttonType'])) {
			$this->buttonType = $elements['buttonType'];
		}
		parent::__construct($elements);
	}
	
	function viewData()	{
		return '';
	}
	
	function csvData()	{
		return '';
	}
	
	function createLabel() {
		return '';
	}
	
	function editData()	{
		if ($this->buttonType=='abort') {
			$cssClass = ' class="cancel" '; 
			$onClick = '<script type="text/javascript">
									$("#' . $this->name . '").click(function() {
										$(".cancel").attr("clicked","true");
									});
									</script>
									';
		} else {
			$cssClass = '';
			$onClick = '';
		}
		if (empty($this->buttonType) || $this->buttonType=='abort') {
			$this->buttonType = 'submit';
		}
		$out = '<input id="' . $this->name . '" name="' . $this->name . '"' . 
					  $cssClass . ' title="' . $this->title . '" type="' . $this->buttonType . '" value="' . $this->value . '" />' .
					  $onClick;
		return $out;
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_button.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_button.php']);
}

?>
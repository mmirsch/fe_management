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
class tx_femanagement_view_field_ajax_feuser_select extends tx_femanagement_view_field_ajax_select {

	var $username;
	var $usernameDisplay;
	
	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['username'])) {
			$this->username = $elements['username'];
		} else {
			$this->username = $GLOBALS['TSFE']->fe_user->user['username'];
		}
		if (!empty($elements['usernameDisplay'])) {
			$this->usernameDisplay = $elements['usernameDisplay'];
		} else {
			$this->usernameDisplay =  $GLOBALS['TSFE']->fe_user->user['name'];
		}
	}
	
	function getDataElemActions()	{
		$elemActions = '';
		$iconPers = '<span data-row="" class="enter_self icon-actions t3-icon-user-admin" title="eingeloggten Benutzer eintragen"></span>';
		$iconDelete = '<span data-row="" class="delete_row icon-actions t3-icon-edit-delete" title="Feld löschen"></span>';
		$elemActions .= $iconPers . $iconDelete;
		$elemActions .= '<script type="text/javascript">
						' . $this->getJsCode() . '
						</script>';
		$elemActions .= parent::getDataElemActions();
		return $elemActions;
	}
	
	function getDynDataElemActions()	{
		$elemActions = '';
		$iconPers = '<span data-row="###numRows###" class="enter_self icon-actions t3-icon-user-admin" title="eingeloggten Benutzer eintragen"></span>';
		$iconDelete = '<span data-row="###numRows###" class="delete_row icon-actions t3-icon-edit-delete" title="Feld löschen"></span>';
		$elemActions .= $iconPers . $iconDelete;
		$elemActions .= parent::getDynDataElemActions();
		return $elemActions;
	}
	
	function getDynJsCode(&$elemCode) {
		$jsCode = $this->getJsCode();
		$jsCode .= parent::getDynJsCode($elemCode);
		return $jsCode;
	}

	function getJsCode() {
		$jsCode = '
			$("#field_' . $this->name . '").delegate(".enter_self","click", function(){
				var index = $(this).attr("data-row");
				$("#uid_' . $this->name . '" + index).val("' . $this->username . '");
				$("#' . $this->name . '" + index).val("' . $this->usernameDisplay . '");
			});
			$("#field_' . $this->name . '").delegate(".delete_row","click", function(){
				var index = $(this).attr("data-row");
				if (index==1 || index=="") {
					$("#uid_' . $this->name . '" + index).val("");
					$("#' . $this->name . '" + index).val("");
				} else {
					$("#row_select_' . $this->name . '" + index).remove();
				}
			});
			';
		return $jsCode;
	}
	
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_ajax_feuser_select.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_ajax_feuser_select.php']);
}

?>
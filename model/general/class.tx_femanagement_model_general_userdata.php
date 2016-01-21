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

class tx_femanagement_model_general_userdata	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'fe_users');
	}

	function initFormFields() {
 		$this->formFields = array(
		);
	}
	
	function selectFields($field,$value,$table,$dbFields) {
		if (empty($value)) {
			return '';
		}
		$configArray['all_pids'] = TRUE;
		$configArray['table'] = $table;
		$configArray['hiddenFieldName'] = 'disable';
		if ($field=="uid") {
			$configArray['sqlFilter'] = $field . '=' . $value;
		} else {
			$configArray['sqlFilter'] = $field . '="' . $value . '"';
		}
		$configArray['fields'] = $dbFields;
		$data = $this->selectData($configArray);
		if (count($data)==1) {
			$wert = $data[0];
		} else {
			$wert = '';
		}
		return $wert;
	}
		
}
?>

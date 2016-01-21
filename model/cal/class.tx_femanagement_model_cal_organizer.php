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

class tx_femanagement_model_cal_organizer	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_cal_organizer');
	}
		
	function initFormFields() {
 		$this->formFields = array(
			'name' => 'name',
			'description' => 'description',
			'street' => 'street',
			'zip' => 'zip',
			'city' => 'city',
			'phone' => 'phone',
			'email' => 'email',
			'link' => 'link',
			'image' => 'image',
			'fe_user_id' => 'fe_user_id',				#fe_user uid für direkte Verknüfung zu einer reelen Person????
		);
	}
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='25') {
		return parent::getList($eingabe,$pid,$limit,'name');
	}	
	
	function getSingle($uid) {
		$sqlSelect = 'SELECT name FROM ' .$this->table;
		$sqlWhere = ' WHERE uid=' . $uid;
		$sqlQuery = $sqlSelect . $sqlWhere;
		$entry = $this->selectSqlData($sqlQuery);
		if (count($entry)==1) {
			return $entry[0]['name'];
		} else {
			return '';
		}
	}
	
	function getFieldData($uid) {
		return parent::selectField($uid,'name');
	}
}
?>

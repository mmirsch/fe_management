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

class tx_femanagement_model_permissions_roles	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_fe_management_permissions_roles');
	}

	function initFormFields() {
 		$this->formFields = array(
				'title' => 'title',
			);
	}
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

	function getSingle($uid) {
		$sqlSelect = 'SELECT title FROM ' .$this->table;
		$sqlWhere = ' WHERE uid=' . $uid;
		$sqlQuery = $sqlSelect . $sqlWhere;
		$entry = $this->selectSqlData($sqlQuery);
		if (count($entry)==1) {
			return $entry[0]['title'];
		} else {
			return '';
		}
	}

	function getRoleId($role) {
		$configArray['fields'] = 'uid';
		$configArray['sqlFilter'] = 'title="' . $role . '"';
		$configArray['all_pids'] = TRUE;
		$data = $this->selectData($configArray);
		if (count($data)>0) {
			return $data[0]['uid'];
		} else {
			return FALSE;
		}	
	}

}
?>

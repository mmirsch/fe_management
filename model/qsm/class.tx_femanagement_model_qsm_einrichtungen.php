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

class tx_femanagement_model_qsm_einrichtungen	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_qsm_einrichtungen');
	}

	function initFormFields() {
 		$this->formFields = array(
 				'title' => 'title',
 				'kuerzel' => 'kuerzel',
 		);
	}
		
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

	function getFieldData($uid) {
		$configArray['fields'] = 'title,kuerzel';
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$configArray['orderBy'] = 'sorting';
		return parent::selectData($configArray);
	}

	function getSelectList($pid) {
		$configArray['pid'] = $pid;
		$configArray['fields'] = 'title,kuerzel';
		$configArray['orderBy'] = 'title,kuerzel';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['kuerzel']] = $elem['title'];
		}
		return $result;
	}
}
?>

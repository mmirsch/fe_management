<?php
/***************************************************************
 *	Copyright notice
*
*	(c) Hochschule Esslingen
*	All rights reserved
*
*	This script is part of the TYPO3 project. The TYPO3 project is
*	free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*
*	The GNU General Public License can be found at
*	http://www.gnu.org/copyleft/gpl.html.
*
*	This script is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
*	GNU General Public License for more details.
*
*	This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_femanagement_model_forschung_einrichtungen extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_forschung_einrichtungen');
	}
	
	function initFormFields() {
 		$this->formFields = array(
 				'title' => 'title',
 				'admins' => 'admins',
 		);
	}
		
	/*
	 * ########################## LISTS ##########################
	*/
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}
	
	function getEinrichtungenList() {
		return parent::getList('','all','','title');
	}
	
	function getEinrichtungenTitles($idList) {
		$titles = array();
		foreach ($idList as $id) {
			$titles[] = $this->getTitle($id);
		}
		return $titles;
	}
	
	function getFieldData($uid) {
		$configArray['fields'] = 'title,admins';
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$configArray['orderBy'] = 'sorting';
		return parent::selectData($configArray);
	}
	
	function getSelectList($pid) {
		$configArray['pid'] = $pid;
		$configArray['fields'] = 'uid,title,admins';
		$configArray['orderBy'] = 'title';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['uid']] = $elem['title'];
		}
		return $result;
	}
	
	function getTitle($uid) {
		$configArray = array();
		$configArray['fields'] = 'title';
		$configArray['sqlFilter'] = 'uid="' . $uid . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['title'];
	}
	
	function gibInstitute() {
		return parent::getList('','','','title');
	}
	
	function gibEinrichtungsId($title) {
		$configArray = array();
		$configArray['fields'] = 'uid';
		$configArray['sqlFilter'] = 'title="' . $title . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['uid'];
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/forschung/class.tx_femanagement_model_forschung_einrichtungen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/forschung/class.tx_femanagement_model_forschung_einrichtungen.php']);
}
?>

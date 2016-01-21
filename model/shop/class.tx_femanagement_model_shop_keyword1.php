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

class tx_femanagement_model_shop_keyword1	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_hebest_keyword1');
	}

	function initFormFields() {
 		$this->formFields = array(
 				'title' => 'title'
			);
		}
		
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		$configArray['orderBy'] = 'title';
		$configArray['fields'] = 'uid,title';
		$configArray['show_hidden'] = TRUE;
		if (empty($pid)) {
			$configArray['pid'] = $this->storagePid;
		} else if ($pid!='all') {
			$configArray['pid'] = $pid;
		} else {
			$configArray['all_pids'] = TRUE;
		}
		if (!empty($eingabe)) {
			$configArray['sqlFilter'] =  $field . ' LIKE "%' . $eingabe . '%"';
		}
		if (!empty($limit)) {
			$configArray['limit'] = $limit;
		}
		$list = $this->selectData($configArray);
		foreach ($list as $entry) {
			$data[$entry['uid']] = $entry['title'];
		}
		return $data;
	}
	
}
?>

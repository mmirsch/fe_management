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

class tx_femanagement_model_shop_unterkategorie	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_hebest_unterkategorie');
	}

	function initFormFields() {
 		$this->formFields = array(
 				'title' => 'title'
			);
		}
		
	function storeFieldData($uid_local,&$categoryData) {
			/*
			 * Vorhandene Einträge löschen
			 */
			$whereDeleteCatMm = 'uid_local=' . $uid_local;
			$res = $this->delete($whereDeleteCatMm,'tx_cal_event_category_mm');
			if (!$res) {
				if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Löschen der Kategorien:', 'fe_managment', 0);
				return FALSE;
			}
			
			$dataCategoryMm = array();
			$sorting = 1;
			foreach ($categoryData as $category) {
				$dataCategoryMm['uid_local'] = $uid_local;
				$dataCategoryMm['uid_foreign'] = $category;
				$dataCategoryMm['sorting'] = $sorting;
				$sorting++;
				$res = $this->insert($dataCategoryMm,'tx_cal_event_category_mm');
				if (!$res) {
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern:', 'fe_managment', 0, $data);
					return FALSE;
				}
			}
		}
/*
 * ########################## LISTS ##########################
 */	
	
	function getCatId($title) {
		$configArray = array();
		$configArray['fields'] = 'uid';
		$configArray['sqlFilter'] = 'title="' . $title . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['uid'];
	}
	
	function getCatIdList($searchString) {
		$configArray = array();
		$configArray['fields'] = 'uid';
		$configArray['sqlFilter'] = 'title LIKE "' . $searchString . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		$resList = array();
		foreach ($data as $idData) {
			$resList[] = $idData['uid'];
		}
		return $resList;
	}
	
	function getCatTitle($catId) {
		$configArray = array();
		$configArray['fields'] = 'title';
		$configArray['sqlFilter'] = 'uid="' . $catId . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['title'];
	}
	
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

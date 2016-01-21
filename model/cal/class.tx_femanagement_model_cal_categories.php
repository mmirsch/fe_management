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

class tx_femanagement_model_cal_categories	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_cal_category');
	}

		function initFormFields() {
 		$this->formFields = array(
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
		$configArray['sqlFilter'] = 'title LIKE "%' . $searchString . '%"';
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
	
	function getCatTitleList($catList) {
		$catTitles = array();
		foreach ($catList as $catId) {
			$catTitles[] = $this->getCatTitle($catId);
		}
		return $catTitles;
	}
	
	function getCatList() {
		return parent::getList('','all','','title');
	}
	
	function getCatListFromIds($catListIds) {
		$catList = array();
		foreach ($catListIds as $catId) {
			$catList[$catId] = $this->getCatTitle($catId);
		}
		return $catList;
	}
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,'all',$limit,'title');
	}
	
	function getMmEventsForCat($uidForeign) {
		return $this->getListMmLocal($uidForeign,'tx_cal_event_category_mm');
	}
	
	function getMmEventsForCatList($uidListForeign) {
		return $this->getListMmLocalList($uidListForeign,'tx_cal_event_category_mm');
	}
	
	function getMmList($uidLocal) {
		$ids = $this->getListMmForeign($uidLocal,'tx_cal_event_category_mm');
		$data = array();
		$configArray = array();
		$configArray['fields'] = 'uid';
		$categories = $this->getList('','','','title');		
		$categoriesList = array();
		foreach ($categories as $uid=>$titel) {
			if (in_array($uid,$ids)) {
				$categoriesList[] = $uid;
			}
		}
		return $categoriesList;		
	}
		
	function getMmTitleList($uidLocal) {
		$ids = $this->getListMmForeign($uidLocal,'tx_cal_event_category_mm');
		$data = array();
		$configArray = array();
		$configArray['fields'] = 'uid';
		$categories = $this->getList('','','','title');		
		$categoriesTitleList = array();
		foreach ($categories as $uid=>$titel) {
			if (in_array($uid,$ids)) {
				$categoriesTitleList[] = $titel;
			}
		}
		return $categoriesTitleList;		
	}
		
}
?>

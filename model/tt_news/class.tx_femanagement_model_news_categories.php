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

class tx_femanagement_model_news_categories	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tt_news_cat');
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
			$res = $this->delete($whereDeleteCatMm,'tt_news_cat_mm');
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
				$res = $this->insert($dataCategoryMm,'tt_news_cat_mm');
				if (!$res) {
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern:', 'fe_managment', 0, $data);
					return FALSE;
				}
			}
			return TRUE;
		}
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe='',$pid='',$limit='') {
		return parent::getList($eingabe,'all',$limit,'title');
	}

  function getCatList() {
    return parent::getList('','all','','title');
  }

  function getCatTitleList($catList) {
    $catTitles = array();
    foreach ($catList as $catId) {
      $catTitles[] = $this->getCatTitle($catId);
    }
    return $catTitles;
  }

  function getCatId($title) {
		$configArray = array();
		$configArray['fields'] = 'uid';
		$configArray['sqlFilter'] = 'title="' . $title . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['uid'];
	}
	
	function getMmEventsForCat($uidForeign) {
		return $this->getListMmLocal($uidForeign,'tt_news_cat_mm');
	}

	function getCatTitle($catId) {
		$configArray = array();
		$configArray['fields'] = 'title';
		$configArray['sqlFilter'] = 'uid="' . $catId . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['title'];
	}
	
	function getMmList($uidLocal) {
		$ids = $this->getListMmForeign($uidLocal,'tt_news_cat_mm');
		$data = array();
		$configArray = array();
		$configArray['fields'] = 'uid';
		$categories = $this->getList();
		$categoriesList = array();
		foreach ($categories as $uid=>$titel) {
			if (in_array($uid,$ids)) {
				$categoriesList[] = $uid;
			}
		}
/*
t3lib_div::debug($ids,"$ids");
t3lib_div::debug($categories,"$ this->getList");
t3lib_div::debug($categoriesList,"$ categoriesList");	
*/	
		return $categoriesList;		
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news_categories.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news_categories.php']);
}
?>
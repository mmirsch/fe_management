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

class tx_femanagement_model_news extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tt_news');
	}
	
	function initFormFields() {
 		$this->formFields = array(
			'pid' => 'pid',
			'title' => 'title',
			'short' => 'short',
			'bodytext' =>'bodytext',
			'datetime' =>'datetime',
 			'starttime' =>'starttime',
 			'endtime' =>'endtime',
 			'archivedate'=>'archivedate',
 			'links' =>'links',
 			'image' =>'image',
 			'imagecaption' =>'imagecaption',
 			'news_files' =>'news_files',
 			'author' =>'author',
 			'keywords'=>'keywords',
 			'author_email'=>'author_email',
 			'tx_hetools_sortierfeld'=>'tx_hetools_sortierfeld',
		);
	}
	
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		/*
		 * Kategorien behandeln
		 */
		if (!empty($dbData['uid'])) {
			$categories = t3lib_div::makeInstance('tx_femanagement_model_news_categories',$piBase,$this->storagePid);
			$formDataNew['category'] = $categories->getMmList($dbData['uid']);
			$configArray['show_hidden'] = 1;
			$configArray['all_pids'] = 1;
			$userId = $this->selectField($dbData['uid'],'fe_cruser_id',$configArray);
			$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
			$feUserdata = $feUserModel->selectFields('uid',$userId,'fe_users','name');
			$formDataNew['fe_cruser_id'] = $feUserdata['name'];
		}
		return $formDataNew;
	}
		
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		/*
		 * Kategorien behandeln
		 */
		if (isset($formData['category'])) {
			$categoryData = $formData['category']->getValue();
			$catCount = count($categoryData);
			$dbData['category'] = $catCount;
		}
		if (empty($uid)) {
			$dbData['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		}
		if (empty($dbData['datetime'])) {
			if (!empty($dbData['starttime'])) {
				$dbData['datetime'] = $dbData['starttime'];
			} else {
				$dbData['datetime'] = time();
			}
			
		}
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		$res = TRUE;
		/*
		 * Kategorien updaten
		 */
		if (is_array($categoryData) && $uidNeu) {
			$model = t3lib_div::makeInstance
			(
				'tx_femanagement_model_news_categories',
				$this->piBase,
				$this->storagePid
			);
			$res = $model->storeFieldData($uidNeu,$categoryData);
		}
		return $uidNeu;
	}

	function getNewsContainerList() {
		$sqlSelect = 'SELECT uid,title FROM pages';
		$sqlWhere = ' WHERE deleted=0 AND hidden=0 AND uid IN
							(SELECT DISTINCT pid FROM tt_news)';
		$sqlOrderBy = ' ORDER BY title';
		$sqlQuery = $sqlSelect . $sqlWhere . $sqlOrderBy;
		$list = $this->selectSqlData($sqlQuery);
		foreach ($list as $entry) {
			$data[$entry['uid']] = $entry['title'];
		}
		return $data;
	}
	
	function loescheSeitencaches($uid) {
		$configArray['all_pids'] = 1;
		$configArray['show_hidden'] = 1;
		$configArray['show_deleted'] = 1;
		$pid = parent::selectField($uid,'pid',$configArray);
		$configArray['table'] = 'pages';
		$tsConfig = parent::selectField($pid,'TSconfig',$configArray);
		if (!empty($tsConfig)) {
			$zeilen = explode("\n",$tsConfig);
			if (count($zeilen)>0) {
				foreach ($zeilen as $zeile) {
					if (strpos($zeile,'TCEMAIN.clearCacheCmd')!==FALSE) {
						$seiten = explode('=',$zeile);
						if (count($seiten)==2) {
							tx_femanagement_lib_util::clearPageCacheContent_pidList($seiten[1]);
						}
					}
				}
			}
		}
	}
	
/*
 * ########################## CONVERT DATA ##########################
 */	
	
	function cleanDataRead($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
			default: 
				$ergebnisDaten[$key] = $value;
			}
		}
		return $ergebnisDaten;
	}
	
	function cleanDataWrite($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
			case 'starttime':
			case 'endtime':
				$day_value = date('d',$value);
				$month_value = date('m',$value);
				$year_value = date('Y',$value);
				$valueNew = mktime(0, 0, 0, $month_value, $day_value, $year_value);
				$ergebnisDaten[$key] = $valueNew;
				break;
			default: 
				$ergebnisDaten[$key] = $value;
			}	
		}
		//$this->sendMail();
		return $ergebnisDaten;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news.php']);
}
?>

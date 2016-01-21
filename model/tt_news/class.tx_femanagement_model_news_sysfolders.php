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

class tx_femanagement_model_news_sysfolders extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tt_news');
	}
	
		function initFormFields() {
 		$this->formFields = array(
			);
		}

	function getList($eingabe,$pid,$limit='25') {
		$sqlSelect = 'SELECT pages.uid,pages.title,parentPage.title as parentTitle FROM pages';
		$sqlJoin = ' INNER JOIN pages as parentPage ON parentPage.uid=pages.pid';
		$sqlWhere = ' WHERE pages.deleted=0 AND pages.hidden=0' . 
//								' AND pages.uid IN (SELECT DISTINCT pid FROM tt_news)' .
								' AND pages.module="news" AND pages.doktype=254';
		if (!empty($eingabe)) {
			$sqlWhere .= ' AND (pages.title LIKE "%' . $eingabe . '%" 
													OR parentPage.title LIKE "%' . $eingabe . '%")';
		}
		$sqlOrderBy = ' ORDER BY parentTitle,pages.title';
		$sqlLimit = ' LIMIT 0,' . $limit;
		$sqlQuery = $sqlSelect . $sqlJoin . $sqlWhere . $sqlOrderBy . $sqlLimit;
		$list = $this->selectSqlData($sqlQuery);
		foreach ($list as $entry) {
			$data[$entry['uid']] = $this->getTitle($entry[uid],$entry['title'],$entry['parentTitle']);
		}
		return $data;
	}
	
	function getSingle($uid) {
		$sqlSelect = 'SELECT pages.uid,pages.title,parentPage.title as parentTitle FROM pages';
		$sqlJoin = ' INNER JOIN pages as parentPage ON parentPage.uid=pages.pid';
		$sqlWhere = ' WHERE pages.uid=' . $uid;
		$sqlQuery = $sqlSelect . $sqlJoin . $sqlWhere;
		$entry = $this->selectSqlData($sqlQuery);
		if (count($entry)==1) {
			return $this->getTitle($entry[0][uid],$entry[0]['title'],$entry[0]['parentTitle']);
		} else {
			return '';
		}
	}
	
	function getTitle($id,$title,$pidTitle) {
		return $pidTitle . ' - ' . $title . ' (' . $id . ')';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news_sysfolders.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/tt_news/class.tx_femanagement_model_news_sysfolders.php']);
}
?>

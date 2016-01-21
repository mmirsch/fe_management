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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_controller_shop_main extends tx_femanagement_controller {
	var $shopId;
	var $shopConfig = array();
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
		$currentSessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
		$this->shopId = tx_femanagement_lib_util::getPageConfig('config.,shopId');
		$this->sortField = tx_femanagement_lib_util::getPageConfig('config.,sortField');
		if (!empty($this->shopId)) {
			$this->shopConfig['filterSearchFields'] = tx_femanagement_lib_util::getPageConfig('config.,filterSearchFields');
			$this->shopConfig['filterFields'] = tx_femanagement_lib_util::getPageConfig('config.,filterFields.');
			$this->shopConfig['showFields'] = tx_femanagement_lib_util::getPageConfig('config.,showFields.');
			$this->shopConfig['cartFields'] = tx_femanagement_lib_util::getPageConfig('config.,cartFields.');
			$this->shopConfig['cartPageId'] = tx_femanagement_lib_util::getPageConfig('config.,cartPageId');
			$this->shopConfig['shopPageId'] = tx_femanagement_lib_util::getPageConfig('config.,shopPageId');
			$this->shopConfig['listview'] = tx_femanagement_lib_util::getPageConfig('config.,listview.');
			$this->shopConfig['singleview'] = tx_femanagement_lib_util::getPageConfig('config.,singleview.');
			$this->shopConfig['limit'] = tx_femanagement_lib_util::getPageConfig('limit');
			$this->shopConfig['herstellerPageId'] = tx_femanagement_lib_util::getPageConfig('config.,hersteller.,pageId');
			$this->shopConfig['lieferantenPageId'] = tx_femanagement_lib_util::getPageConfig('config.,lieferanten.,pageId');
			$this->shopConfig['hideShoppingCart'] = tx_femanagement_lib_util::getPageConfig('config.,hideShoppingCart');
			
			if (!empty($this->shopConfig['singleview']['pageId'])) {
				$this->shopConfig['singleViewPageId'] = $this->shopConfig['singleview']['pageId'];
			} else {
				$this->shopConfig['singleViewPageId'] = $GLOBALS['TSFE']->id;
			}
			$currentSessionData[$this->shopId]['config'] = $this->shopConfig;
			$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($currentSessionData));
			$GLOBALS['TSFE']->fe_user->storeSessionData();							
		} else {
			$get = t3lib_div::_GET();
			$this->shopId = $get['args']['shop_id'];
			$this->shopConfig = $currentSessionData[$this->shopId]['config'];
		}		
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/shop/css/shop.css"/>
		';
		if ($this->shopConfig['singleview']['mode']=='fancybox') {
			$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<script type="text/javascript" src="/fileadmin/res/fancybox/jquery.fancybox.pack.js"></script>
			<link rel="stylesheet" type="text/css" href="/fileadmin/res/fancybox/jquery.fancybox.css"/>
			';
		}
	}
	
	function getListViewFields() {
		return tx_femanagement_lib_util::getFieldList($this->shopConfig['showFields']);
	}
	
	function getLinkPageId($viewName) {
		return $this->shopConfig[$viewName . 'PageId'];
	}


	function initListViewMenu($aktuelleSeite) {
		$artikel = $this->formView->createMenuEntry('Artikel','article');
		$hersteller = $this->formView->createMenuEntry('Hersteller','hersteller');
		$lieferanten = $this->formView->createMenuEntry('Lieferanten','lieferanten');
		$menu = array($artikel,$hersteller,$lieferanten);
		$this->formView->setMenu($menu);
	}

	function showListView($aktuelleSeite) {
		$this->initListViewMenu($aktuelleSeite);
		return parent::showListView($aktuelleSeite);
	}	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/shop/class.tx_femanagement_controller_shop_main.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/shop/class.tx_femanagement_controller_shop_main.php']);
}

?>
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

require_once(t3lib_extMgm::extPath('fe_management') . 'lib/class.tx_femanagement_lib_util.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'lib/shop/class.tx_femanagement_lib_shop.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'view/shop/class.tx_femanagement_view_form_shop_article_cart.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/class.tx_femanagement_model.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/shop/class.tx_femanagement_model_shop_article.php');

/**
 * Plugin 'Cart' for the 'wt_cart' extension.
 *
 * @author	wt_cart Development Team <info@wt-cart.com>
 * @package	TYPO3
 * @subpackage	tx_wtcart_powermail
 */
class tx_femanagement_powermail extends tslib_pibase {
	
	public function getCart($pageId,$shopId) {
		return tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
	}
	
	public function clearShoppingCart($pageId,$shopId) {
		tx_femanagement_lib_shop::clearCart($pageId,$shopId);
	}
	
	public function PM_SubmitBeforeMarkerHook(&$powermail,&$markerArray, &$sessiondata) {
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['config.'];
		$pageId = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['pageId'];
		if (intval($conf['powermailContent.']['uid']) == $powermail->cObj->data['uid']) { 
			$warenKorbHtml = tx_femanagement_lib_shop::gibWarenkorb($pageId);
			$powermail->cObj->data = $powermail->cObj->substituteMarkerInObject(
																	$powermail->cObj->data,
																	array('###POWERMAIL_TYPOSCRIPT_CART###'=>$warenKorbHtml)
																);
		}
	}	/**
	 * Don't show powermail form if session is empty
	 *
	 * @param	string			$content: html content from powermail
	 * @param	array			$piVars: piVars from powermail
	 * @param	object			$pObj: piVars from powermail
	 * @return	void
	 */
	public function PM_MainContentAfterHook($content, $piVars, &$pObj) {
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['config.'];
		$pageId = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['pageId'];
		$piVars = t3lib_div::_GP('tx_powermail_pi1');
		if ($piVars['mailID'] > 0 || 
				$piVars['sendNow'] > 0 ||
				$conf['allowEmptyCart']) {
			return false; // stop
		}
		if (intval($conf['powermailContent.']['uid']) == $pObj->cObj->data['uid']) { // if powermail uid isset and fits to current CE
			$shopId = $conf['shopId'];
			$products = $this->getCart($pageId,$shopId); // get products from session
			if (!is_array($products) || count($products) == 0)
			{ // if there are no products in the session
				$pObj->content = ''; // clear content
			}
		}
	}
	
	public function PM_MandatoryHookBefore($error, &$markerArray, &$sessionfields, &$obj) {
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['config.'];
		$pageId = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['pageId'];
		if (!$conf['allowEmptyCart']) {
			if (intval($conf['powermailContent.']['uid']) == $obj->cObj->data['uid']) { // if powermail uid isset and fits to current CE
				$shopId = $conf['shopId'];
				$products = $this->getCart($pageId,$shopId); // get products from session
				if (!is_array($products) || count($products) == 0) {
					$obj->error = 1;
					$sessionfields['ERROR']['cartmin'][] = 'Fehler: Keine Produkte im Warenkorb';
				}
			}
		}
	}

	public function PM_MandatoryHook($error, &$markerArray, &$innerMarkerArray, &$sessionfields, &$obj)	{
	}

	public function PM_SubmitEmailHook($subpart, &$maildata, &$sessiondata, &$markerArray, $obj) {
	}

	/**
	 * Clear cart after submit
	 *
	 * @param	string			$content: html content from powermail
	 * @param	array			$conf: TypoScript from powermail
	 * @param	array			$session: Values in session
	 * @param	boolean			$ok: if captcha not failed
	 * @param	object			$pObj: Parent object
	 * @return	void
	 */
	public function PM_SubmitLastOneHook($content, $conf, $session, $ok, $pObj) {
		$piVars = t3lib_div::_GPmerged('tx_powermail_pi1');
		$conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['config.'];
		$pageId = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.']['pageId'];
		if ($piVars['mailID'] == $conf['powermailContent.']['uid']) {
			$shopId = $conf['shopId'];
			$this->clearShoppingCart($pageId,$shopId); 
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/hooks/class.tx_femanagement_powermail.php'])
{
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/hooks/class.tx_femanagement_powermail.php']);
}
?>

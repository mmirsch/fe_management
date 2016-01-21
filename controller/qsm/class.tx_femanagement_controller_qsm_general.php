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
class tx_femanagement_controller_qsm_general extends tx_femanagement_controller_general {

	function __construct(&$parent) {
		parent::__construct($parent);
	}
	
	function handleGeneralEvent($application) {
		switch ($application) {
		case "QSM_CREATE":
			$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege',$this->parent,$this->parent->get,$this->parent->settings);
			$content = $controller->handle_new($this->parent->post,$post,$this->parent->get['tx_femanagement']);
			break;
		case "QSM_LIST":
			$controller = '';
			if (!empty($this->parent->get['tx_femanagement']['page'])) {
				if ($this->parent->get['tx_femanagement']['page']=='einrichtungen') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_einrichtungen',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='gremien') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_gremien',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='zeitraeume') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_zeitraeume',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='alleAntraege') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_alle',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='finaAntraege') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_fina',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='gremienAntraege') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_gremien',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='meineAntraege') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_meine',$this->parent,$this->parent->get);
				} else if ($this->parent->get['tx_femanagement']['page']=='verwendung') {
					$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_verwendung',$this->parent,$this->parent->get);
				}
			}
			if (empty($controller)) {
				$controller = t3lib_div::makeInstance('tx_femanagement_controller_qsm_antraege_verwendung',$this->parent,$this->parent->get);
			}
			$content = parent::handleEvent($controller);
			break;
		}
		return $content;
	}

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_general.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/qsm/class.tx_femanagement_controller_qsm_general.php']);
}

?>
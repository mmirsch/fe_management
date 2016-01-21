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

require_once(t3lib_extMgm::extPath('fe_management') . 'view/class.tx_femanagement_view_form_single.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/qsm/class.tx_femanagement_model_qsm_antraege.php');
require_once(t3lib_extMgm::extPath('fe_management') . 'model/qsm/class.tx_femanagement_model_qsm_mittel.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_qsm_antraege_single extends tx_femanagement_view_form_single {

	function __construct(&$piBase,$pid,$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function getBereicheList($value='',$pid) {
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$datenOrig = $bereicheModel->getSelectList($pid);
		$daten = array();
		if (count($exclusive)>0) {
			foreach($datenOrig as $key=>$title) {
				if (in_array($key,$exclusive)) {
					$daten[$key] = $title;
				}
			}
		} else if (count($exclude)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$exclude)) {
					$daten[$key] = $title;
				}
			}
		} else {
			$daten = $datenOrig;
		}
		return $this->getOptionList($daten,$value);
	}
	
	function getEinrichtungenList($value='',$pid) {
		$einrichtungenModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_einrichtungen');
		$datenOrig = $einrichtungenModel->getSelectList($pid);
		$daten = array();
		if (count($exclusive)>0) {
			foreach($datenOrig as $key=>$title) {
				if (in_array($key,$exclusive)) {
					$daten[$key] = $title;
				}
			}
		} else if (count($exclude)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$exclude)) {
					$daten[$key] = $title;
				}
			}
		} else {
			$daten = $datenOrig;
		}
		return $this->getOptionList($daten,$value);
	}
	
	function getZeitraeumeList($value='',$pid) {
		$zeitraeumeModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_zeitraeume');
		$datenOrig = $zeitraeumeModel->getSelectList($pid);
		$daten = array();
		if (count($exclusive)>0) {
			foreach($datenOrig as $key=>$title) {
				if (in_array($key,$exclusive)) {
					$daten[$key] = $title;
				}
			}
		} else if (count($exclude)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$exclude)) {
					$daten[$key] = $title;
				}
			}
		} else {
			$daten = $datenOrig;
		}
		return $this->getOptionList($daten,$value);
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_form_qsm_antraege_single.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_form_qsm_antraege_single.php']);
}

?>
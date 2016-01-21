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

#require_once(t3lib_extMgm::extPath('fe_management').'model/class.tx_femanagement_cal_model.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_fieldset
{
protected $containerList;
protected $title;
protected $wrapClass;

	public function __construct($title='',$wrapClass='') {
		$this->containerList = array();
		$this->title = $title;
		$this->wrapClass = $wrapClass;
	}
	
	function addContainer(&$fieldList,$fieldOnly=FALSE,$wrapClass='')	{
		$container = t3lib_div::makeInstance('tx_femanagement_view_container',
																						 $fieldOnly,
																						 $wrapClass);
		if (is_array($fieldList) && count($fieldList)>0) {
			foreach ($fieldList as $fieldName=>$field) {
				if (empty($field)) {
					t3lib_utility_Debug::debugInPopUpWindow('ACHTUNG: Leeres Feld: "' . $fieldName . '" !');
					exit();
				} else {
					$container->addField($field);
				}
			}
		}
																						 
		$this->containerList[] = $container;
	}
	
	function show($mode)	{
		$out = $this->initElemCode();
		foreach ($this->containerList as $container) {
			$out .= $container->show($mode);
		}
		$out .= $this->exitElemCode();
		return $out;
	}
	
	function initElemCode()	{
		if (!empty($this->wrapClass)) {
			$classCode = ' class="' . $this->wrapClass . '"';
		} else {
			$classCode = '';
		}
		$out = '<fieldset' . $classCode . '>';
		if (!empty($this->title)) {
			$out .= '<legend>' . $this->title . '</legend>';
		}
		return $out;
	}
	
	function exitElemCode()	{
		return '</fieldset>';
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_fieldset.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_fieldset.php']);
}

?>
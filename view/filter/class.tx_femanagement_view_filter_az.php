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

class tx_femanagement_view_filter_az extends tx_femanagement_view_filter {
	
	public function __construct($name='',$title='',$value='',$data='',$toggle=FALSE,$additionalCssClass='') {
		parent::__construct('az',$name,$title,$value,$data,$toggle,$additionalCssClass);
	}
	
	function show()	{
		$buchstabenListe = array('A','B','C','D','E','F','G',
			'H','I','J','K','L','M','N',
			'O','P','Q','R','S','T','U',
			'V','W','X','Y','Z');
		$azList = '';
		foreach ($buchstabenListe as $anfangsBuchstabe) {
			$azList .= '<a id="#char_'. strtolower($anfangsBuchstabe) . '">' . $anfangsBuchstabe . '</a>';
		}
		return '<div class="a-z-filter">' . $azList . '</div>';
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter_az.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter_az.php']);
}

?>
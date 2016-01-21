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

class tx_femanagement_controller_lib_upload {

	public static function handleFileUpload($fieldname,$uploadDir,&$filename,$fieldList='') {

    $ergebnis = FALSE;
		if ($uploadDir[strlen($uploadDir)-1]!='/') {
			$uploadDir = $uploadDir . '/';
		}
		if (empty($fieldList)) {
			$new_filename = $_FILES[$fieldname]['name'];
			$uploaded_filename = $_FILES[$fieldname]['tmp_name'];
		} else {
			$new_filename = $_FILES[$fieldname]['name'][$fieldList['index']][$fieldList['field']];
			$uploaded_filename = $_FILES[$fieldname]['tmp_name'][$fieldList['index']][$fieldList['field']];
		}
		$datZeit = date('Ymd-Gi');
		if ($new_filename!='' && is_uploaded_file($uploaded_filename)) {
 			$new_filename = str_replace('ä', 'ae', $new_filename);
			$new_filename = str_replace('ö', 'oe', $new_filename);
			$new_filename = str_replace('ü', 'ue', $new_filename);
			$new_filename = str_replace('Ä', 'Ae', $new_filename);
			$new_filename = str_replace('Ö', 'Oe', $new_filename);
			$new_filename = str_replace('Ü', 'Ue', $new_filename);
			$new_filename = str_replace('ß', 'ss', $new_filename);
			$new_filename = preg_replace('/([^a-zA-Z0-9_.])/', '', $new_filename);
			$teile = explode('.', $new_filename);
			$name = $teile[0];
			$endung = $teile[count($teile)-1];
			for ($i=1; $i<count($teile)-1;$i++) {
				$name .= '.' . $teile[0];
			}
			$new_filename = $name . '_' . $datZeit;
			$new_filename = preg_replace('/[_]+/', '_', $new_filename);
			$saveFileName = $uploadDir . $new_filename . '.' . $endung;
			if (move_uploaded_file($uploaded_filename, $saveFileName)) {
				chmod($saveFileName,0640);
				$filename = $new_filename . '.' . $endung;
				$ergebnis = TRUE;
			}
		}

		return $ergebnis;
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/email/class.tx_femanagement_controller_lib_upload.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/email/class.tx_femanagement_controller_lib_upload.php']);
}

?>
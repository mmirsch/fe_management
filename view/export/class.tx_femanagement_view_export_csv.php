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

class tx_femanagement_view_export_csv extends tx_femanagement_view_export {

	public function __construct(&$view) {
		parent::__construct('csv',$view);
	}
	
	function createDataExport(&$dataList,$title,$fieldList) {
		if (empty($fieldList)) {
			$fieldList = array_keys($dataList[0]);
		}
		$dateiname = $title . '.csv';
		header("Content-type: text/x-csv");
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename="'.$dateiname.'"');
		header('Pragma: no-cache');
		$nl = chr(13) . chr(10);
		$out = '';
		$titelZeile = array();
		foreach($dataList[0] as $key=>$wert) {
			if (in_array($key,$fieldList)) {
				$titel = $this->view->getDataExportFieldTitle($key);
				if (!empty($titel)) {
					if (is_array($titel)) {
						foreach ($titel as $titelEintrag) {
							$titelZeile[] = '"' . iconv('UTF-8','CP1252',$titelEintrag) . '"';
						}
					} else {
						$titelZeile[] = '"' . iconv('UTF-8','CP1252',$titel) . '"';
					}
				}
			}
		}
		$out .=  join(';', $titelZeile) . $nl;
		foreach($dataList as $eintrag) {
			$zeile = array();
			foreach($eintrag as $key=>$val) {
				if (in_array($key,$fieldList)) {
					$wert = $this->view->formatExportItem($key,$val);
					if (!empty($wert)) {
						if (is_array($wert)) {
							foreach ($wert as $wertEintrag) {
								$wertEintrag = str_replace(array("\r\n", "\n"), '', $wertEintrag);
								$wertEintrag = str_replace('"', '""', $wertEintrag);
								$zeile[] = '"' . iconv('UTF-8','CP1252',$wertEintrag) . '"';
							}
						} else {
							$zeile[] = '"' . iconv('UTF-8','CP1252',$wert) . '"';
						}
					}
				}
			}
			$out .=  join(';', $zeile) . $nl;
		}
		print $out;
		exit();
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php']);
}

?>
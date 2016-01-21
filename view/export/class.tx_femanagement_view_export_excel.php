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
require_once(t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/execdir/phpexcel/PHPExcel.php');
require_once(t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/execdir/phpexcel/PHPExcel/IOFactory.php');


class tx_femanagement_view_export_excel extends tx_femanagement_view_export {

	public function __construct(&$view) {
		parent::__construct('xls',$view);
	}
	
	function createDataExport(&$dataList,$title,$fieldList) {
		if (empty($fieldList)) {
			$fieldList = array_keys($dataList[0]);
		}
		$dateiname = $title . '.xls';
		$nl = chr(13) . chr(10);
		$out = '<table style="border: 1px solid #004666; padding: 2px 4px;" >';
		$titelZeile = array();
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header("Content-type: application/ms-excel");
		header('Content-Disposition: attachment; filename="'.$dateiname.'"');
		header('Pragma: no-cache');
		foreach($dataList[0] as $key=>$wert) {
			if (in_array($key,$fieldList)) {
				$titel = $this->view->getDataExportFieldTitle($key);
				if (!empty($titel)) {
					if (is_array($titel)) {
						foreach ($titel as $titelEintrag) {
							$titelZeile[] = iconv('UTF-8','ISO-8859-1',$titelEintrag);
						}
					} else {
						$titelZeile[] = iconv('UTF-8','ISO-8859-1',$titel);
					}
				}
			}
		}
		$out .=  '<tr><th>' . join('</th><th>', $titelZeile) . '</th></tr>' . $nl;
		foreach($dataList as $eintrag) {
			$zeile = array();
			foreach($eintrag as $key=>$val) {
				if (in_array($key,$fieldList)) {
					$wert = $this->view->formatExportItem($key,$val);
					if (!empty($wert)) {
						if (is_array($wert)) {
							foreach ($wert as $wertEintrag) {
								$wertXls = iconv('UTF-8','ISO-8859-1',$wertEintrag);
								$zeile[] = preg_replace('@<br[^>]*>@siU', "\r\n", $wertXls);
							}
						} else {
							$wertXls = iconv('UTF-8','ISO-8859-1',$wert);
							$zeile[] = preg_replace('@<br[^>]*>@siU', "\r\n", $wertXls);
						}
					}
				}
			}
			$out .=  '<tr><td>' . join('</td><td>', $zeile) . '</td></tr>' . $nl;
		}
		$out .= '</table>';
		$css = '<style>
					 table {
					 	border-collapse: collapse;
					 }
					 table th,
					 table td {
						border: 1px solid #004666;
						padding: 2px 4px; 
					}
		</style>
		';
		$xlsCode = $this->htmlHead($title,$css) .
							 $this->htmlBody($out);
		print $xlsCode;
		exit();
	}

	function htmlHead($title,$css='') {
		$out = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN '.
					 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'  . "\n" .
					 '<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml"'.
					 ' xml:lang="de-DE" lang="de-DE">' . "\n";
		$out .= '<head>' . "\n";
		$out .= '<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />' . "\n";
		$out .= '<title>' . $title . '</title>' . "\n";
		$out .= $css . "\n";
		$out .= '</head>' . "\n";
		return $out;
  }
	
	function htmlBody($body) {
		$out = '<body>' . "\n";
		$out .= $body . "\n";
		$out .= '</body>' . "\n";
		$out .= '</html>'."\n";
		return $out;
	}

	function createDataExportNeu(&$dataList,$exportTitle) {
		$workbook  = t3lib_div::makeInstance('PHPExcel');
		$workbook ->getProperties()->setTitle($exportTitle)->setSubject($exportTitle);
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		
		// das erste worksheet anwaehlen
		$sheet = $workbook ->getActiveSheet();
		$sheet->setTitle('Kalendertermine');
		$spalte = 0;
		foreach($dataList[0] as $key=>$value) {
			$title = $this->view->getDataExportFieldTitle($key);
			if (!empty($title)) {
				if (is_array($title)) {
					foreach ($title as $titelEintrag) {
						$objRichText = new PHPExcel_RichText();
						$titel = $objRichText->createTextRun($titelEintrag);
						$titel->getFont()->setSize('12');
						$titel->getFont()->setName('Arial');
						$sheet->setCellValueByColumnAndRow($spalte, 1, $objRichText);
						$zelle = chr(ord('A') + $spalte) . '1';
						$sheet->getStyle($zelle)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$spalte++;
					}
				} else {
					$objRichText = new PHPExcel_RichText();
					$titel = $objRichText->createTextRun($title);
					$titel->getFont()->setSize('12');
					$titel->getFont()->setName('Arial');
					$sheet->setCellValueByColumnAndRow($spalte, 1, $objRichText);
					$zelle = chr(ord('A') + $spalte) . '1';
					$sheet->getStyle($zelle)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$spalte++;
				}
			}
		}
		for ($zeile=0;$zeile<count($dataList);$zeile++) {
			$spalte=0;
      foreach($dataList[$zeile] as $key=>$value) {
				$wert = $this->view->formatExportItem($key,$value);
				if (!empty($wert)) {
					if (is_array($wert)) {
						foreach ($wert as $wertEintrag) {
							$wertXls = preg_replace('@<br[^>]*>@siU', "\r\n", $wertEintrag);
			      	$sheet->setCellValueByColumnAndRow($spalte, $zeile+2, $wertXls);
							$spalte++;
						}
					} else {
						$wertXls = preg_replace('@<br[^>]*>@siU', "\r\n", $wert);
		      	$sheet->setCellValueByColumnAndRow($spalte, $zeile+2, $wertXls);
						$spalte++;
					}
				}
			}
		}
    $anzSpalten = $spalte;
/*
    for ($spalte=0;$spalte<$anzSpalten;$spalte++) {
//			$sheet->getColumnDimension(chr(ord('A')+ $spalte))->setAutoSize(true);
//			$sheet->getColumnDimension(chr(ord('A')+ $spalte))->setWidth(50);
		}
*/
		$dateiname = $exportTitle . '.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $dateiname . '"');
		header('Cache-Control: max-age=0');
        
		$objWriter = PHPExcel_IOFactory::createWriter($workbook , 'Excel5');
		$objWriter->save('php://output');
		exit();
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php']);
}

?>
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
//require_once(t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/execdir/phpexcel/PHPExcel.php');
//require_once(t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/execdir/phpexcel/PHPExcel/IOFactory.php');
require_once(t3lib_extMgm::extPath('phpexcel_service') . 'class.tx_phpexcel_service.php');
//require_once(t3lib_extMgm::extPath('phpexcel_service') . 'Classes/PHPExcel/RichText.php');
//require_once(t3lib_extMgm::extPath('phpexcel_service') . 'Classes/PHPExcel/Writer/Excel2007.php');


class tx_femanagement_view_export_excel_xls extends tx_femanagement_view_export {

	public function __construct(&$view) {
		parent::__construct('xls',$view);
	}

	function createDataExport(&$dataList,$title,$fieldList) {
    if (empty($fieldList)) {
      $fieldList = array_keys($dataList[0]);
    }
    $dateiname = $title . '.xlsx';


    $phpExcelService =  t3lib_div::makeInstance('tx_phpexcel_service');
    $phpExcel = $phpExcelService->getPHPExcel();

    $phpExcel ->getProperties()->setTitle($title)->setSubject($title);
		PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
		
		// das erste worksheet anwaehlen
		$sheet = $phpExcel ->getActiveSheet();
		$sheet->setTitle('Export');
		$spalte = 0;
		foreach($dataList[0] as $key=>$value) {
      if (in_array($key,$fieldList)) {
        $titel = $this->view->getDataExportFieldTitle($key);
        if (!empty($titel)) {
          if (is_array($titel)) {
            foreach ($titel as $titelEintrag) {
              $objRichText = new PHPExcel_RichText();
              $cellTitle = $objRichText->createTextRun($titelEintrag);
              $cellTitle->getFont()->setSize('12');
              $cellTitle->getFont()->setName('Arial');
              $sheet->setCellValueByColumnAndRow($spalte, 1, $objRichText);
              $zelle = chr(ord('A') + $spalte) . '1';
              $sheet->getStyle($zelle)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $spalte++;
            }
          } else {
            $objRichText = new PHPExcel_RichText();
            $cellTitle = $objRichText->createTextRun($titel);
            $cellTitle->getFont()->setSize('12');
            $cellTitle->getFont()->setName('Arial');
            $sheet->setCellValueByColumnAndRow($spalte, 1, $objRichText);
            $zelle = chr(ord('A') + $spalte) . '1';
            $sheet->getStyle($zelle)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $spalte++;
          }
        }
      }
		}
    $searchLf = array(
      '@<br[^>]*>@siU',
      '@<p[^>]*>@siU',
    );
    $replaceLf = array(
      "\r\n",
      "\r\n\r\n",
    );
    for ($zeile=0;$zeile<count($dataList);$zeile++) {
      $spalte=0;
      foreach($dataList[$zeile] as $key=>$value) {
        $wert = $this->view->formatExportItem($key,$value);
        if (is_array($wert)) {
          foreach ($wert as $wertEintrag) {
            $value = preg_replace($searchLf, $replaceLf, $wertEintrag);
            $value = html_entity_decode($value,ENT_QUOTES,'UTF-8');
            $wertXls = strip_tags($value);
            //$wertXls = preg_replace('@<br[^>]*>@siU', "\r\n", $wertEintrag);
            //$wertXls = iconv('UTF-8','ISO-8859-1',$wertXls);
            $sheet->setCellValueByColumnAndRow($spalte, $zeile+2, $wertXls);
            $zelle = chr(ord('A') + $spalte) . $zeile+2;
            $sheet->getStyle($zelle)->getAlignment()->setWrapText(true);
            $spalte++;
          }
        } else {
          $value = preg_replace($searchLf, $replaceLf, $wert);
          $value = html_entity_decode($value,ENT_QUOTES,'UTF-8');
          $wertXls = strip_tags($value);
//            $wertXls = preg_replace('@<br[^>]*>@siU', "\r\n", $wert);
          //$wertXls = iconv('UTF-8','ISO-8859-1',$wertXls);
          $sheet->setCellValueByColumnAndRow($spalte, $zeile+2, $wertXls);
          $zelle = chr(ord('A') + $spalte) . $zeile+2;
          $sheet->getStyle($zelle)->getAlignment()->setWrapText(true);
          $spalte++;
        }
      }
    }
/*
    $anzSpalten = $spalte;
    for ($spalte=0;$spalte<$anzSpalten;$spalte++) {
			$sheet->getColumnDimension(chr(ord('A')+ $spalte))->setAutoSize(true);
    }
*/
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $dateiname . '"');
		header('Cache-Control: max-age=0');
    $excelWriter = $phpExcelService->getInstanceOf('PHPExcel_Writer_Excel2007', $phpExcel);
//$dateiname = 'uploads/tx_femanagement_forschungsprojekte/forschungsprojekte.xlsx';
    $excelWriter->save('php://output');

//		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel , 'Excel5');
//		$objWriter->save('php://output');
		exit();
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_excel_xls.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_excel_xls.php']);
}

?>
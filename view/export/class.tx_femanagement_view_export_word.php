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

require_once(t3lib_extMgm::extPath('he_tools').'res/phpword/PHPWord.php');
require_once(t3lib_extMgm::extPath('he_tools').'res/phpword/PHPWord/Shared/Font.php');

class tx_femanagement_view_export_word extends tx_femanagement_view_export {

	public function __construct(&$view) {
		parent::__construct('doc',$view);
	}
	
	function createDataExport(&$dataList,$title,$fieldList) {
    $dateiname = $title . '.docx';

    $PHPWord = new PHPWord();
    /*
     * Dokumenteigenschaften
     */
    $properties = $PHPWord->getProperties();
    $properties->setCreator('fe_management (TYPO3-Extension) by Manfred Mirsch - Hochschule Esslingen');
    $properties->setCompany('Hochschule Esslingen');
    $properties->setTitle('Forschungsprojekte der Hochschule Esslingen');
    $properties->setDescription('Export ausgewählter Forschungsprojekte der Hochschule Esslingen');
    $properties->setLastModifiedBy('TYPO3');
    $properties->setCreated(time());
    $properties->setSubject('Forschungsprojekte der Hochschule Esslingen');
    $properties->setKeywords('Forschung, Forschungsprojekte, Hochschule Esslingen');

    $PHPWord->setDefaultFontName('Arial');
    $PHPWord->setDefaultFontSize(11);
    $titleStyle = array('size'=>12, 'bold'=>true);
    $PHPWord->addFontStyle('TitelStil', $titleStyle);
    $normalStyle = array('size'=>11, 'bold'=>false);
    $paragraphStyle = array('spaceAfter'=>60);
    $PHPWord->addParagraphStyle('AbsatzStil', $paragraphStyle);
    
    $tableStyle = array(
    	'borderColor'=>'010101',
      'borderSize'=>6,
      'cellMargin'=>18);
    $PHPWord->addTableStyle('forschungsTabelle', $tableStyle);
    $section = $PHPWord->createSection();

    $PHPWord->addParagraphStyle('AbsatzStil',array('spaceAfter'=>60));
    
    foreach($dataList as $eintrag) {
      $table = $section->addTable('forschungsTabelle');
      $this->view->formatExportItemWord($this, $table,$eintrag,'TitelStil','NormalStil','AbsatzStil');
      $section->addTextBreak();
		}
    header('Content-Type: application/vnd.ms-word');
    header('Content-Disposition: attachment;filename="' . $dateiname . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
    $objWriter->save('php://output');
    exit();
	}
	
 	function stripHtml($text,$encodingFrom='UTF-8',$encodingTo='ISO-8859-1') {
    $searchLf = array(
      '@<br[^>]*>@siU',
      '@<p[^>]*>@siU',
      '@&ndash;@siU',
    	'@–@siU',
    );
    $replaceLf = array(
      "\r\n",
      "\r\n\r\n",
      "-",
      "-",
    );
    $value = html_entity_decode($text,ENT_QUOTES,$encodingFrom);
    $value = preg_replace($searchLf, $replaceLf, $value);
    $value = strip_tags($value);
    $value = trim(iconv($encodingFrom,$encodingTo,$value));
     //t3lib_utility_Debug::debugInPopUpWindow('<pre>' . $value . '</pre>');
    return $value;
  }

 	function handleHtml(&$cell, $text, $parentTag='') {
		$htmlParser = new tx_femanagement_lib_htmlParser($text);
	 	$htmlArray = $htmlParser->toArray();
	 	foreach ($htmlArray as $elem) {
	 		$this->handleHtmlElem($cell, $elem, $parentTag);
	 	}
	}
 
 	function handleHtmlElem(&$cell, $elem, $parentTag) {
 		switch ($elem['tag']) {
			case 'h1':
			case 'h2':
			case 'h3':
			case 'h4':
			case 'h5':
			case 'h6':
				$text = $this->stripHtml($elem['innerHTML']);
				$cell->addText($text, 'TitelStil', 'AbsatzStil');
				break;
			case 'p':
				$text = $this->stripHtml($elem['innerHTML']);
				$cell->addText($text, 'NormalStil', 'AbsatzStil');
				break;
			case 'ul':
			case 'ol':
				if (count($elem['childNodes'])>0) {
					foreach ($elem['childNodes'] as $child) {
						$this->handleHtmlElem($cell, $child, $elem['tag']);
					}
				}
				break;
			case 'li':
				if ($parentTag=='ol') {
					$listStyle = array('listType' => 7);
				} else {
					$listStyle = array('listType' => 3);
				}
				$text = $this->stripHtml($elem['innerHTML']);
				$cell->addListItem($text, 0, 'NormalStil', $listStyle, 'AbsatzStil');
				break;
		}
  }

 	public function addTableRow(&$table, $hoeheZeile) {
		$table->addRow($hoeheZeile);
	}

	public function addTableCell(&$table, $breiteZelle) {
		return $table->addCell($breiteZelle);
 	}

	public function addTableCellWithText(&$table, $breiteZelle, $text, $textStyle='NormalStil', $paragraphStyle='AbsatzStil') {
		if ($textStyle=='title') {
			$textStyle = 'TitelStil';
		} else {
			$textStyle = 'NormalStil';
		}
		$table->addCell($breiteZelle)->addText($text, $textStyle, $paragraphStyle);
	}

	public function addListItem(&$cell, $text) {
		$cell->addListItem($text, 0, 'NormalStil', 3, 'AbsatzStil');
	}
	
	public function addText(&$cell, $text) {
		$cell->addText($text, 'NormalStil', 'AbsatzStil');
	}
	
	public function addImage(&$cell, &$dateiWord, $imageStyle) {
		$cell->addImage($dateiWord, $imageStyle);
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/export/class.tx_femanagement_view_export_csv.php']);
}

?>
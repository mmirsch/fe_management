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
 *
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_form_news_single extends tx_femanagement_view_form_single {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function createFormSingle($formularDaten) {
		return;
		$containermain = array(
								array(
									$formularDaten['title'],
									$formularDaten['short'],
									$formularDaten['bodytext'],
									$formularDaten['starttime'],
									$formularDaten['endtime'],
									$formularDaten['archivedate'],
									$formularDaten['links'],
									$formularDaten['image'],
									$formularDaten['news_files'],
									$formularDaten['category'],
								),
								FALSE,
								'allgemein'
								);						
							
		$containerList = array(
							$containermain
						);
		$this->addFieldset($containerList,'Allgemeine Newsdaten','');

		$buttonContainer = array(
												array(
													$formularDaten['speichern'],
													$formularDaten['abbrechen']
												),
												FALSE,
												'buttons'
												);
		$containerList = array($buttonContainer);
		$this->addFieldset($containerList,'','');
	}
	
	function getCategoriesAjax($data) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories');
		$daten = $calModel->getList($data['args'],$data['pid'],$data['limit']);
		$jsonDaten = array();
		foreach ($daten as $uid=>$titel) {
			$jsonDaten[] = array('value'=>$uid, 'title'=>$titel);
		}
		return $jsonDaten;
	}
	
	function getCategoriesList($value='',$pid,$exclusive=array(),$excludeIds=array()) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories');
		$datenOrig = $calModel->getList('',$pid);
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
	
	function getCategoriesTitles($catList) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories');
		$catTitles = array();
		foreach ($catList as $catId) {
			$catTitles[] = $calModel->getCatTitle($catId);
		}
		return implode('<br/>',$catTitles);
	}
	
	function getNewsContainerList($value='',$pid,$exclusive=array(),$excludeIds=array()) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_news');
		$datenOrig = $calModel->getNewsContainerList();
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form_news_single.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form_news_single.php']);
}

?>
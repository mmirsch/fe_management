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
class tx_femanagement_controller_shop_article extends tx_femanagement_controller_shop_main {
var $shopId;
var $shopConfig = array();

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function handle_view($uid) {
		$viewClassName = 'tx_femanagement_view_form_shop_article_single';
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Artikel',
			'shop_single',
			''
		);
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_shop_article',
			$this->piBase,
			$this->getPid()
		);
		return $this->formView->showSingleData($this->model,$uid);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_shop_article_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_shop_article',
			$this->piBase,
			$this->getPid()
		);
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Artikel',
			'shop_single',
			$this->eidViewHandler
		);
		$this->formView->setControllerName('tx_femanagement_controller_shop_article');																		
		$this->formView->setModelName('tx_femanagement_model_shop_article');																		
	}

	function initListView() {		
		$viewClassName = 'tx_femanagement_view_form_shop_article_list';
		$this->eidViewHandler =  $this->eidUrl . 
														 '&view=' . $viewClassName
														 ;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_shop_article',$this->piBase,$this->getPid());

		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Artikel',
																							'cal_list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_shop_article');																		
		$this->formView->setModelName('tx_femanagement_model_shop_article');																		
	}

	function displayShoppingCart() {
		$displayShoppingCart = TRUE;
		if (isset($this->shopConfig['hideShoppingCart'])) {
			$displayShoppingCart = ! $this->shopConfig['hideShoppingCart'];
		}
		return $displayShoppingCart;
	}

	function showCart() {
		if ($this->displayShoppingCart()) {
			$viewClassName = 'tx_femanagement_view_form_shop_article_cart';
			$this->eidViewHandler =  $this->eidUrl . '&view=tx_femanagement_view_form_shop_article_list';
			$this->model = t3lib_div::makeInstance('tx_femanagement_model_shop_article',$this->piBase,$this->getPid());
			$this->formView = t3lib_div::makeInstance($viewClassName,
					$this->piBase,
					$this->getPid(),
					'Artikel',
					'cal_list',
					$this->eidViewHandler);
			$feldListe = tx_femanagement_lib_util::getFieldList($this->shopConfig['cartFields']);
			return $this->formView->showCart($this->model,$this->shopId,$feldListe);			
		}
		return '';
	}
	
	function initFormSingle(&$formData,$mode) {		
		$fieldSettings['produktname'] = array(
												'title'=>'Titel',
												'type'=>'input',
												'validate'=>'string',
												);
		$fieldSettings['artikelnummer'] = array(
												'title'=>'Artikelnummer',
												'type'=>'readonly',
												'validate'=>'string',
												);
		$fieldSettings['bild'] = array(
												'title'=>'Bild',
												'type'=>'file',
												 'upload_dir'=>'uploads/tx_hebest/',
												 'filetyp' => 'img',
												 'width' => '200px',
													);
		$fieldSettings['save'] = array(
										 'value'=>'Artikel speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
			);
		$fieldSettings['abort'] = array(
										 'value'=>'Abbrechen',
										 'type'=>'button',
										 'buttonType'=>'abort',
										);
		$formData = $this->createFormFields($fieldSettings);
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {		
		$titelFelder = array('produktname','artikelnummer','bild');
		$containerTitel = $this->createContainer($titelFelder,$formData);
		$containerList = array(
							$containerTitel,
						);
		$this->formView->addFieldset($containerList);	
		$buttonFelder = array('save','abort');	
		$containerButtons = $this->createContainer($buttonFelder,$formData,FALSE,'buttons');
		$containerList = array($containerButtons);						
		$this->formView->addFieldset($containerList,'','');						
	}
		
	function showListView($aktuelleSeite) {
//		parent::initListViewMenu($aktuelleSeite);
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		$buttonListe = array();
		$index = 10;
		foreach ($this->shopConfig['filterFields'] as $field=>$title) {
			switch ($field) {
				case 'az':
					$filterListe[$index] = $this->formView->createFilter('hidden','az','','','','all');
					$index++;
					break;
				case 'volltextsuche':
					$filterListe[$index] = $this->formView->createFilter('search','volltextsuche',$title,'');
					$index++;
					break;
				case 'hauptkategorie':
					$hauptkategorien = $this->formView->gibHauptkategorien();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$hauptkategorien);
					$index++;
					break;
				case 'unterkategorie':
					$unterkategorien = $this->formView->gibUnterkategorien();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$unterkategorien);
					$index++;
					break;
				case 'hersteller':
					$hersteller = $this->formView->gibHersteller();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$hersteller);
					$index++;
					break;
				case 'lieferanten':
					$lieferanten = $this->formView->gibHersteller();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$lieferanten);
					$index++;
					break;
				case 'eigenschaft1':
					$eigenschaft1 = $this->formView->gibEigenschaft1();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$eigenschaft1);
					$index++;
					break;
				case 'eigenschaft2':
					$eigenschaft2 = $this->formView->gibEigenschaft2();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,'',$eigenschaft2);
					$index++;
					break;
			}
		}

		if ($this->isAdmin()) {		
		}
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,$this->shopConfig['limit'],TRUE);
		$filterListe[31] = $this->formView->createFilter('reset','reset','Formular zurücksetzen','Alle Formularfilter (Volltextsuche, Kategorien etc.) zurücksetzen');
		$filterListe[100] = $this->formView->createFilter('hidden','sortField','','','',$this->sortField);
		$filterListe[101] = $this->formView->createFilter('hidden','sortMode','','','','ASC');
		$filterListe[102] = $this->formView->createFilter('hidden','page','','','','0');
		$filterListe[103] = $this->formView->createFilter('hidden','page_id','','','',$this->pageId);
		$filterListe[104] = $this->formView->createFilter('hidden','hidden','','','','0');
		$filterListe[105] = $this->formView->createFilter('hidden','deleted','','','','0');
		$filterListe[999] = $this->formView->createFilter('hidden','shop_id','','','',$this->shopId);
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite,array('shopId'=>$this->shopId));
	}
	
	function getSearchFields() {
		return $this->shopConfig['filterSearchFields'];
	}
	
	function getSinglePageConfig() {
		return array('pageId' => $this->shopConfig['singleViewPageId'],
								 'mode' => $this->shopConfig['singleview']['mode']);
	}
	
	function createPreviewLink($uid,$startDate,$id='') {
		if (empty($id)) {
			//t3lib_div::debug($this->piBase->settings,'settings');
			//$id = $this->piBase->settings['CAL_PREVIEW_PID'];
			$id = '93355';
		}
		$dateString = date('Y-m-d',$startDate);
		sscanf($dateString, "%4d-%02d-%02d", $jahr, $monat, $tag);
		$additionalParams = '&tx_cal_controller%5Bview%5D=event&tx_cal_controller%5Btype%5D=tx_cal_phpicalendar&tx_cal_controller%5Buid%5D=' . $uid . '&tx_cal_controller%5Byear%5D=' . $jahr . '&tx_cal_controller%5Bmonth%5D=' . $monat . '&tx_cal_controller%5Bday%5D=' . $tag;
		return 'http://www.hs-esslingen.de/index.php?id=' . $id . $additionalParams;
	}
		
	function postProcessingSaveForm($calUid,&$formData,$mode) {	
		return;
		$kategorien = $formData['category']->getValue();
		
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
		$daten = $catModel->getList('',$this->getPid());
		$kategorieTitelListe = array();
		foreach ($daten as $uid=>$titel) {
			if (in_array($uid,$kategorien)) {
				$kategorieTitelListe[$uid] = $titel;
			}
		}
		if (count($kategorieTitelListe)<=1) {
			$kategorieTitel = '"' . implode('", "',$kategorieTitelListe) . '"';
		} else {
			$kategorieTitel = '"' . $kategorieTitelListe[0] . '"';
			for ($i=1;$i<count($kategorieTitelListe)-1;$i++) {
				$kategorieTitel .= ', "' . $kategorieTitelListe[$i] . '"';
			}
			$kategorieTitel .= ' und "' . $kategorieTitelListe[count($kategorieTitelListe)-1] . '"';
		}
		$title = $formData['title']->getValue();
		$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');

		switch ($mode) {
		case 'new':
/*
 * E-Mails nur versenden, wenn User nicht Admin ist
 */			
			if (!parent::isAdmin()) {
			
/*
 * E-Mail an Absender
 */
			
				$userEmail = $GLOBALS['TSFE']->fe_user->user[email];
				$userName = $GLOBALS['TSFE']->fe_user->user[name];
				if (empty($userEmail)) {
					$userEmail = $GLOBALS['TSFE']->fe_user->user[username] . '@hs-esslingen.de';
				}
				$email->setFrom('hochschulkalender@hs-esslingen.de','Hochschulkalender');
				$email->setTo($userEmail,$userName);
				$email->setSubject('Kalendereintrag gespeichert');
				$email->setBodyHtml('<h3>Neuer Kalendereintrag</h3>' . 
													 '<p>Sehr geehrte/r ' . $userName . ',<br/>' . 
													 'Ihr Kalender-Eintrag "' . $title . '" wurde gespeichert,</p>' . 
													 '<p>Vielen Dank für die Eingabe des Termins <b>"' . $title . '"</b> in den Hochschulkalender.</p>' .
													 '<p>Über die Freigabe zur Veröffentlichung des Termins werden Sie automatisch in einer weiteren E-Mail informiert.<br/>' .
													 'Sollten sich Änderungen an diesem Termin ergeben, wenden Sie sich bitte an <a href="http://www.hs-esslingen.de/de/mitarbeiter/simona-ozimic.html">Frau Ozimic</a>.</p>' .
													 '<p>Gerne stehen wir Ihnen für Rückfragen zur Verfügung.</p>' .
													 '<p>Mit freundlichen Grüßen<br/>' . 
													 'Das RÖM-Team</p>');
				
				$erg = $email->sendEmail();
/*
 * E-Mail an Admin
 */		
				$email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
				$email2->setSubject('Neuer Kalendereintrag');
				$email2->setFrom($userEmail,$userName);
//				$email2->setTo('mmirsch@hs-esslingen.de','Manfred Mirsch');
				$email2->setTo('t3admin@hs-esslingen.de','TYPO3 Admins');
				$email2->addTo('sabine.pfeiffer@hs-esslingen.de','Sabine Pfeiffer');
				$email2->addTo('simona.ozimic@hs-esslingen.de','Simona Ozimic');
				$link = 'https://www.hs-esslingen.de/index.php?id=94586&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $calUid;
				$email2->setBodyHtml('<h3>Neuer Kalendereintrag</h3>' . 
													 'Es wurde ein neuer Kalendertermin eingereicht:</p>' . 
													 '<p>Zur Bearbeitung: <a href="' . $link . '">' . $title . '</a></p>');
				$erg = $email2->sendEmail();
			}
			break;
		case 'publish':
			$calModel = t3lib_div::makeInstance('tx_femanagement_model_shop_article');
			$configArray['show_hidden'] = 1;
			$configArray['all_pids'] = 1;
			
			$userId = $calModel->selectField($calUid,'fe_cruser_id',$configArray);
			
			$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
			$feUserdata = $feUserModel->selectFields('uid',$userId,'fe_users','name,email,username');
			$userEmail = $feUserdata['email'];
			if (empty($userEmail)) {
				$userEmail = $feUserdata['username'] . '@hs-esslingen.de';
			}
			$userName = $feUserdata['name'];
			$email->setFrom('hochschulkalender@hs-esslingen.de','Hochschulkalender');
			$email->setTo($userEmail,$userName);
			$link = 'https://www.hs-esslingen.de/index.php?id=94586&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $calUid;
			
			$startDate = $calModel->selectField($calUid,'start_date',$configArray);
			$linkUrl = $this->createPreviewLink($calUid,$startDate);
			$email->setSubject('Kalendereintrag freigeschaltet');
			$email->setBodyHtml('<h3>Kalendereintrag freigeschaltet</h3>' . 
												 '<p>Sehr geehrte/r ' . $userName . ',<br/>' . 
												 'Der von Ihnen eingegebene Termin <b>"' . $title . '"</b> wurde zur Veröffentlichung in den Kategorien <b>' . $kategorieTitel . '</b> frei gegeben.</p>' .
												 '<p>Vorschau: <a href="' . $linkUrl . '">' . $title . '</a></p>' .
												 '<p>Sollten sich Änderungen an diesem Termin ergeben, wenden Sie sich bitte an <a href="http://www.hs-esslingen.de/de/mitarbeiter/simona-ozimic.html">Frau Ozimic</a>.<br/><br/>' .
												 'Bitte beachten Sie dabei bei den Terminen für die Printversion des Hochschulkalenders auf den Redaktionsschluss.</p>' . 
												 'Gerne stehen wir Ihnen für Rückfragen zur Verfügung.<br/>' .
												 '<p>Mit freundlichen Grüßen<br/><br/>' . 
												 'Das RÖM-Team</p>');
			
			$erg = $email->sendEmail();
			break;
		case 'edit':
			break;
		}
		$this->postProcessingDataChange($calUid);
	}
	
	function postProcessingDataChange($calUid) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_shop_article');
		$calModel->aktualisiereSeitenTstampInfoscreen($calUid);
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/shop/class.tx_femanagement_controller_shop_article.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/shop/class.tx_femanagement_controller_shop_article.php']);
}

?>
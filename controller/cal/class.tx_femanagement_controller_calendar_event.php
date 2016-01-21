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
class tx_femanagement_controller_calendar_event extends tx_femanagement_controller_calendar_main {
	protected $calMode;
	
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_form_cal_event_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_cal_event',
			$this->piBase,
			$this->getPid()
		);
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Kalendereintrag',
			'cal_single',
			$this->eidViewHandler
		);
		$this->formView->setControllerName('tx_femanagement_controller_calendar_event');																		
		$this->formView->setModelName('tx_femanagement_model_cal_event');	
		$calMode = tx_femanagement_lib_util::getPageConfig('calMode');
		if (empty($calMode)) {
			$calMode = 'HSK';
		}			
		$this->calMode = $calMode;
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_form_cal_event_list';
		$this->eidViewHandler =  $this->eidUrl . 
														 '&view=' . $viewClassName
														 ;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_cal_event',$this->piBase,$this->piBase->settings['STORAGE_PID']);

		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Kalendereinträge',
																							'cal_list',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_calendar_event');																		
		$this->formView->setModelName('tx_femanagement_model_cal_event');																		
		$calMode = tx_femanagement_lib_util::getPageConfig('calMode');
		if (empty($calMode)) {
			$calMode = 'HSK';
		}			
		$this->calMode = $calMode;	
	}

	function initFormSingle(&$formData,$mode) {		
		$isAdmin = parent::isAdmin();
		$fieldSettings['calendar_id'] = array(
												'type'=>'hidden',
												'prefill'=> $this->piBase->settings['CALENDAR_ID'],
												);
		$fieldSettings['title'] = array(
												'title'=>'Titel',
												'type'=>'input',
												'validate'=>'required',
												);
		$fieldSettings['tx_femanagement_cal_title_infoscreen'] = array(
												'title'=>'Titel für die Infoscreen-Anzeige (wird nur auf den Infoscreens angezeigt)',
												'type'=>'input',
												);
		if ($mode=='new') {
			$fieldSettings['beschreibung'] = array(
													'title'=>'Beschreibung',
													'type'=>'rte',
													'validate'=>'required',
													'admin'=>$isAdmin,
													);
			$fieldSettings['referent'] = array(
													'title'=>'Referent/in',
													'type'=>'rte',
													'validate'=>'required',
													'admin'=>$isAdmin,
													);
			$fieldSettings['zielgruppe'] = array(
													'title'=>'Zielgruppe',
													'type'=>'rte',
													'validate'=>'required',
													'admin'=>$isAdmin,
													);
			$fieldSettings['link'] = array(
													'title'=>'Link',
													'type'=>'input',
													);
		}	else {
			$fieldSettings['description'] = array(
													'title'=>'Beschreibung',
													'type'=>'rte',
													'validate'=>'required',
													'prefill'=>'<b>Beschreibung:</b>&nbsp;<br/><b>Referent/in:</b>&nbsp;<br/><b>Zielgruppe:</b>&nbsp;<br/><b>Link:</b>&nbsp;',
													'admin'=>$isAdmin,
													);
		}											
		$fieldSettings['allday'] = array(
												'title'=>'ganztägig',
												'type'=>'checkbox',
												);
		$fieldSettings['start_date'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_STARTDATE_LABEL'),
												'type'=>'date',
												'icons'=>array('delete'=>1),
												'validate'=>'required',
												);
		$fieldSettings['end_date'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_ENDDATE_LABEL'),
												'type'=>'date',
												'icons'=>array('delete'=>1),
												);

		$fieldSettings['start_time'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_STARTTIME_LABEL'),
												'type'=>'time',
												'icons'=>array('delete'=>1),
												'validate'=>'required',
												'label_hour' => $this->piBase->pi_getLL('TIME_SELECTOR_HOUR'),
												'label_minute' => $this->piBase->pi_getLL('TIME_SELECTOR_MINUTE'),
												'label_now' => $this->piBase->pi_getLL('TIME_SELECTOR_NOW'),
												'label_done' => $this->piBase->pi_getLL('TIME_SELECTOR_DONE'),
												'TIME_SELECTOR_INSERT_TIME' => $this->piBase->pi_getLL('TIME_SELECTOR_INSERT_TIME'),
												'TIME_SELECTOR_TIME' => $this->piBase->pi_getLL('TIME_SELECTOR_TIME'),
												'TIMEPICKER_INTERVALL' => $this->piBase->settings['TIMEPICKER_INTERVALL'],
												'TIMEPICKER_EARLIEST_HOUR' => $this->piBase->settings['TIMEPICKER_EARLIEST_HOUR'],
												'TIMEPICKER_LATEST_HOUR' => $this->piBase->settings['TIMEPICKER_LATEST_HOUR'],
												'TIMEPICKER_SHOW_NOW' => true,
												'TIMEPICKER_SHOW_CLOSE' => true,
												);
		$fieldSettings['end_time'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_ENDTIME_LABEL'),
												'type'=>'time',
												'validate'=>'required',
												'icons'=>array('delete'=>1),
												'label_hour' => $this->piBase->pi_getLL('TIME_SELECTOR_HOUR'),
												'label_minute' => $this->piBase->pi_getLL('TIME_SELECTOR_MINUTE'),
												'label_now' => $this->piBase->pi_getLL('TIME_SELECTOR_NOW'),
												'label_done' => $this->piBase->pi_getLL('TIME_SELECTOR_DONE'),
												'TIME_SELECTOR_INSERT_TIME' => $this->piBase->pi_getLL('TIME_SELECTOR_INSERT_TIME'),
												'TIME_SELECTOR_TIME' => $this->piBase->pi_getLL('TIME_SELECTOR_TIME'),
												'TIMEPICKER_INTERVALL' => $this->piBase->settings['TIMEPICKER_INTERVALL'],
												'TIMEPICKER_EARLIEST_HOUR' => $this->piBase->settings['TIMEPICKER_EARLIEST_HOUR'],
												'TIMEPICKER_LATEST_HOUR' => $this->piBase->settings['TIMEPICKER_LATEST_HOUR'],
                        'TIMEPICKER_SHOW_NOW' => true,
                        'TIMEPICKER_SHOW_CLOSE' => true,
												);
		$fieldSettings['ext_url'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_LINK_LABEL'),
												'type'=>'input',
												);

		$fieldSettings['attachment'] = array(
												'title'=>$this->piBase->pi_getLL('CAL_FILE_LABEL'),
												'type'=>'file',
//												 'accept'=>'application/pdf, application/msword',
												 'upload_dir'=>'uploads/tx_cal/media/',
												 'filetyp' => 'all',
													);
		
		
		
		$dataProviderCategories = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
		$kategorienCallbacks = array (
				'php' => array('object'=>$dataProviderCategories,
						'method'=>'getCatList',
						'getTitles' => 'getCatTitleList',
						'pid'=>$this->getPid(),
				),
		);
		$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		
		$domainCampusLeben = $domainModel->getDomainId('Campus Leben');
		$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
		if ($this->calMode=='Infoscreen') {
			$fieldSettings['description'] = array(
					'title'=>'Beschreibung',
					'type'=>'rte',
			);
			$catListAll = $calModel->getCatIdList('%');
			$catListInfoscreen = $calModel->getCatIdList('Infoscreen%');
			$catListKeep = array();
			foreach ($catListAll as $cat) {
				if (!in_array($cat,$catListInfoscreen)) {
					$catListKeep[] = $cat;
				}
			}
			$fieldSettings['category'] = array(
					'title'=>'Infoscreen Kategorien (Mehrfachnennungen sind möglich)',
					'type'=>'multiselect',
					'callbacks'=>$kategorienCallbacks,
					'selectedElems'=>'ausgewählte Kategorien',
					'linkTitle'=>$this->piBase->pi_getLL('CAL_CREATE_NEW_CATEGORY'),
					'validate'=>'required',
					'dontRemove' => $catListKeep,
					'exclusive_new_ids' => $catListInfoscreen,
					'options'=>array('searchable'=>TRUE,'height'=>200),
			);
			
			unset($fieldSettings['beschreibung']);
			unset($fieldSettings['referent']);
			unset($fieldSettings['zielgruppe']);
			unset($fieldSettings['link']);
			unset($fieldSettings['ext_url']);
			unset($fieldSettings['attachment']);
		} else if (parent::isAdmin()) {
			$fieldSettings['category'] = array(
					'title'=>'Kategorien (Mehrfachnennungen sind möglich)',
					'type'=>'multiselect',
					'callbacks'=>$kategorienCallbacks,
					'selectedElems'=>'ausgewählte Kategorien',
					'validate'=>'required', 
					'options'=>array('searchable'=>TRUE,'height'=>200),
			);
		} else if (parent::isEditor()) {
			$catSemestertermin = $calModel->getCatId('Semestertermin');
			$catHochschule = $calModel->getCatId('Hochschule');
//			$catListInfoscreen = $calModel->getCatIdList('Infoscreen%');
			$excludeCats = array_merge(array($catHochschule,$catSemestertermin));
			$fieldSettings['category'] = array(
					'title'=>'Kategorien (Mehrfachnennungen sind möglich)',
					'type'=>'multiselect',
					'callbacks'=>$kategorienCallbacks,
					'selectedElems'=>'ausgewählte Kategorien',
					'exclude'=>$excludeCats,
					'validate'=>'required', 
					'options'=>array('searchable'=>TRUE,'height'=>200),
			);
			$catHochschulKalender = $calModel->getCatId('Hochschulkalender');
			$catHochschulKalenderPrint = $calModel->getCatId('Hochschulkalender (Print-Version)');
			$fieldSettings['category']['mandatory'] = array($catHochschulKalender);
			$fieldSettings['category']['preselect'] = array($catHochschulKalenderPrint);
// Campus-Leben
		} else if (parent::isDomainEditor($domainCampusLeben)) {
			$catCampusLeben = $calModel->getCatId('Campus_Leben');
			$fieldSettings['category'] = array(
													'title'=>'Kategorien (Mehrfachnennungen sind möglich)',
													'type'=>'multiselect',
													'callbacks'=>$kategorienCallbacks,
													'selectedElems'=>'ausgewählte Kategorien',
													'linkTitle'=>$this->piBase->pi_getLL('CAL_CREATE_NEW_CATEGORY'),
													'validate'=>'required', 
													'exclusive_ids'=>array($catCampusLeben),
			);
			$fieldSettings['category']['mandatory'] = array($catCampusLeben);
/*
		} else if ($this->isDomainReviser($domainInfoscreen)) {
			$fieldSettings['description'] = array(
													'title'=>'Beschreibung',
													'type'=>'rte',
													);
			$catInfoscreenStadtmitte = $calModel->getCatId('Infoscreen Stadtmitte');
			$catInfoscreenFlandernstrasse = $calModel->getCatId('Infoscreen Flandernstrasse');
			$catInfoscreenGoeppingen = $calModel->getCatId('Infoscreen Goeppingen');
			$kategorienCallbacks = array
			(
					'php' => array('object'=>$dataProviderCategories,
							'method'=>'getCategoriesList',
							'getTitles' => 'getCategoriesTitles',
							'pid'=>$this->getPid(),
					),
			);
			$fieldSettings['category'] = array(
													'title'=>'Infoscreen Kategorien (Mehrfachnennungen sind möglich)',
													'type'=>'multiselect',
													'callbacks'=>$kategorienCallbacks,
													'selectedElems'=>'ausgewählte Kategorien',
													'linkTitle'=>$this->piBase->pi_getLL('CAL_CREATE_NEW_CATEGORY'),
													'validate'=>'required', 
													'exclusive_ids'=>array($catInfoscreenStadtmitte,$catInfoscreenGoeppingen,$catInfoscreenFlandernstrasse),
			);
*/
		}
		$fieldSettings['category']['mandatoryMsg'] = 'Diese Kategorie kann nicht entfernt werden!';
		
		$fieldSettings['location_id'] = array(
										 'title'=>'Veranstaltungsort (falls nicht bekannt bitte den Standort auswählen)',
										 'type'=>'ajax_select',
										 'model'=>'tx_femanagement_model_cal_location',
										 'minLength'=>3,
										 'linkTitle'=>$this->piBase->pi_getLL('CAL_CREATE_NEW_LOCATION'),
										 'validate'=>'required', 
									);
		if (parent::isAdmin()) {												
			$fieldSettings['location_id']['title'] = 'Veranstaltungsort';			
			$linkConf = array(
										'parameter'=>$this->piBase->settings['CAL_CREATE_LOCATION_PID'],
										'additionalParams' => '&tx_femanagement[mode]=new&popup=1&norefresh=1',
			);					
			$urlNewLocation = $this->piBase->cObj->typoLink_URL($linkConf);
			$fieldSettings['location_id']['urlNew'] = $urlNewLocation;
		} else {
			$fieldSettings['location_id']['title'] = 'Veranstaltungsort <br/><i>Sollte der Veranstaltungsort nicht angelegt sein, teilen Sie uns dies bitte mit (<a href="mailto:simona.ozimic@hs-esslingen.de">simona.ozimic@hs-esslingen.de</a>)</i>';			
		}
		$linkConf = array(
									'parameter'=>$this->piBase->settings['CAL_CREATE_ORGANIZER_PID'],
									'additionalParams' => '&tx_femanagement[mode]=new&popup=1&norefresh=1',
			);					
		$urlNewOrganizer = $this->piBase->cObj->typoLink_URL($linkConf);
		$fieldSettings['organizer_id'] = array(
										 'title'=>'Veranstalter',
										 'type'=>'ajax_select',
										 'model'=>'tx_femanagement_model_cal_organizer',
										 'urlNew'=>$urlNewOrganizer,
										 'minLength'=>3,
										 'linkTitle'=>$this->piBase->pi_getLL('CAL_CREATE_NEW_ORGANIZER'),
										);
		$fieldSettings['save'] = array(
										 'value'=>'Kalendertermin speichern',
										 'type'=>'button',
										 'buttonType'=>'submit',
			);
		$fieldSettings['abort'] = array(
										 'value'=>'Abbrechen',
										 'type'=>'button',
										 'buttonType'=>'abort',
										);
		if (parent::isAdmin()) {
			$fieldSettings['publish'] = array(
											 'value'=>'Termin freischalten',
											 'type'=>'button',
										 	 'buttonType'=>'submit',
											);
			$fieldSettings['fe_cruser_id'] = array(
											 	'title'=>'Termin eingereicht von',
												'type'=>'readonly',
												'prefill'=>'keine Person angelegt',
											);
		} else {
			$fieldSettings['location_id']['coords'] = array(50,50,600,300);
			$fieldSettings['organizer_id']['coords'] = array(50,50,600,300);
		}
		$formData = $this->createFormFields($fieldSettings);
		$this->validationDependencies = array(
																		'allday' => array(
																				'click' => array(
																						array(
																							'condition' => '$(elem).attr("checked") == "checked"',
																							'actions' => array(
																								'valid' => array('start_time','end_time'),
																								'hide' => array('field_start_time','field_end_time'),
																							),
																						),
																						array(
																							'condition' => '$(elem).attr("checked") != "checked"',
																							'actions' => array(
																								'required' => array('start_time','end_time'),
																								'show' => array('field_start_time','field_end_time'),
																							),
																						),
																				),
																			),
																		);
		$this->formView->setValidationDependencies($this->validationDependencies);		
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {	
		if ($mode=='new') {
				$titelFelder = array('calendar_id','title','tx_femanagement_cal_title_infoscreen','beschreibung','referent','zielgruppe','link');
		} else  {
				$titelFelder = array('calendar_id','title','tx_femanagement_cal_title_infoscreen','description');						
		}
		
		$startDatumFelder = array('start_date','start_time');
		$endDatumFelder = array('end_date','end_time');
		$datumMiscFelder = array('allday');
		$weitereFelder = array('category','location_id','organizer_id',
													 'attachment');
		if (parent::isAdmin() && !empty($parameter['uid'])) {
			$weitereFelder[] = 'fe_cruser_id';
		}
		if ($this->calMode=='Infoscreen') {
			$titelFelder = array('calendar_id','title','description');
			$weitereFelder = array('category','location_id','organizer_id');
		}
		$containerTitel = $this->createContainer($titelFelder,$formData);
		$containerStartDatum = $this->createContainer($startDatumFelder,$formData,FALSE,'container_col2');
		$containerEndDatum = $this->createContainer($endDatumFelder,$formData,FALSE,'container_col2');
		$containerDatumMisc = $this->createContainer($datumMiscFelder,$formData);
		$containerWeiteres = $this->createContainer($weitereFelder,$formData);
		$containerList = array(
							$containerTitel,
							$containerStartDatum,
							$containerEndDatum,
							$containerDatumMisc,
							$containerWeiteres
						);
		$this->formView->addFieldset($containerList);	
		$buttonFelder = array('save','abort');	
		if (parent::isAdmin()) {
			if (isset($parameter['hidden'])) {
				if ($parameter['hidden']==1) {
					$buttonFelder = array('save','publish','abort');
				}
			}
		}
		$containerButtons = $this->createContainer($buttonFelder,$formData,FALSE,'buttons');
		$containerList = array($containerButtons);						
		$this->formView->addFieldset($containerList,'','');						
	}
		
	function showListView($aktuelleSeite) {
		parent::initListViewMenu($aktuelleSeite);
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$buttonListe = array($this->formView->createButton('newElem',$this->params));		
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$calCatModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
				
		if ($this->calMode=='Infoscreen') {
			$catList = $calCatModel->getCatIdList('Infoscreen%');
		} else {
			$catList = $calCatModel->getCatIdList('%');
		}
		$catData = $calCatModel->getCatListFromIds($catList);
		$filterListe[20] = $this->formView->createFilter('select','cat','Kategorie',$sessionDaten,$catData);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[21] = $this->formView->createFilter('date','dateStart','von',$sessionDaten,'','',TRUE);
		$filterListe[22] = $this->formView->createFilter('date','dateEnd','bis',$sessionDaten,'','',TRUE);
		$filterListe[23] = $this->formView->createFilter('check','self','nur eigene',$sessionDaten);
		$filterListe[40] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		
		if ($this->isAdmin()) {
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterListe[30] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[31] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
			$filterListe[41] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
			$filterListe[42] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
			$filterListe[50] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		} else if ($this->isEditor()) {
			$filterListe[50] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
			$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		}			
		$filterListe[] = $this->formView->createFilter('hidden','calMode','','','',$this->calMode);
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function getListViewFields() {
		$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
		$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
		if ($this->isDomainReviser($domainInfoscreen)) {
			return array('title'=>'Titel',
									 'start_date'=>'Startdatum',
									 'start_time'=>'Startuhrzeit',
									 'end_date'=>'Enddatum',
									 'end_time'=>'Enduhrzeit',
									 'location_id'=>'Ort',
									 'organizer_id'=>'Veranstalter',
					);
		} else {
			return array('title'=>'Titel',
					'start_date'=>'Startdatum',
					'start_time'=>'Startzeit',
					'location_id'=>'Ort',
					'organizer_id'=>'Veranstalter',
					'fe_cruser_id'=>'Person');
			
		}
	}

	function createPreviewLink($uid,$startDate,$id='') {
		if (empty($id)) {
			//t3lib_div::debug($this->piBase->settings,'settings');
			//$id = $this->piBase->settings['CAL_PREVIEW_PID'];
			$id = '93355';
		}
    $jahr = 0;
    $monat = 0;
    $tag = 0;
    $dateString = date('Y-m-d',$startDate);
		sscanf($dateString, "%4d-%02d-%02d", $jahr, $monat, $tag);
		$additionalParams = '&tx_cal_controller%5Bview%5D=event&tx_cal_controller%5Btype%5D=tx_cal_phpicalendar&tx_cal_controller%5Buid%5D=' . $uid . '&tx_cal_controller%5Byear%5D=' . $jahr . '&tx_cal_controller%5Bmonth%5D=' . $monat . '&tx_cal_controller%5Bday%5D=' . $tag;
		return 'http://www.hs-esslingen.de/index.php?id=' . $id . $additionalParams;
	}
		
	function postProcessingSaveForm($calUid,&$formData,$mode) {	
		$seite = $GLOBALS['TSFE']->id;
		$infoscreenSeite = '129005';

		if ($seite==$infoscreenSeite) {
				/*
				 * E-Mails nur versenden, wenn User nicht Admin ist
				 */
				$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
				$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
				if (!parent::isAdmin()) {
					/*
					 * E-Mail an Absender
					*/
					$title = $formData['title']->getValue();
					$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
					$userEmail = $GLOBALS['TSFE']->fe_user->user[email];
					$userName = $GLOBALS['TSFE']->fe_user->user[name];
					if (empty($userEmail)) {
						$userEmail = $GLOBALS['TSFE']->fe_user->user[username] . '@hs-esslingen.de';
					}
					$email->setFrom('hochschulkalender@hs-esslingen.de','Hochschulkalender');
					$email->setTo($userEmail,$userName);
					$email->setSubject('Infoscreen-Eintrag gespeichert');
					$link = 'https://www.hs-esslingen.de/index.php?id=' . $infoscreenSeite;
					$email->setBodyHtml('<h3>Neuer Infoscreen-Eintrag</h3>' .
							'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
							'Ihr Infoscreen-Eintrag "' . $title . '" wurde gespeichert,</p>' .
							'<p><a href="' . $link . '">Zur Infoscreen Termin-Verwaltung</a></p>' . 
							'<p>Mit freundlichen Grüßen<br/>' .
							'Das RÖM-Team</p>');
					$erg = $email->sendEmail();
          /*
           * E-Mail an Admin
           */
          $email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
          $email2->setSubject('Neuer Kalendereintrag');
          $email2->setFrom($userEmail,$userName);
          $email2->addTo('simona.ozimic@hs-esslingen.de','Simona Ozimic');
          $link = 'https://www.hs-esslingen.de/index.php?id=94586&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $calUid;
          $email2->setBodyHtml('<h3>Neuer Infoscreen-Eintrag</h3>' .
            'Es wurde ein neuer Infoscreen-Eintrag angelegt:</p>' .
            '<p>Zur Bearbeitung: <a href="' . $link . '">' . $title . '</a></p>');
          $erg = $email2->sendEmail();
        }
				$this->postProcessingDataChange($calUid);
		} else {
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
				case 'copy':
					/*
					 * E-Mails nur versenden, wenn User nicht Admin ist
					 */
					$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
					$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
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
						$email2->setTo('mmirsch@hs-esslingen.de','Manfred Mirsch');
						$email2->addTo('simona.ozimic@hs-esslingen.de','Simona Ozimic');
						$link = 'https://www.hs-esslingen.de/index.php?id=94586&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $calUid;
						$email2->setBodyHtml('<h3>Neuer Kalendereintrag</h3>' .
								'Es wurde ein neuer Kalendertermin eingereicht:</p>' .
								'<p>Zur Bearbeitung: <a href="' . $link . '">' . $title . '</a></p>');
						$erg = $email2->sendEmail();
					}
					break;
				case 'publish':
					$calModel = t3lib_div::makeInstance('tx_femanagement_model_cal_event');
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
							'<p>Sollten sich Änderungen an diesem Termin ergeben, wenden Sie sich bitte an ' .
							'<a href="http://www.hs-esslingen.de/de/mitarbeiter/simona-ozimic.html">Frau Ozimic</a>.</p>' .
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
	}
	
	function postProcessingDataChange($calUid) {
		$calModel = t3lib_div::makeInstance('tx_femanagement_model_cal_event');
		$calModel->aktualisiereSeitenTstampInfoscreen($calUid);
	}
	
	function getPermissions(&$elem,$page='',&$model='',$hiddenField = 'hidden') {
		$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->getPid());
		$domainInfoscreen = $domainModel->getDomainId('Infoscreen');
		$permissions = array();
	
		if (empty($model)) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_cal_event',$this->piBase,$this->getPid());
		}
		$catList = $model->getCategoryTitles($elem['uid'],$this->piBase,$this->getPid());
		$domains = array();
		foreach ($catList as $calCatTitle) {
			switch ($calCatTitle) {
				case 'Infoscreen Bibliothek': 
					$domains[] = $domainModel->getDomainId('Bibliothek');
					break;
				case 'Infoscreen GS': 
					$domains[] = $domainModel->getDomainId('Fakultät GS');
					break;
				case 'Infoscreen SAGP': 
					$domains[] = $domainModel->getDomainId('Fakultät SAGP');
					break;
				case 'Infoscreen RZ': 
					$domains[] = $domainModel->getDomainId('Rechenzentrum');
					break;
			}
		}
		$isDomainAdmin = false;
		if (count($domains)>0) {
			foreach ($domains as $domain) {
				if ($this->isDomainAdmin($domain)) {
					$isDomainAdmin = true;
				}
			}
		}
				
		if (empty($elem['uid']))	{
			$owner = true;
		} else {
			$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
			$owner = $model->isOwner($elem['uid'],$userId);
		}
		if ($this->isAdmin()) {
			$permissions = array('edit',
					'copy',
					'delete',
					'undelete',
					'destroy',
					'hide',
					'view',
			);
		} else if ($isDomainAdmin) {
			$permissions = array('edit',
					'view',
					'copy',
					'hide',
					'delete',
			);
		} else if ($elem['deleted']==0) {
			if ($owner) {
				$permissions = array('edit',
						'view',
						'copy',
						'hide',
						'delete',
				);
			} else if (empty($elem['uid']) || $this->isDomainReviser($domainInfoscreen)) {
				$permissions = parent::getPermissions($elem,$page,$model,$hiddenField);
			}
		}
		if (!empty($model)) {
			if ($model->infoscreenTermin($elem['uid'],$this->piBase,$this->getPid())) {
				if (!empty($permissions)) {
					$permissions[] = 'infoscreen';
				}
			}
		}
		return $permissions;
	}
		
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar_event.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_calendar_event.php']);
}

?>
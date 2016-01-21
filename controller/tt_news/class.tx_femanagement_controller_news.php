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
class tx_femanagement_controller_news extends tx_femanagement_controller {

	function __construct(&$piBase='',&$params='') {										//Erzeugt ein Objekt für die Klasse news_controller
		parent::__construct($piBase,$params);											//Erzeugt ein Objekt für die Vaterklasse controller
	}
	
	function initSingleView() {															//Vorbereitung des Single Views
		$viewClassName = 'tx_femanagement_view_form_news_single';						//Übergeben der speziellen View Klasse
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				//Aufruf der Funktion eidViewHandler mit dem Single View
		
		$this->model = t3lib_div::makeInstance											//Erzeugen einer News-Model-Instanz (in der Variablen protected $model der Vaterklasse)(Datenbankzugriffe)
		(
			'tx_femanagement_model_news',									//Übergeben der richtigen Model Klasse (als Parameter)
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->piBase->settings['STORAGE_PID']				//Übergeben der Konstanten (mit Speicher PID für News)
		);
		$this->formView = t3lib_div::makeInstance										//Erzeugen einer Single-View Objekt (in der Variablen protected $formView der Vaterklasse)(Ansicht für Einzelansicht)
		(
			$viewClassName,																//Auswahl der entsprechenden Klasse
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->piBase->settings['STORAGE_PID'],									//Übergeben der Konstanten (mit Speicher PID für News)
			'News Eintrag',																//Überschrift über dem Formular
			'news_single',																//Bezeichnung der DIV Klasse um das Formular
			$this->eidViewHandler														//Definition der eidURL
		);
		$this->formView->setControllerName('tx_femanagement_controller_news');			//Funktionsaufruf von setControllerName der Vaterklasse auf das erstellte Viewobjekt 																
		$this->formView->setModelName('tx_femanagement_model_news');					//Funktionsaufruf von setModelName der Vaterklasse auf das erstellte Viewobjekt
	}
	
	function initListView() {															//Vorbereitung des List Views
		$viewClassName = 'tx_femanagement_view_form_news_list';							//siehe oben wie bei initSingleView
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_news',
			$this->piBase,$this->piBase->settings['STORAGE_PID']
		);
		
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->piBase->settings['STORAGE_PID'],
			'Newseinträge',
			'news_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_news');																		
		$this->formView->setModelName('tx_femanagement_model_news');																		
	}
				
	function initFormSingle(&$formData,$mode) {	
		$fieldSettings = array();
		$fieldSettings['title'] = array(
												'title'=>'Titel',
												'type'=>'input',
												'validate'=>'required',
												);
		$fieldSettings['bodytext'] = array(
												'title'=>'Text',
												'type'=>'rte',
												'validate'=>'required',
												);
		$fieldSettings['short'] = array(
												'title'=>'Einleitungstext',
												'type'=>'text',
												);
		if (parent::isAdmin()) {
			$fieldSettings['tx_hetools_sortierfeld'] = array(
					'title'=>'Sortiernummer (bitte mit führenden Nullen eingeben)',
					'type'=>'input',
					'size'=>'4',
					'width'=>'10%',
					'validate'=>'required',
			);
		}
		$aktuellesDatum = $dateString = date('d.m.Y',time());		
		$fieldSettings['starttime'] = array(
												'title'=>'Erster Tag der Veröffentlichung<br />(entspricht Startdatum im Backend)',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$aktuellesDatum,
												);
		$archivDatum = $dateString = date('d.m.Y',time()+8640000);		
		$fieldSettings['archivedate'] = array(
												'title'=>'Letzter Tag der Veröffentlichung<br />(der News-Beitrag ist danach noch im Archiv abrufbar)',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$archivDatum,
												);
		$fieldSettings['image'] = array(
												'title'=>'Bild (bitte komprimieren Sie das Bild auf eine Breite von 800 Pixeln. Achten Sie darauf, dass es sich um HE-eigenes Bildmaterial handelt bzw. geben die genehmigte Bildquelle an.)',
												'type'=>'file',
												'upload_dir'=>'uploads/pics/',
												'filetyp' => 'image',
												);
		
		$fieldSettings['imagecaption'] = array(
												'title'=>'Bildunterschrift',
												'type'=>'input',
												);	
		$fieldSettings['news_files'] = array(
												'title'=>'Datei',
												'type'=>'file',
												'upload_dir'=>'uploads/media/',
												'filetyp' => 'all',
												);					
		$fieldSettings['author'] = array(
												'title'=>'Autor',
												'type'=>'input',
												);	
		
		$fieldSettings['author_email'] = array(
												'title'=>'E-Mail des Autors',
												'type'=>'input',
												);
		$fieldSettings['fe_cruser_id'] = array(
												'title'=>'Verfasser',
												'type'=>'hidden',
												);		
		if ($mode=='new') {
			$fieldSettings['author']['prefill'] = $this->getUserFullname();
			$fieldSettings['author']['type'] = 'readonly';
			$fieldSettings['author_email']['prefill'] = $this->getUserEmail();
			$fieldSettings['author_email']['type'] = 'readonly';
			$fieldSettings['fe_cruser_id']['prefill'] = $this->getUserid();
		}		
		if ($mode=='edit' && parent::isAdmin()) {
			$fieldSettings['pid'] = array(
					'title'=>'Systemordner in welchem die Nachricht gespeichert wird',
					'type'=>'ajax_select',
					'model'=>'tx_femanagement_model_news_sysfolders',
					'minLength'=>0,
					'limit'=>50,
			);
		} else {
			$fieldSettings['pid'] = array(
											 	'type'=>'hidden',
												'prefill'=>$this->piBase->settings['STORAGE_PID'],
											);
		}
    $newsCatModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories');
    $kategorienCallbacks = array (
      'php' => array('object'=>$newsCatModel,
        'method'=>'getCatList',
        'getTitles' => 'getCatTitleList',
        'pid'=>$this->getPid(),
      ),
    );

		if (parent::isAdmin()) {
			$fieldSettings['category'] = array(
				'title'=>'Kategorien',
				'type'=>'multiselect',
				'selectedElems'=>'ausgewählte Kategorien',
				'callbacks'=>$kategorienCallbacks,
				'validate'=>'required',
				'options'=>array('searchable'=>TRUE,'height'=>200),
			);
		} else  {
			$newsCatsExclusive = array();
			$newsCatsExclusive[] = $newsCatModel->getCatId('Aktuelles');
			$newsCatsExclusive[] = $newsCatModel->getCatId('Intranet-Studierende');
			$newsCatsExclusive[] = $newsCatModel->getCatId('Intranet-Mitarbeiter');
			$newsCatsExclusive[] = $newsCatModel->getCatId('Intranet-Prof/LB');
			$newsCatsExclusive[] = $newsCatModel->getCatId('Hochschulexpress_Redaktion');
			$catHochschule = $newsCatModel->getCatId('Hochschule');
			$fieldSettings['category'] = array(
					'title'=>'Kategorien',
					'type'=>'multiselect',
					'selectedElems'=>'ausgewählte Kategorien',
					'callbacks'=>$kategorienCallbacks,
					'validate'=>'required',
					'exclusive_ids'=>$newsCatsExclusive,
			);
		}
		$fieldSettings['save'] = array(
				'value'=>'Newseintrag speichern',
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
					'value'=>'Newseintrag freischalten',
					'type'=>'button',
					'buttonType'=>'submit',
			);
		}
		$formData = $this->createFormFields($fieldSettings);
	}

	function createFormSingle(&$formData,&$parameter,$mode) {		
		$mainfelder = array('title','short','bodytext','category','pid');										
		if (parent::isAdmin()) {
			$mainfelder[] = 'tx_hetools_sortierfeld';
		}
		$datumsFelder = array('starttime','archivedate');
		$dateiFelder = array('image','imagecaption','news_files');
		$weitereFelder = array('author','author_email');	
		if (parent::isAdmin() && !empty($parameter['uid'])) {
			$weitereFelder[] = 'fe_cruser_id';
		}
		$hauptcontainer = $this->createContainer($mainfelder,$formData);
		$timecontainer = $this->createContainer($datumsFelder,$formData,FALSE,'field_col2');
		$dateicontainer = $this->createContainer($dateiFelder,$formData);
		$weiterercontainer = $this->createContainer($weitereFelder,$formData);

		$containerList_main = array($hauptcontainer);										//Select all container to an array
		$this->formView->addFieldset($containerList_main);									//Create new Fieldset with all container

		$containerList_date = array($timecontainer);										//Select all container to an array
		$this->formView->addFieldset($containerList_date);									//Create new Fieldset with all container		
		
		$containerList_data = array($dateicontainer);										//Select all container to an array
		$this->formView->addFieldset($containerList_data);									//Create new Fieldset with all container		
		
		$containerList_weiteres = array($weiterercontainer);								//Select all container to an array
		$this->formView->addFieldset($containerList_weiteres);
		
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
	
	function showListView($aktuelleSeite) {												//Wird vom Hauptcontroller nach init_List_view aufgerufen mit der aktuellen seiten id
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		
		$buttonListe = array();
		$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');		//Definition der Filter für gelöschte Elemente
		$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');		//Definition der Filter für verborgenen Elemente
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$catData = $this->formView->getCategories();									//Holt sich eine Liste aller News Kategorien aus --> tx_femanagement_view_form_news_list::getCategories welche auf das news model zugreift
		$filterListe[20] = $this->formView->createFilter('select','cat','Kategorie',$sessionDaten,$catData);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		if ($this->isAdmin()) {
			$filterListe[21] = $this->formView->createFilter('date','datetimeStart','Datum von',$sessionDaten,'','',TRUE);
			$filterListe[22] = $this->formView->createFilter('date','datetimeEnd','Datum bis',$sessionDaten,'','',TRUE);
			$filterListe[23] = $this->formView->createFilter('date','starttimeStart','Start von',$sessionDaten,'','',TRUE);
			$filterListe[24] = $this->formView->createFilter('date','starttimeEnd','Start bis',$sessionDaten,'','',TRUE);
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterListe[25] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[26] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
//			$filterListe[31] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
//			$filterListe[32] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		} else if ($this->isEditor()) {
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterListe[21] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		}
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);	// $this->formView = Variable mit View-Objekt von tx_femanagement_view_form_news_list ---> Darin wird die Funktion showListView aufgerufen --> Da sie da nicht ist wird die Funktion von der Vater-Vater-Klasse view_form_list ausgeführt
	}
	
	function getListViewFields() {
		return array('title'=>'Titel',
								 'datetime'=>'Datum',
								 'archivedate'=>'Archivdatum',
								 'starttime'=>'Startdatum',
								 'endtime'=>'Stopdatum',
								 'tx_hetools_sortierfeld'=>'Sortierfeld',
								 'fe_cruser_id'=>'Person');
	}

	function postProcessingSaveForm($newsUid,&$formData,$mode) {
		$kategorien = $formData['category']->getValue();
	
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_news_categories');
		$daten = $catModel->getList();
		$kategorieTitelListe = array();
		foreach ($daten as $uid=>$titel) {
			if (in_array($uid,$kategorien)) {
				$kategorieTitelListe[$uid] = $titel;
			}
		}
		if (count($kategorieTitelListe)<=1) {
			$kategorieTitel = '"' . implode('", "',$kategorieTitelListe) . '"';
		} else {
			$erstesElement = TRUE;
			$index = 1;
			foreach ($kategorieTitelListe as $titel) {
				if ($erstesElement) {
					$kategorieTitel = '"' . $titel . '"';
					$erstesElement = FALSE;
				} else {
					if ($index<count($kategorieTitelListe)) {
						$kategorieTitel .= ', "' . $titel . '"';
					} else {
						$kategorieTitel .= ' und "' . $titel . '"';
					}
				}
				$index++;
			}
		}
		$title = $formData['title']->getValue();
		$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
	
$testModus = TRUE;		
		switch ($mode) {
			case 'new':
				/*
				 * E-Mails nur versenden, wenn User nicht Admin ist
				 */
				if ($testModus || !parent::isAdmin()) {
	
					/*
					 * E-Mail an Absender
					*/
	
					$userEmail = $GLOBALS['TSFE']->fe_user->user[email];
					$userName = $GLOBALS['TSFE']->fe_user->user[name];
					if (empty($userEmail)) {
						$userEmail = $GLOBALS['TSFE']->fe_user->user[username] . '@hs-esslingen.de';
					}
					$email->setFrom('no-reply@hs-esslingen.de','Newsverwaltung');
					$email->setTo($userEmail,$userName);
					$email->setSubject('Newseintrag gespeichert');
					$email->setBodyHtml('<h3>Neuer Newseintrag</h3>' .
							'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
							'Ihr Newseintrag "' . $title . '" wurde gespeichert,</p>' .
							'<p>Vielen Dank für die Eingabe des Eintrags <b>"' . $title . '"</b>.</p>' .
							'<p>Über die Freigabe zur Veröffentlichung des Newseintrags werden Sie automatisch in einer weiteren E-Mail informiert.<br/>' .
							'Sollten sich Änderungen an diesem Eintrag ergeben, wenden Sie sich bitte an <a href="http://www.hs-esslingen.de/de/mitarbeiter/tobias-binder.html">Tobias Binder</a>.</p>' .
							'<p>Gerne stehen wir Ihnen für Rückfragen zur Verfügung.</p>' .
							'<p>Mit freundlichen Grüßen<br/>' .
							'Das RÖM-Team</p>');
	
					$erg = $email->sendEmail();
					/*
					 * E-Mail an Admin
					*/
					$email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
					$email2->setSubject('Neuer Newseintrag');
					$email2->setFrom($userEmail,$userName);
					//				$email2->setTo('mmirsch@hs-esslingen.de','Manfred Mirsch');
					$adminList = parent::getAdminList();
					$toList = array();
					foreach($adminList as $entry) {
						$email = $entry['email'];
						$name = $entry['name'];
						if (empty($toList)) {
							$email2->setTo($email,$name);
							$toList[$email] = $name;
						} else {
							$email2->addTo($email,$name);
						}
					}
					
					$link = 'https://www.hs-esslingen.de/index.php?id=94605&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $newsUid;
					$email2->setBodyHtml('<h3>Neuer Newseintrag</h3>' .
							'Es wurde ein neuer Newseintrag eingereicht:</p>' .
							'<p>Zur Bearbeitung: <a href="' . $link . '">' . $title . '</a></p>');
					$erg = $email2->sendEmail();
				}
				break;
			case 'publish':
				$calModel = t3lib_div::makeInstance('tx_femanagement_model_news');
				$configArray['show_hidden'] = 1;
				$configArray['all_pids'] = 1;
	
				$userId = $calModel->selectField($newsUid,'fe_cruser_id',$configArray);
	
				$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
				$feUserdata = $feUserModel->selectFields('uid',$userId,'fe_users','name,email,username');
				$userEmail = $feUserdata['email'];
				if (empty($userEmail)) {
					$userEmail = $feUserdata['username'] . '@hs-esslingen.de';
				}
				$userName = $feUserdata['name'];
				$email->setFrom('no-reply@hs-esslingen.de','Newsverwaltung');
				$email->setTo($userEmail,$userName);
				$link = 'https://www.hs-esslingen.de/index.php?id=94605&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $newsUid;
				$linkUrl = $this->createPreviewLink($newsUid);
				$email->setSubject('Newseintrag freigeschaltet');
				$email->setBodyHtml('<h3>Newseintrag freigeschaltet</h3>' .
						'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
						'Der von Ihnen eingegebene Termin <b>"' . $title . '"</b> wurde zur Veröffentlichung in den Kategorien <b>' . $kategorieTitel . '</b> frei gegeben.</p>' .
						'<p>Vorschau: <a href="' . $linkUrl . '">' . $title . '</a></p>' .
						'Sollten sich Änderungen an diesem Eintrag ergeben, wenden Sie sich bitte an <a href="http://www.hs-esslingen.de/de/mitarbeiter/tobias-binder.html">Herrn Binder</a>.</p>' .
						'Gerne stehen wir Ihnen für Rückfragen zur Verfügung.<br/>' .
						'<p>Mit freundlichen Grüßen<br/><br/>' .
						'Das RÖM-Team</p>');
	
				$erg = $email->sendEmail();
				break;
			case 'edit':
				break;
		}
		$this->postProcessingDataChange($newsUid);
	}
	
	function postProcessingDataChange($newsUid) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_news');
		$model->loescheSeitencaches($newsUid);
	}
	
	function createPreviewLink($uid,$id='') {
		if (empty($id)) {
			$id = '95238';
		}
		$additionalParams = '&no_cache=1&&tx_ttnews%5Btt_news%5D=' . $uid;
		return 'http://www.hs-esslingen.de/index.php?id=' . $id . $additionalParams;
	}
		
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_news.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_news.php']);
}

?>
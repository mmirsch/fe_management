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
class tx_femanagement_controller_forschung extends tx_femanagement_controller {
var $config = array();
	
	function __construct(&$piBase='',&$params='') {							
		parent::__construct($piBase,$params);											
		$currentSessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
		$this->sortField = tx_femanagement_lib_util::getPageConfig('config.,sortField');
		if (!empty($this->sortField)) {
			$this->config['filterSearchFields'] = tx_femanagement_lib_util::getPageConfig('config.,filterSearchFields');
			$this->config['filterFields'] = tx_femanagement_lib_util::getPageConfig('config.,filterFields.');
			$this->config['showFields'] = tx_femanagement_lib_util::getPageConfig('config.,showFields.');
			$this->config['listview'] = tx_femanagement_lib_util::getPageConfig('config.,listview.');
			$this->config['singleview'] = tx_femanagement_lib_util::getPageConfig('config.,singleview.');
			$this->config['limit'] = tx_femanagement_lib_util::getPageConfig('limit');
			if (!empty($this->config['singleview']['pageId'])) {
				$this->config['singleViewPageId'] = $this->config['singleview']['pageId'];
			} else {
				$this->config['singleViewPageId'] = $GLOBALS['TSFE']->id;
			}
			$currentSessionData['config'] = $this->config;
			$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($currentSessionData));
			$GLOBALS['TSFE']->fe_user->storeSessionData();				
		} else {
			$get = t3lib_div::_GET();
			$this->config = $currentSessionData['config'];
		}	
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/forschung/css/forschung.css"/>
		';
	}
	
	function initSingleView() {															//Vorbereitung des Single Views
		$viewClassName = 'tx_femanagement_view_forschung_single';						//Übergeben der speziellen View Klasse
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				//Aufruf der Funktion eidViewHandler mit dem Single View
		
		$this->model = t3lib_div::makeInstance											//Erzeugen einer Model-Instanz (in der Variablen protected $model der Vaterklasse)(Datenbankzugriffe)
		(
			'tx_femanagement_model_forschung',									//Übergeben der richtigen Model Klasse (als Parameter)
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid()				//Übergeben der Konstanten (mit Speicher PID)
		);
		$this->formView = t3lib_div::makeInstance										//Erzeugen einer Single-View Objekt (in der Variablen protected $formView der Vaterklasse)(Ansicht für Einzelansicht)
		(
			$viewClassName,																//Auswahl der entsprechenden Klasse
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid(),									//Übergeben der Konstanten (mit Speicher PID)
			'Forschungs-Eintrag',																//Überschrift über dem Formular
			'forschung_single',																//Bezeichnung der DIV Klasse um das Formular
			$this->eidViewHandler														//Definition der eidURL
		);
		$this->formView->setControllerName('tx_femanagement_controller_forschung');			//Funktionsaufruf von setControllerName der Vaterklasse auf das erstellte Viewobjekt 																
		$this->formView->setModelName('tx_femanagement_model_forschung');					//Funktionsaufruf von setModelName der Vaterklasse auf das erstellte Viewobjekt
	}
	
	function initListView() {															//Vorbereitung des List Views
		$viewClassName = 'tx_femanagement_view_forschung_list';							//siehe oben wie bei initSingleView
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_forschung',
			$this->piBase,$this->getPid()
		);
		
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Forschungseinträge',
			'forschung_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_forschung');																		
		$this->formView->setModelName('tx_femanagement_model_forschung');																		
	}
				
	function initFormSingle(&$formData,$mode) {		
		$fieldSettings = array();
		
		$fieldSettings['title'] = array(
												'title'=>'Titel',
												'type'=>'input',
												'validate'=>'required',
												);
		$fieldSettings['hinweis'] = array(
				'title'=>'Hinweis',
				'type'=>'info',
				'value'=>'Bitte füllen Sie das Formular <a target="_blank" href="/fileadmin/medien/einrichtungen/Forschung_Transfer/HE_Einverstaendniserklaerung_FDB.pdf">Einwilligung zur Veröffentlichung von Unternehmensdaten</a> für alle Unternehmen aus, die veröffentlicht werden sollen.',
		);
		
		$model = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->getPid());
		$institute = $model->gibInstitute(); 	
			
		$fieldSettings['leitende_einrichtung'] = array(
				'title'=>'Fakultät/ Institut (federführend)',
				'type'=>'select',
				'selectData'=>$institute,
				'emptySelectTitle'=>'Bitte auswählen',
				'validate'=>'required',
		);

    $fieldSettings['projektnummer'] = array(
      'title'=>'Projektnummer',
      'type'=>'input',
      'tooltip'=>'Offizielle Projektnummer der Finanzabteilung der Hochschule Esslingen',
      'tooltip_popup'=>'Offizielle Projektnummer der Finanzabteilung der Hochschule Esslingen',
    );

    $fieldSettings['foerderkennzeichen'] = array(
      'title'=>'Förderkennzeichen',
      'type'=>'input',
      'tooltip'=>'Laut Zuwendungsbescheid vom Förderträger',
      'tooltip_popup'=>'Laut Zuwendungsbescheid vom Förderträger',
    );

// Beteiligte Fakultäten
		
		$dataProviderEinrichtungen = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		
		$einrichtungenCallbacks = array(
				'php' => array('object'=>$dataProviderEinrichtungen,
						'method'=>'getEinrichtungenList',
						'getTitles'=>'getEinrichtungenTitles',
						'pid'=>$this->getPid(),
				),
		);
		
		$fieldSettings['fakultaet'] = array(
				'title'=>'Beteiligte Fakultäten/ Institute',
				'type'=>'multiselect',
				'selectedElems'=>'ausgewählte Fakultäten/ Institute',
				'callbacks'=>$einrichtungenCallbacks,
				'validate'=>'required',
				'options'=>array('height'=>300,'width'=>400),
			);
		
		$fieldSettings['faku_link'] = array(
												'title'=>'Link zu Fakultät/Institut',
												'type'=>'input',
												);
	
		$fieldSettings['foerderung_wer'] = array(
				'title'=>'Förderung durch?',
				'type'=>'dyn_table',
				'colTitles'=> array('einrichtung'=>'Einrichtung/Firma','foerderprogramm'=>'Förderprogramm','logo'=>'Logo', 'genehmigung'=>'Genehmigung<br />zur externen<br />Veröffentlichung', 'art'=>'Art der Förderung'),
				'colTypes' => array('einrichtung'=>'input','foerderprogramm'=>'input','logo'=>'image','genehmigung'=>'checkbox','art'=>'select'),
				'colData' => array('art'=>array(''=>'bitte auswählen','privat'=>'privat gefördert','oeffentlich'=>'öffentlich gefördert')),
				
				'numRows'=>3,
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/pics/',
				'linkTitle'=>'Zeilen hinzufügen',
		);
		
		$fieldSettings['kooperationspartner'] = array(
				'title'=>'Kooperations-/ Projektpartner',
				'type'=>'dyn_table',
				'colTitles'=> array('einrichtung'=>'Kooperationspartner','logo'=>'Logo', 'genehmigung'=>'Genehmigung<br />zur externen<br />Veröffentlichung'),
				'colTypes' => array('einrichtung'=>'input','logo'=>'image','genehmigung'=>'checkbox'),
				'numRows'=>3,
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/pics/',
				'linkTitle'=>'Zeilen hinzufügen',
		);
		$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users',$this->piBase,$this->getPid());
		$usernameDisplay = $modelFeUser->getFieldData($this->feUser);
		
		$validateScriptWissLeitung = '
				<input type="hidden" required="required" id="wiss_leitung" value="empty" />
				<script type="text/javascript">
				function validateMitarbeiter() {
				var table = $("#dyn_table_wiss_mitarbeiter");
				var rows = $("tr:gt(0)",table);
				var meldungAlleFelder = "Bitte geben Sie mindestens die Felder \'Vorname\' und \'Nachname\' ein!";
				var meldungMindestensEinEintrag = "Bitte geben Sie mindestens eine Person ein!";
				var valid = true;
				var filledRows = 0;
					rows.each(function(){
						if (valid) {
					  	var requiredCols = ["vorname", "nachname" ];
					  	var checkCols = ["vorname", "nachname" ];
					  	var vorname = "";
					  	var nachname = "";
					  	var cols = $("input",this);
					  	var colName;
					  	var rowFilled = false;
					  	cols.each(function(){
					  		var wert = "";
					  		if ($(this).attr("type")!="hidden") {
					  			if ($(this).attr("type")=="checkbox") {
					  				if ($(this).is(":checked")) {
					  					wert = "on";
					  				}
					  			} else {	 
					  				wert = $(this).val();
					  			}
					  			if (wert!="") {
					  				rowFilled = true;
					  			}
					  		}
					  	});
				  		if (rowFilled) {
				  			filledRows++;
				  			for (var i=0;valid && i<requiredCols.length; i++) {
				  				var wert = $(this).find("." + requiredCols[i]).val();
				  				if (wert=="") {
				  					valid = false;
									}
				  			}
				  		}
						}
					});
					if (filledRows==0) {
						return meldungMindestensEinEintrag;
					} else if (!valid) {
						return meldungAlleFelder;
					} else {
						return true;
					}
				}
				$.tools.validator.fn("#wiss_mitarbeiter", function(element, value) {
					return validateMitarbeiter();
				});
				</script>';
				
			$fieldSettings['wiss_leitung'] = array(
					'title'=>'Wissenschaftliche Leitung',
					'type'=>'dyn_table',
//					'colTitles'=> array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)'),
//					'colTypes' => array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'input'),
					'numRows'=>2,
					'linkTitle'=>'Zeilen hinzufügen',
					'validateScript' => $validateScriptWissLeitung,
			);
		if (parent::isAdmin() || $this->isApplicationAdmin()) {		
			$fieldSettings['wiss_leitung']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)');
			$fieldSettings['wiss_leitung']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'input');
		} else {
			$fieldSettings['wiss_leitung']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)');
			if ($mode=='new') {
				$fieldSettings['wiss_leitung']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'hidden');
			} else {
				$fieldSettings['wiss_leitung']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'readonly');
			}
		}
		
		$validateScriptMitarbeiter = '
				<input type="hidden" required="required" id="wiss_mitarbeiter" value="empty" />
				<script type="text/javascript">
				function validateMitarbeiter() {
				var table = $("#dyn_table_wiss_mitarbeiter");
				var rows = $("tr:gt(0)",table);
				var meldungAlleFelder = "Bitte geben Sie mindestens die Felder \'Vorname\' und \'Nachname\' ein!";
				var meldungMindestensEinEintrag = "Bitte geben Sie mindestens eine Person ein!";
				var valid = true;
				var filledRows = 0;
					rows.each(function(){
						if (valid) {
					  	var requiredCols = ["vorname", "nachname" ];
					  	var checkCols = ["vorname", "nachname" ];
					  	var vorname = "";
					  	var nachname = "";
					  	var cols = $("input",this);
					  	var colName;
					  	var rowFilled = false;
					  	cols.each(function(){
					  		var wert = "";
					  		if ($(this).attr("type")!="hidden") {
					  			if ($(this).attr("type")=="checkbox") {
					  				if ($(this).is(":checked")) {
					  					wert = "on";
					  				}
					  			} else {	 
					  				wert = $(this).val();
					  			}
					  			if (wert!="") {
					  				rowFilled = true;
					  			}
					  		}
					  	});
				  		if (rowFilled) {
				  			filledRows++;
				  			for (var i=0;valid && i<requiredCols.length; i++) {
				  				var wert = $(this).find("." + requiredCols[i]).val();
				  				if (wert=="") {
				  					valid = false;
									}
				  			}
				  		}
						}
					});
					if (filledRows==0) {
						return meldungMindestensEinEintrag;
					} else if (!valid) {
						return meldungAlleFelder;
					} else {
						return true;
					}
				}
				$.tools.validator.fn("#wiss_mitarbeiter", function(element, value) {
					return validateMitarbeiter();
				});
				</script>';
		$fieldSettings['wiss_mitarbeiter'] = array(
				'title'=>'Wissenschaftliche MitarbeiterInnen',
				'type'=>'dyn_table',
				'colTitles'=> array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'genehmigung'=>'Genehmigung<br />zur externen<br />Veröffentlichung'),
				'colTypes' => array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','genehmigung'=>'checkbox'),
				'numRows'=>3,
				'linkTitle'=>'Zeilen hinzufügen',
				'validateScript' => $validateScriptMitarbeiter,
		);
		
		$maxChars = 800;
		$toleranz = 20;
		$fieldSettings['beschreibung_kurz'] = array(
				'title'=>'Projektbeschreibung Kurzfassung (maximal ' . $maxChars . ' Zeichen)',
				'type'=>'rte',
				'configEditor'=>array('maxChars'=>($maxChars+$toleranz)),
				'validate'=>'required',
		);
		
		if (parent::isAdmin()) {
			$fieldSettings['beschreibung_kurz']['admin'] = true;
		}
		
		$fieldSettings['beschreibung_lang'] = array(
				'title'=>'Projektbeschreibung',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/media/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['downloads'] = array(
				'title'=>'Weiteres Dokument zur Projektbeschreibung',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/media/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['downloads_beschriftung'] = array(
				'title'=>'Angezeigte Beschreibung des Dokuments',
				'type'=>'input',
		);
		
		$aktuellesDatum = $dateString = date('d.m.Y',time());		
		$fieldSettings['start_datum'] = array(
												'title'=>'Projektlaufzeit Start',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$aktuellesDatum,
												'validate'=>'required',
												);
		$endDatum = $dateString = date('d.m.Y',time()+86400*365);		
		$fieldSettings['end_datum'] = array(
												'title'=>'Projektlaufzeit Ende',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$endDatum,
												'validate'=>'required',
												);
		
		$fieldSettings['webseite'] = array(
				'title'=>'Spezielle Webseite des Projekts',
				'type'=>'input',
		);

		$fieldSettings['medien1'] = array(
				'title'=>'Grafik 1<br/><i>Die Grafik können Sie nur einstellen, wenn Ihnen die Genehmigung des Urhebers vorliegt</i>',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/pics/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['bildunterschrift1'] = array(
				'title'=>'Bildunterschrift zur Grafik 1',
				'type'=>'input',
		);
		
		$fieldSettings['medien2'] = array(
				'title'=>'Grafik2<br/><i>Die Grafik können Sie nur einstellen, wenn Ihnen die Genehmigung des Urhebers vorliegt</i>',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_forschungsprojekte/pics/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['bildunterschrift2'] = array(
				'title'=>'Bildunterschrift zur Grafik 2',
				'type'=>'input',
		);
		
		$fieldSettings['veroeff_title'] = array(
				'title'=>'Veröffentlichungen',
				'type'=>'rte',
		);

		$promotionenModel = t3lib_div::makeInstance('tx_femanagement_model_promotionen');
		$promotionenCallbacks = array (
			'php' => array('object'=>$promotionenModel,
				'method'=>'getList',
				'getTitles' => 'getTitleList',
				'pid'=>'all',
			),
		);

		$fieldSettings['diss'] = array(
			'title'=>'Promotionen zum Forschungsprojekt',
			'type'=>'multiselect',
			'callbacks'=>$promotionenCallbacks,
			'selectedElems'=>'ausgewählte Promotionen',
			'options'=>array('searchable'=>TRUE,'height'=>500,'width'=>500),
		);

/*
		$fieldSettings['diss'] = array(
				'title'=>'Dissertationen ',
				'type'=>'rte',
				'prefill'	=>	'<b>Promovierende(r) Vorname:</b>&nbsp;<br/>' . 
											'<b>Promovierende(r) Nachname:</b>&nbsp;<br/>' . 
											'<b>Akademischer Titel:</b>&nbsp;<br/>' . 
											'<b>Betreuender Professor (HE):</b>&nbsp;<br/>' . 
											'<b>Kooperierende Universität:</b>&nbsp;<br/>' . 
											'<b>Kooperierender Professor:</b>&nbsp;<br/>' . 
											'<b>Laufend/Veröffentlicht ("laufend" oder Datum eingeben):</b>&nbsp;<br/>' . 
											'<b>Link zur Veröffentlichung:</b>&nbsp;',
		);
*/
    $fieldSettings['nachhaltigkeitsbezug'] = array(
      'title'=>'Bitte kreuzen Sie diese folgenden Auswahlfelder an, wenn es sich um ein Forschungsprojekt mit Nachhaltigkeitsbezug handelt, weil diese Projekte im Bereich „Umweltmanagement“ explizit ausgewiesen werden.',
      'type'=>'info',
    );
    $fieldSettings['nachhaltigkeitsbezug_oekologisch'] = array(
      'title'=>'ökologisch',
      'tooltip'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit ökologischem Nachhaltigkeitsbezug handelt, weil diese Projekte im Bereich „Umweltmanagement“ explizit ausgewiesen werden.',
      'tooltip_popup'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit ökologischem Nachhaltigkeitsbezug handelt,<br>weil diese Projekte im Bereich <b>Umweltmanagement</b> explizit ausgewiesen werden.',
      'type'=>'checkbox',
    );
    $fieldSettings['nachhaltigkeitsbezug_oekonomisch'] = array(
      'title'=>'ökonomisch',
      'tooltip'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit ökonomischem Nachhaltigkeitsbezug handelt, weil diese Projekte im Bereich „Umweltmanagement“ explizit ausgewiesen werden.',
      'tooltip_popup'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit ökonomischem Nachhaltigkeitsbezug handelt,<br>weil diese Projekte im Bereich <b>Umweltmanagement</b> explizit ausgewiesen werden.',
      'type'=>'checkbox',
    );

    $fieldSettings['nachhaltigkeitsbezug_sozial'] = array(
      'title'=>'sozial',
      'tooltip'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit sozialem Nachhaltigkeitsbezug handelt, weil diese Projekte im Bereich „Umweltmanagement“ explizit ausgewiesen werden.',
      'tooltip_popup'=>'Bitte kreuzen Sie diese Auswahl an, wenn es sich um ein Forschungsprojekt mit sozialem Nachhaltigkeitsbezug handelt,<br>weil diese Projekte im Bereich <b>Umweltmanagement</b> explizit ausgewiesen werden.',
      'type'=>'checkbox',
    );


		$fieldSettings['foerdersumme'] = array(
				'title'=>'Bewilligte Förderungssumme',
				'type'=>'input',
				'tooltip'=>'Bitte die Angaben aus dem Zuwendungsbescheid verwenden!',
				'tooltip_popup'=>'Bitte die Angaben aus dem Zuwendungsbescheid verwenden!',
		);
		
		$fieldSettings['pid'] = array(
											 	'type'=>'hidden',
												'prefill'=>$this->getPid(),
											);

		$fieldSettings['fe_cruser_id'] = array(
											 	'type'=>'hidden',
												'prefill'=>$GLOBALS['TSFE']->fe_user->user['uid'],
											);

		$fieldSettings['save'] = array(
				'value'=>'Eintrag speichern',
				'type'=>'button',
				'buttonType'=>'submit',
		);
		$fieldSettings['abort'] = array(
				'value'=>'Abbrechen',
				'type'=>'button',
				'buttonType'=>'abort',
		);
		if (parent::isAdmin() || $this->isApplicationAdmin()) {
			$fieldSettings['publish'] = array(
					'value'=>'Eintrag freischalten',
					'type'=>'button',
					'buttonType'=>'submit',
			);
		}
		$formData = $this->createFormFields($fieldSettings);
	}

	function createFormSingle(&$formData,&$parameter,$mode) {		
		$hauptFelder = array('hinweis','title','leitende_einrichtung',
												 'fakultaet','faku_link','projektnummer', 'foerderkennzeichen',
												 'foerderung_wer','kooperationspartner',
												 'pid','fe_cruser_id');										
		$datumsFelder = array('start_datum','end_datum');
		$personenFelder = array('wiss_leitung','wiss_mitarbeiter');
		$weitereFelder = array('webseite','beschreibung_kurz','beschreibung_lang','downloads','downloads_beschriftung',
													 'medien1','bildunterschrift1','medien2','bildunterschrift2',
                            'nachhaltigkeitsbezug','nachhaltigkeitsbezug_oekologisch','nachhaltigkeitsbezug_oekonomisch',
                            'nachhaltigkeitsbezug_sozial','foerdersumme');
		
		$hauptContainer = $this->createContainer($hauptFelder,$formData);
		$datumContainer = $this->createContainer($datumsFelder,$formData,FALSE,'field_col2');
		$personenContainer = $this->createContainer($personenFelder,$formData);
		$weiterercontainer = $this->createContainer($weitereFelder,$formData);

		$containerListMain = array($hauptContainer);
		$this->formView->addFieldset($containerListMain);

		$containerListDatum = array($datumContainer);
		$this->formView->addFieldset($containerListDatum);		
		
		$containerListPersonen = array($personenContainer);
		$this->formView->addFieldset($containerListPersonen);		
		
		$containerList_weiteres = array($weiterercontainer);								
		$this->formView->addFieldset($containerList_weiteres);
		
		$buttonFelder = array('save','abort');

		if (parent::isAdmin()  || $this->isApplicationAdmin()) {

			$promotionenFelder = array('diss');
			$promotionenContainer = $this->createContainer($promotionenFelder,$formData);
			$containerListPromotionen = array($promotionenContainer);
			$this->formView->addFieldset($containerListPromotionen);

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
	
	function showDataSingle(&$formData,&$parameter,$mode,$aktuelleSeite='') {
		if ($mode!='view') {
			return parent::showDataSingle($formData,$parameter,$mode,$aktuelleSeite);
		}
		$uid = $parameter['uid'];
		$fieldList = array(
				'title' => 'Titel',
				'beschreibung_lang' => 'Projektbeschreibung (Datei)',
				'downloads' => 'Dokument zur Projektbeschreibung',
				'downloads_beschriftung' => 'Angezeigte Beschreibung des Dokuments',
				'beschreibung_kurz' => 'Projektbeschreibung',
				'leitende_einrichtung' => 'Fakultät/Institut (federführend)',
				'fakultaet' => 'Beteiligte Fakultäten/ Institute',
				'faku_link' => 'Link zu Fakultät/Institut',
				'start_datum' => 'Projektlaufzeit Start',
				'end_datum' => 'Projektlaufzeit Ende',
				'anzahl_stud' => 'Teilnehmende Studierende',
				'foerderung_wer' => 'Förderung durch wen?',
				'kooperationspartner' => 'Kooperations-/ Projektpartner',
				'wiss_leitung' => 'Wissenschaftliche Leitung',
				'wiss_mitarbeiter' => 'Wissenschaftliche Mitarbeiter/innen',
				'foerdersumme' => 'Fördersumme',
				'webseite' => 'Spezielle Webseite des Projekts',
				'veroeff_title' => 'Veröffentlichungen',
				'veroeff_link' => 'Veröffentlichungen (Link)',
				'diss' => 'Mit dem Projekt verknüpfte Promotion/en',
				'medien1' => 'Grafik1',
				'bildunterschrift1' => 'Bildunterschrift 1',
				'medien2' => 'Grafik2',
				'bildunterschrift2' => 'Bildunterschrift 2',
				);
		$out = $this->formView->showSingleView($this->model,$fieldList,$mode,$aktuelleSeite,$uid);
		if ($this->testParam('popup')) {
			if ($this->testParam('norefresh')) {
				$out .= '	<div id="popup_code">
				<input type="button" value="Fenster schliessen"
				onclick="javascript:window.close();" />
				</div>
				';
			} else {
				$out .= '	<div id="popup_code">
				<input type="button" value="Fenster schliessen"
				onclick="javascript:window.opener.location.reload();window.close();" />
				</div>
				';
			}
		}
		return $out;
	}
	
	function showListView($aktuelleSeite) {												//Wird vom Hauptcontroller nach init_List_view aufgerufen mit der aktuellen seiten id
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$buttonListe = array();
		$index = 10;
		foreach ($this->config['filterFields'] as $field=>$title) {
			switch ($field) {
				case 'az':
					$filterListe[$index] = $this->formView->createFilter('hidden','az','','','','all');
					$index++;
					break;
				case 'volltextsuche':
					$filterListe[$index] = $this->formView->createFilter('search','volltextsuche',$title,$sessionDaten);
					$index++;
					break;
				case 'personensuche':
					$filterListe[$index] = $this->formView->createFilter('search','personensuche',$title,$sessionDaten);
					$index++;
					break;
				case 'einrichtung':
					$model = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->getPid());
					$einrichtungen = $model->gibInstitute();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,$sessionDaten,$einrichtungen,'',FALSE,'einrichtung');
					$index++;
					break;
        case 'projektstatus':
          $aktJahr = date('Y');
          $this->addFilter($filterListe,$excludeFilters,'text','bezugsjahr','Bezugsjahr',$sessionDaten,$aktJahr,0,TRUE);
          $filterProjektStatus = array('abgeschlossene'=>'abgeschlossene','laufende'=>'laufende','neu'=>'neu begonnene');
          $this->addFilter($filterListe,$excludeFilters,'select','projektstatus','Projektstatus',$sessionDaten,$filterProjektStatus,0,TRUE);
          break;
			}
		}
		$anzSelect = array('5'=>'5','10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$hideFilters = $this->config['hideFilters'];
		if (!empty($hideFilters)) {
			$excludeFilters = explode(',',$hideFilters);
		} else {
			$excludeFilters = array();
		}
	
		//		$buttonListe = array($this->formView->createButton('newElem',$this->params));
		if ($this->isAdmin()) {
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$this->addFilter($filterListe,$excludeFilters,'select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$this->addFilter($filterListe,$excludeFilters,'select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
			$filterListe[] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[] = $this->formView->createFilter('hidden','deleted','','','','0');
			$this->addFilter($filterListe,$excludeFilters,'select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
			$this->addFilter($filterListe,$excludeFilters,'export','csv','CSV-Export',$sessionDaten);
      $this->addFilter($filterListe,$excludeFilters,'export','xls','EXCEL-Export',$sessionDaten);
      $this->addFilter($filterListe,$excludeFilters,'export','doc','WORD-Export',$sessionDaten);
      $this->addFilter($filterListe,$excludeFilters,'toggle','toggle','',$sessionDaten);
			/*
			$filterListe[$index++] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[$index++] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
			$filterListe[$index++] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
			$filterListe[$index++] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
			$filterListe[$index++] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
			$filterListe[$index++] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
			*/
		} else if ($this->isApplicationAdmin()) {
			$filterListe[] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[] = $this->formView->createFilter('hidden','deleted','','','','0');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
		  $this->addFilter($filterListe,$excludeFilters,'select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$this->addFilter($filterListe,$excludeFilters,'select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
			$this->addFilter($filterListe,$excludeFilters,'toggle','toggle','',$sessionDaten);
			/*
			$filterListe[$index++] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[$index++] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
			$filterListe[$index++] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
			*/
		} else {
			$filterListe[] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$this->addFilter($filterListe,$excludeFilters,'select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[] = $this->formView->createFilter('hidden','sortField','','','',$this->sortField);
			$filterListe[] = $this->formView->createFilter('hidden','sortMode','','','','ASC');
			$filterListe[] = $this->formView->createFilter('hidden','page','','','','0');
			$filterListe[] = $this->formView->createFilter('hidden','page_id','','','',$this->pageId);
			$filterListe[] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[] = $this->formView->createFilter('hidden','deleted','','','','0');
		}
	
		ksort($filterListe);
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function addFilter(&$filterListe,&$excludeFilters,$type,$name,$title,$sessionDaten,$data='',$defaultValue='',$toggle=FALSE,$additionalCssClass='',$options='') {
		if (count($excludeFilters)>0) {
			if (!in_array($name,$excludeFilters)) {
				$filterListe[] = $this->formView->createFilter($type,$name,$title,$sessionDaten,$data,$defaultValue,$toggle,$additionalCssClass,$options);
			}
		} else {
			$filterListe[] = $this->formView->createFilter($type,$name,$title,$sessionDaten,$data,$defaultValue,$toggle,$additionalCssClass,$options);
		}
	}
	
	function getListViewFields() {
		return tx_femanagement_lib_util::getFieldList($this->config['showFields']);
	}

	function createPreviewLink($uid,$id='') {
		if (empty($id)) {
			$id = '95238';
		}
		$additionalParams = '&no_cache=1&&tx_ttnews%5Btt_news%5D=' . $uid;
		return 'http://www.hs-esslingen.de/index.php?id=' . $id . $additionalParams;
	}
	
	function gibBereich($einrichtung) {
		switch ($einrichtung) {
			case 'Fakultät Angewandte Naturwissenschaften':
			case 'Fakultät Fahrzeugtechnik':
			case 'Fakultät Gebäude Energie Umwelt':
			case 'Fakultät Grundlagen':
			case 'Fakultät Maschinenbau':
			case 'Institut für Angewandte Forschung - Energetische Systeme':
				$bereich = 'SM';
				break;
			case 'Fakultät Betriebswirtschaft':
			case 'Fakultät Graduate School':
			case 'Fakultät Informationstechnik':
			case 'Fakultät Soziale Arbeit, Gesundheit und Pflege':
			case 'Institut für Angewandte Forschung - Gesundheit und Soziales':
				$bereich = 'FL';
				break;
			case 'Fakultät Mechatronik und Elektrotechnik':
			case 'Fakultät Wirtschaftsingenieurwesen':
			case 'Institut für Mechatronik':
				$bereich = 'GP';
				break;
			case 'Institut für nachhaltige Energietechnik und Mobilität (INEM)':
				$bereich = 'INEM';
				break;
			case 'Kompetenzzentrum für energetische und informationstechnische Mobilitätsschnittstellen (KEIM)':
				$bereich = 'KEIM';
				break;
			}
		return $bereich;
	}
	
	function gibDomain($bereich) {
		$domainTitle = '';
		$domain = '';
		switch ($bereich) {
			case 'SM':
				$domainTitle = 'Stadtmitte';
				break;
			case 'FL':
				$domainTitle = 'Flandernstrasse';
				break;
			case 'GP':
				$domainTitle = 'Göppingen';
				break;
			case 'INEM':
				$domainTitle = 'INEM';
				break;
			case 'KEIM':
				$domainTitle = 'KEIM';
				break;
		}
		if (!empty($domainTitle)) {
			$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains');
			$domain = $domainModel->getDomainId($domainTitle);
		}
		return $domain;
	}
		
	function getDomainAdmins($einrichtungsTitel) {
		$bereich = $this->gibBereich($einrichtungsTitel);
		$domain = $this->gibDomain($bereich);
		$admins = $this->getAdminList($domain);
		return $admins;
	}
		
	function getPermissions(&$elem,$page='',&$model='',$hiddenField = 'hidden') {
		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
		
		if (empty($model)) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_forschung');
		}
    $owner = $model->isOwner($elem['uid'],$userId);
    if (!$owner) {
      $owner = $model->istWissenschaftlicherLeiter($elem['uid'],$userId);
    }


		$leitendeEinrichtung = $model->getFieldVal($elem['uid'],'leitende_einrichtung');
		$modelEinrichtung = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		$einrichtungsTitel = $modelEinrichtung->getTitle($leitendeEinrichtung);
		$bereich = $this->gibBereich($einrichtungsTitel);
		
		$domain = $this->gibDomain($bereich);
		
		// Neuer Datensatz?
		if (empty($elem['uid'])) {
			$permissions = array('edit',
					'copy',
					'view',
			);
		} else if ($this->isAdmin()) {
			$permissions = array('edit',
					'copy',
					'delete',
					'undelete',
					'destroy',
					'hide',
					'view',
			);
		} else if ($this->isDomainAdmin()) {
			$permissions = array('edit',
					'copy',
					'hide',
					'view',
			);
		} else if ($owner) {
			if ($this->isReviser()) {
				$permissions = array('edit',
						'view',
						'copy',
						'hide',
				);
			} else {
				$permissions = array('edit',
						'view',
						'copy',
				);
			}
		} else if ($this->isEditor()) {
			$permissions = array('view',
		
			);
		} else {
			$permissions = array('view',
			);
		}
		return $permissions;	
	}
	
	function postProcessingSaveForm($uid,&$formData,$mode) {
		$editPage = $this->config['managementPid'];
		$title = $formData['title']->getValue();
		$einrichtung = $formData['leitende_einrichtung']->getValue();
		$modelEinrichtung = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		$einrichtungsTitel = $modelEinrichtung->getTitle($einrichtung);
		$domainAdmins = $this->getDomainAdmins($einrichtungsTitel);
		
		$userName = $GLOBALS['TSFE']->fe_user->user['name'];
		$userEmail = strtolower($GLOBALS['TSFE']->fe_user->user['email']);
		if (empty($userEmail)) {
			$userEmail = $GLOBALS['TSFE']->fe_user->user['username'] . '@hs-esslingen.de';
		}
		if (array_key_exists($userName,$domainAdmins)) {
			$domainAdmin = TRUE;
		} else {
			$domainAdmin = FALSE;
		}
		$adminEmailListe = array();
		foreach ($domainAdmins as $username=>$userData) {
			$adminEmailListe[] = array('email'=>$userData['email'],'name'=>$userData['name']);
		}
		switch ($mode) {
			case 'new':
			case 'copy':
				/*
				 * E-Mails nur versenden, wenn User nicht Admin ist
				 */
				$domainModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_domains',$this->piBase,$this->piBase->settings['STORAGE_PID']);
//				if (!parent::isAdmin() && !$domainAdmin) {
					/*
					 * E-Mail an Absender
					*/
					$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
					$email->setFrom('forschungseintraege@hs-esslingen.de','Forschungseintrag');
					$email->setTo($userEmail,$userName);
					$email->setSubject('Forschungseintrag gespeichert');
					$email->setBodyHtml('<h3>Neuer Forschungseintrag</h3>' .
							'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
							'Ihr Forschungseintrag "' . $title . '" wurde gespeichert,</p>' .
							'<p>Über die Freigabe zur Veröffentlichung werden Sie automatisch in einer weiteren E-Mail informiert.');
					$erg = $email->sendEmail();
					/*
					 * E-Mail an Admin
					*/
					if (!empty($domainAdmins)) {
						$email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
						$email2->setSubject('Neuer Forschungseintrag');
						$email2->setFrom($userEmail,$userName);
            /* E-Mail an forschung@hs-esslingen.de */
            $email2->setTo('forschung@hs-esslingen.de','forschung@hs-esslingen.de');
            /*
						$email2->setTo($adminEmailListe[0]['email'],$adminEmailListe[0]['name']);
						if (count($adminEmailListe)>1) {
							for($i=1;$i<count($adminEmailListe);$i++) {
								$email2->addTo($adminEmailListe[$i]['email'],$adminEmailListe[$i]['name']);
							}
						}
            */
						$link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $uid;
						$email2->setBodyHtml('<h3>Neuer Forschungseintrag</h3>' .
								'Es wurde ein neuer Forschungseintrag eingereicht:</p>' .
								'<p>Zur Bearbeitung: <a href="' . $link . '">' . $link . '</a></p>');
						$erg = $email2->sendEmail();
					}
//				}
				break;
			case 'publish':
				$model = t3lib_div::makeInstance('tx_femanagement_model_forschung');
				$configArray['show_hidden'] = 1;
				$configArray['all_pids'] = 1;
	
				$userId = $model->selectField($uid,'fe_cruser_id',$configArray);
	
				$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
				$feUserdata = $feUserModel->selectFields('uid',$userId,'fe_users','name,email,username');
				$userEmail = $feUserdata['email'];
				if (empty($userEmail)) {
					$userEmail = $feUserdata['username'] . '@hs-esslingen.de';
				}
				$userName = $feUserdata['name'];
				$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
				$email->setFrom('forschungseintraege@hs-esslingen.de','Forschungseintrag');
				$email->setTo($userEmail,$userName);
				$link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $uid;
	
				$email->setSubject('Forschungseintrag freigeschaltet');
				$email->setBodyHtml('<h3>Forschungseintrag freigeschaltet</h3>' .
						'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
						'Der von Ihnen eingegebene Forschungseintrag <b>"' . $title . '"</b> wurde zur Veröffentlichung frei gegeben.</p>' .
						'<p>Vorschau: <a href="' . $link . '">' . $link . '</a></p>');
	
				$erg = $email->sendEmail();
				break;
			case 'edit':
        $email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
        $email->setSubject('Forschungseintrag geändert');
        $email->setFrom($userEmail,$userName);
        /* E-Mail an Forschungs-Admin */
        $email->setTo('Clemens.Harr@hs-esslingen.de','Clemens.Harr@hs-esslingen.de');

        $link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $uid;
        $email->setBodyHtml('<h3>Forschungseintrag bearbeitet</h3>' .
          'Ein Forschungseintrag wurde bearbeitet und evtl. dabei geändert.</p>' .
          '<p>Zur Bearbeitung: <a href="' . $link . '">' . $link . '</a></p>');
        $erg = $email->sendEmail();
				break;
		}
		$this->postProcessingDataChange($uid);
	}
	
	function postProcessingDataChange($uid) {
/*		
		$model = t3lib_div::makeInstance('tx_femanagement_model_forschung');
		$model->loescheSeitencaches($uid);
*/
	}

  function getExportConfig() {
    if (isset($this->config['xls-export'])) {
      $erg = $this->config['xls-export'];
    } else {
      $erg = '';
    }
    return $erg;
  }


}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/forschung/class.tx_femanagement_controller_forschung.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/forschung/class.tx_femanagement_controller_forschung.php']);
}

?>
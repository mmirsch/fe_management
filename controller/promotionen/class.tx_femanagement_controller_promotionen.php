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
class tx_femanagement_controller_promotionen extends tx_femanagement_controller {
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
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/promotionen/css/promotionen.css"/>
		';
	}
	
	function initSingleView() {															//Vorbereitung des Single Views
		$viewClassName = 'tx_femanagement_view_promotionen_single';						//Übergeben der speziellen View Klasse
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				//Aufruf der Funktion eidViewHandler mit dem Single View
		
		$this->model = t3lib_div::makeInstance											//Erzeugen einer Model-Instanz (in der Variablen protected $model der Vaterklasse)(Datenbankzugriffe)
		(
			'tx_femanagement_model_promotionen',									//Übergeben der richtigen Model Klasse (als Parameter)
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid()				//Übergeben der Konstanten (mit Speicher PID)
		);
		$this->formView = t3lib_div::makeInstance										//Erzeugen einer Single-View Objekt (in der Variablen protected $formView der Vaterklasse)(Ansicht für Einzelansicht)
		(
			$viewClassName,																//Auswahl der entsprechenden Klasse
			$this->piBase,																//Übergeben der piBase (zur Link generierung)
			$this->getPid(),									//Übergeben der Konstanten (mit Speicher PID)
			'Promotions-Eintrag',																//Überschrift über dem Formular
			'promotionen_single',																//Bezeichnung der DIV Klasse um das Formular
			$this->eidViewHandler														//Definition der eidURL
		);
		$this->formView->setControllerName('tx_femanagement_controller_promotionen');			//Funktionsaufruf von setControllerName der Vaterklasse auf das erstellte Viewobjekt
		$this->formView->setModelName('tx_femanagement_model_promotionen');					//Funktionsaufruf von setModelName der Vaterklasse auf das erstellte Viewobjekt
	}
	
	function initListView() {															//Vorbereitung des List Views
		$viewClassName = 'tx_femanagement_view_promotionen_list';							//siehe oben wie bei initSingleView
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_promotionen',
			$this->piBase,$this->getPid()
		);
		
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Promotionen',
			'promotionen_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_promotionen');
		$this->formView->setModelName('tx_femanagement_model_promotionen');
	}
				
	function initFormSingle(&$formData,$mode) {		
		$fieldSettings = array();

		$fieldSettings['title'] = array(
		  'title'=>'Titel der Dissertation',
		  'type'=>'input',
		  'validate'=>'required',
		);

		$fieldSettings['promovend_vorname'] = array(
		  'title'=>'Promovend/in - Vorname',
		  'type'=>'input',
		  'validate'=>'required',
		);

		$fieldSettings['promovend_nachname'] = array(
			'title'=>'Promovend/in - Nachname',
			'type'=>'input',
			'validate'=>'required',
		);

		$fieldSettings['promovend_email'] = array(
			'title'=>'Promovend/in - E-Mail',
			'type'=>'input',
			'validate'=>'required',
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
				'title'=>'Fakultät/Institut',
				'type'=>'multiselect',
				'selectedElems'=>'ausgewählte/s Fakultät/Institut',
				'callbacks'=>$einrichtungenCallbacks,
				'validate'=>'required',
				'options'=>array('searchable'=>TRUE,'height'=>300,'width'=>400),
			);
		
		$fieldSettings['faku_link'] = array(
												'title'=>'Link zu Fakultät/Institut',
												'type'=>'input',
												);

		$fieldSettings['kooperations_uni'] = array(
				'title'=>'Kooperations-Universität',
				'type'=>'dyn_table',
				'colTitles'=> array('uni'=>'Universität','logo'=>'Logo', 'genehmigung'=>'Genehmigung<br />zur externen<br />Veröffentlichung'),
				'colTypes' => array('uni'=>'input','logo'=>'image','genehmigung'=>'checkbox'),
				'numRows'=>1,
				'upload_dir'=>'uploads/tx_femanagement_promotionen/pics/',
				'linkTitle'=>'Zeilen hinzufügen',
		);
		
		$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users',$this->piBase,$this->getPid());
		$usernameDisplay = $modelFeUser->getFieldData($this->feUser);
		
		$validateScriptErstBetreuer = '
				<input type="hidden" required="required" id="erst_betreuer" value="empty" />
				<script type="text/javascript">
				function validateMitarbeiter() {
				var table = $("#dyn_table_erst_betreuer");
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
				$.tools.validator.fn("#erst_betreuer", function(element, value) {
					return validateMitarbeiter();
				});
				</script>';
				
			$fieldSettings['erst_betreuer'] = array(
					'title'=>'Erstbetreuung',
					'type'=>'dyn_table',
//					'colTitles'=> array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)'),
//					'colTypes' => array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'input'),
					'numRows'=>1,
					'linkTitle'=>'Zeilen hinzufügen',
					'validateScript' => $validateScriptErstBetreuer,
			);
		if (parent::isAdmin() || $this->isApplicationAdmin()) {		
			$fieldSettings['erst_betreuer']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail');
			$fieldSettings['erst_betreuer']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input');
		} else {
			$fieldSettings['erst_betreuer']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail');
			$fieldSettings['erst_betreuer']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input');
		}

		$validateScriptZweitBetreuer = '
				<input type="hidden" required="required" id="zweit_betreuer" value="empty" />
				<script type="text/javascript">
				function validateZweitBetreuer() {
				var table = $("#dyn_table_zweit_betreuer");
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
				$.tools.validator.fn("#zweit_betreuer", function(element, value) {
					return validateZweitBetreuer();
				});
				</script>';

		$fieldSettings['zweit_betreuer'] = array(
			'title'=>'Zweitbetreuung (HE)',
			'type'=>'dyn_table',
//					'colTitles'=> array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)'),
//					'colTypes' => array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'input'),
			'numRows'=>1,
			'linkTitle'=>'Zeilen hinzufügen',
			'validateScript' => $validateScriptZweitBetreuer,
		);
		if (parent::isAdmin() || $this->isApplicationAdmin()) {
			$fieldSettings['zweit_betreuer']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail', 'deputats_nachlass'=>'Deputatsnachlass (SWS)');
			$fieldSettings['zweit_betreuer']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input','deputats_nachlass'=>'input');
		} else {
			$fieldSettings['zweit_betreuer']['colTitles'] = array('titel'=>'Titel', 'vorname'=>'Vorname', 'nachname'=>'Nachname', 'email'=>'E-Mail');
			$fieldSettings['zweit_betreuer']['colTypes'] = 	array('titel'=>'input','vorname'=>'input','nachname'=>'input','email'=>'input');
		}

		/*
          $minLength = 3;
        $fieldSettings['zweit_betreuer'] = array(
          'title'=>'Zweitbetreuung (HE)',
          'type'=>'feuser_select',
          'limit'=>'25',
          'minLength'=>$minLength,
          'model'=>'tx_femanagement_model_qsm_fe_users',
          'dynList' => TRUE,
          'newRowTitle' => 'Weitere Person hinzufügen',
          'tooltip'=>'Bitte geben Sie mindestens ' . $minLength . ' Zeichen aus dem Vor-, Nach- oder Benutzernamen des Zweitbetreuers ein.',
          'tooltip_popup'=>'Bitte geben Sie mindestens ' . $minLength . ' Zeichen aus dem Vor-, Nach- oder Benutzernamen des Zweitbetreuers ein.',
        );
    */
		$maxChars = 800;
		$toleranz = 20;
		$fieldSettings['beschreibung_kurz'] = array(
				'title'=>'Kurzbeschreibung (maximal ' . $maxChars . ' Zeichen)',
				'type'=>'rte',
				'configEditor'=>array('maxChars'=>($maxChars+$toleranz)),
				'validate'=>'required',
		);

		$fieldSettings['beschreibung_lang'] = array(
				'title'=>'Projektbeschreibung als Datei',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_promotionen/media/',
				'filetyp' => 'all',
		);

		
		$aktuellesDatum = $dateString = date('d.m.Y',time());		
		$fieldSettings['start_datum'] = array(
												'title'=>'Start der Promotion bzw. Tag der Annahme an der Uni',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$aktuellesDatum,
												'validate'=>'required',
												);
		$endDatum = $dateString = date('d.m.Y',time()+86400*365);		
		$fieldSettings['end_datum'] = array(
												'title'=>'Abschluss der Promotion bzw. Tag der Verteidigung',
												'icons'=>array('delete'=>1),
												'type'=>'date',
												'prefill'=>$endDatum,
												'validate'=>'required',
												);

		$fieldSettings['grafik'] = array(
				'title'=>'Grafik zur Promotion<br/><i>Die Grafik können Sie nur einstellen, wenn Ihnen die Genehmigung des Urhebers vorliegt</i>',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_femanagement_promotionen/pics/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['bildunterschrift'] = array(
				'title'=>'Bildunterschrift zur Grafik',
				'type'=>'input',
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
		$hauptFelder = array('title', 'promovend_vorname', 'promovend_nachname', 'promovend_email',
												 'fakultaet','faku_link','kooperations_uni',
												 'pid','fe_cruser_id');										
		$datumsFelder = array('start_datum','end_datum');
		$personenFelder = array('erst_betreuer','zweit_betreuer');
		$weitereFelder = array('beschreibung_kurz','beschreibung_lang',
                           'grafik','bildunterschrift');
		
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
				'beschreibung_lang' => 'Promotionsbeschreibung (Datei)',
				'beschreibung_kurz' => 'Kurzbeschreibung',
				'fakultaet' => 'Fakultät/Institut',
				'fakultaet' => 'Beteiligte Fakultäten/ Institute',
				'faku_link' => 'Link zu Fakultät/Institut',
				'start_datum' => 'Promotionslaufzeit Start',
				'end_datum' => 'Promotionslaufzeit Ende',
				'kooperations_uni' => 'Kooperations-Universität',
				'erst_betreuer' => 'Erstbetreuung',
				'zweit_betreuer' => 'Zweitbetreuung (HE)',
				'grafik' => 'Grafik',
				'bildunterschrift' => 'Bildunterschrift',

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
				case 'fakultaet':
					$model = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->getPid());
					$fakultaeten = $model->getEinrichtungenList();
					$filterListe[$index] = $this->formView->createFilter('select',$field,$title,$sessionDaten,$fakultaeten,'',FALSE,'fakultaet');
					$index++;
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
//			$this->addFilter($filterListe,$excludeFilters,'export','csv','CSV-Export',$sessionDaten);
//			$this->addFilter($filterListe,$excludeFilters,'export','xls','EXCEL-Export',$sessionDaten);
//			$this->addFilter($filterListe,$excludeFilters,'export','doc','WORD-Export',$sessionDaten);
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
//			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
//			$this->addFilter($filterListe,$excludeFilters,'select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
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
			$model = t3lib_div::makeInstance('tx_femanagement_model_promotionen');
		}
		$owner = $model->isOwner($elem['uid'],$userId);
/*
		if (!$owner) {
		  $owner = $model->istZweitbetreuer($elem['uid'],$userId);
		}
*/

		$fakultaet = $model->getFieldVal($elem['uid'],'fakultaet');
		$modelEinrichtung = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		$einrichtungsTitel = $modelEinrichtung->getTitle($fakultaet);
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
		$einrichtung = $formData['fakultaet']->getValue();
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
//				if ((!parent::isAdmin() && !$domainAdmin) || true) {
					/*
					 * E-Mail an Absender
					 */
					$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
					$email->setFrom('forschung@hs-esslingen.de','Promotionseintrag');
					$email->setTo($userEmail,$userName);
					$email->setSubject('Promotionseintrag gespeichert');
					$email->setBodyHtml('<h3>Neuer Promotionseintrag</h3>' .
							'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
							'Ihr Promotionseintrag "' . $title . '" wurde gespeichert,</p>' .
							'<p>Über die Freigabe zur Veröffentlichung werden Sie automatisch in einer weiteren E-Mail informiert.');
					$erg = $email->sendEmail();
					/*
					 * E-Mail an Admin
					 */
//					if (!empty($domainAdmins)) {
						$email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
						$email2->setSubject('Neuer Promotionseintrag');
						$email2->setFrom($userEmail,$userName);
						/* E-Mail an forschung@hs-esslingen.de */
					//$email2->setTo('forschung@hs-esslingen.de','forschung@hs-esslingen.de');
            $email2->setTo('harr@hs-esslingen.de','harr@hs-esslingen.de');
						$link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $uid;
						$email2->setBodyHtml('<h3>Neuer Promotionseintrag</h3>' .
								'Es wurde ein neuer Promotionseintrag eingereicht:</p>' .
								'<p>Zur Bearbeitung: <a href="' . $link . '">' . $link . '</a></p>');
						$erg = $email2->sendEmail();
//					}
//				}
				break;
			case 'publish':
				$model = t3lib_div::makeInstance('tx_femanagement_model_promotionen');
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
				$email->setFrom('forschung@hs-esslingen.de','Promotionseintrag');
				$email->setTo($userEmail,$userName);
				$link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $uid;
	
				$email->setSubject('Promotionseintrag freigeschaltet');
				$email->setBodyHtml('<h3>Promotionseintrag freigeschaltet</h3>' .
						'<p>Sehr geehrte/r ' . $userName . ',<br/>' .
						'Der von Ihnen eingegebene Promotionseintrag <b>"' . $title . '"</b> wurde zur Veröffentlichung frei gegeben.</p>' .
						'<p>Vorschau: <a href="' . $link . '">' . $link . '</a></p>');
	
				$erg = $email->sendEmail();
				break;
			case 'edit':
				$email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
				$email->setSubject('Promotionseintrag geändert');
				$email->setFrom($userEmail,$userName);
				/* E-Mail an Promotions-Admin */
				$email->setTo('Clemens.Harr@hs-esslingen.de','Clemens.Harr@hs-esslingen.de');

				$link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=edit&tx_femanagement[uid]=' . $uid;
				$email->setBodyHtml('<h3>Promotionseintrag bearbeitet</h3>' .
					'Ein Promotionseintrag wurde bearbeitet und evtl. dabei geändert.</p>' .
					'<p>Zur Bearbeitung: <a href="' . $link . '">' . $link . '</a></p>');
				$erg = $email->sendEmail();
				break;
		}
		$this->postProcessingDataChange($uid);
	}
	
	function postProcessingDataChange($uid) {
/*		
		$model = t3lib_div::makeInstance('tx_femanagement_model_promotionen');
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/promotionen/class.tx_femanagement_controller_promotionen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/promotionen/class.tx_femanagement_controller_promotionen.php']);
}

?>
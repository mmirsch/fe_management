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
class tx_femanagement_controller_qsm_antraege extends tx_femanagement_controller_qsm_main {

	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}
	
	function initSingleView() {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',
																							 $this->piBase,
																							 $this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Antrag',
																							'single_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_qsm_antraege_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',$this->piBase,$this->getPid());
		$this->formView = t3lib_div::makeInstance($viewClassName,
																							$this->piBase,
																							$this->getPid(),
																							'Antrag',
																							'list_left',
																							$this->eidViewHandler);
		$this->formView->setControllerName('tx_femanagement_controller_qsm_antraege');																		
		$this->formView->setModelName('tx_femanagement_model_qsm_antraege');																		
	}
	
	function getFieldSettings(&$fieldSettings,$mode) {
		/* eingeloggten Benutzer zum automatischen Eintragen abfragen */
		$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users',$this->piBase,$this->getPid());
		$usernameDisplay = $modelFeUser->getFieldData($this->feUser);
		
		/* Zeitraumliste erzeugen */
		$zeitraeumeModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_zeitraeume');
		$alleZeitraeume = $zeitraeumeModel->getSelectList($this->getPid());
		$zeitraumListe = array(''=>'Bitte wählen Sie ein Semester aus');
		foreach ($alleZeitraeume as $uid=>$zeitraum) {
			$zeitraumListe[$uid] = $zeitraum;
		}
		
		$fieldSettings['status'] = array(
				'type'=>'hidden',
		);
		
		$fieldSettings['masnanr'] = array(
				'title'=>'Maßnahmen-Nummer',
				'type'=>'input',
				'validate'=>'required',
		);
		
		$fieldSettings['title'] = array(
				'title'=>'Titel der Maßnahme',
				'type'=>'input',
				'validate'=>'required',
		);
		$fieldSettings['short_title'] = array(
				'title'=>'Kurztitel',
				'type'=>'input',
				'validate'=>'required',
		);
		$fieldSettings['ziel'] = array(
				'title'=>'Ziel',
				'type'=>'rte',
				'validate'=>'required',
		);
		$fieldSettings['begruendung'] = array(
				'title'=>'Begründung',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_qsm/media/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['anlage'] = array(
				'title'=>'Anlage',
				'type'=>'file',
				'upload_dir'=>'uploads/tx_qsm/media/',
				'filetyp' => 'all',
		);
		
		$fieldSettings['antragsteller'] = array(
				'title'=>'Antragsteller',
				'type'=>'feuser_select',
				'limit'=>'25',
				'minLength'=>3,
				'model'=>'tx_femanagement_model_qsm_fe_users',
				'username' => $this->feUser,
				'usernameDisplay' => $usernameDisplay,
				'prefill' => array('value'=>$this->feUser,'valueSelect'=>$usernameDisplay),
				'validate'=>'required',
		);
		$fieldSettings['verantw'] = array(
				'title'=>'Verantwortlicher',
				'type'=>'feuser_select',
				'limit'=>'25',
				'minLength'=>3,
				'model'=>'tx_femanagement_model_qsm_fe_users',
				'username' => $this->feUser,
				'usernameDisplay' => $usernameDisplay,
				'dynList' => TRUE,
				'newRowTitle' => 'Weiteren Verantwortlichen hinzufügen',
		);
		$bereichsModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichsListe = $bereichsModel->getSelectList($this->getPid());
		$fieldSettings['bereich'] = array(
				'title'=>'Antrag an',
				'type'=>'select',
				'selectData'=>$bereichsListe,
				'emptySelectTitle'=>'Bitte wählen Sie einen Bereich aus',
				'validate'=>'required',
		);
		
		$einrichtungenModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_einrichtungen');
		$einrichtungenListe = $einrichtungenModel->getSelectList($this->getPid());
		$fieldSettings['einrichtung'] = array(
				'title'=>'für die Einrichtung/Fakultät',
				'type'=>'select',
				'selectData'=>$einrichtungenListe,
				'emptySelectTitle'=>'Bitte wählen Sie eine Einrichtung aus',
				'validate'=>'required',
		);
		
		$fieldSettings['bezugssemester'] = array(
				'title'=>'Bezugssemester',
				'type'=>'select',
				'selectData'=>$zeitraumListe,
				'emptySelectTitle'=>'Bitte das Semester auswählen',
				'validate'=>'required',
		);
		
		$fieldSettings['start'] = array(
				'title'=>'Beginn der Maßnahme',
				'type'=>'date',
				'icons'=>array('delete'=>1),
				'validate'=>'required',
		);
		$fieldSettings['ende'] = array(
				'title'=>'Ende der Maßnahme',
				'type'=>'date',
				'icons'=>array('delete'=>1),
				'validate'=>'required',
		);
		$fieldSettings['entscheidung'] = array(
				'title'=>'Datum der Entscheidung',
				'type'=>'date',
				'icons'=>array('delete'=>1),
				'validate'=>'required',
		);
		
		$fieldSettings['beanbudget'] = array(
				'title'=>'Beantragte Budgets',
				'type'=>'dyn_table',
				'model'=>'tx_femanagement_model_qsm_budgets',
				'colTitles'=> array('zeitraumlabel'=>'Zeitraum','zeitraum'=>'Semester', 'budget'=>'Betrag in €', 'mode'=>'', 'version'=>''),
				'colTypes' => array('zeitraumlabel'=>'label','zeitraum'=>'select','budget'=>'input','mode'=>'hidden','version'=>'hidden'),
				'colData' => array('zeitraum'=>$zeitraumListe,'mode'=>'beanbudget','version'=>'0'),
				'numRows' => 3,
				'linkTitle' => 'Weitere Budgets hinzufügen',
				'validateScript' => $this->gibBudgetValidierungsSkript('beanbudget'),
		);
		
		$fieldSettings['bewbudget'] = array(
				'title'=>'Bewilligte Budgets',
				'type'=>'dyn_table',
				'model'=>'tx_femanagement_model_qsm_budgets',
				'colTitles'=> array('zeitraumlabel'=>'Zeitraum','zeitraum'=>'Semester', 'budget'=>'Betrag in €', 'mode'=>'', 'version'=>''),
				'colTypes' => array('zeitraumlabel'=>'label','zeitraum'=>'select','budget'=>'input','mode'=>'hidden','version'=>'hidden'),
				'colData' => array('zeitraumlabel'=>'','zeitraum'=>$zeitraumListe,'mode'=>'bewbudget','version'=>'0'),
				'numRows' => 3,
				'linkTitle' =>'Weitere Budgets hinzufügen',
				'validateScript' => $this->gibBudgetValidierungsSkript('bewbudget'),
		);
		
		$fieldSettings['anmerkungen'] = array(
				'title'=>'Anmerkungen zu den bewilligten Budgets',
				'type'=>'text',
		);
		$fieldSettings['persstellen'] = array(
				'title'=>'Beschäftigung von Personal',
				'type'=>'input',
				'fieldDescription' => 'Anzahl der Personalstellen',
		);
		
		$fieldSettings['mittel'] = array(
				'title'=>'Aufteilung der Mittel',
				'type'=>'dyn_table',
				'model'=>'tx_femanagement_model_qsm_mittel',
				'colTitles'=>array('title'=>'Bezeichnung', 'betrag'=>'Betrag in €', 'kostenstelle'=>'Kostenstelle'),
				'colTypes' => array('title'=>'input','betrag'=>'input','kostenstelle'=>'input'),
				'numRows'=>5,
				'linkTitle'=>'Zeilen hinzufügen',
				'fieldDescription' => 'Bitte geben Sie in den nachfolgenden Feldern die Aufteilung der Mittel an (z.B. in Mittel für Lehrbeauftragte, Studentische Hilfskräfte, Sachmittel mit Angabe der Laborbezeichnung usw.).',
		);
		$bereichsModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege');
		$bereichsListe1 = $bereichsModel->getListFina1();
		$fieldSettings['fina_bereich1'] = array(
				'title'=>'Bereich aus dem die Maßnahme finanziert wird',
				'type'=>'radio',
				'selectData'=>$bereichsListe1,
				'emptySelectTitle'=>'Bitte wählen Sie einen Bereich aus',
				'validate'=>'required',
		);
		$bereichsListe2 = $bereichsModel->getListFina2();
		$fieldSettings['fina_bereich2'] = array(
				'title'=>'Die Einnahmen aus Studiengebühren werden wie folgt verwendet',
				'type'=>'select',
				'selectData'=>$bereichsListe2,
				'emptySelectTitle'=>'Bitte wählen Sie einen Bereich aus',
				'validate'=>'required',
		);
	}

	function createValidationDependencies() {		
		$validationDependencies = array(
																		'bereich' => array(
																				'change' => array(
																						array(
																							'condition' => '$(elem).val() != "ZM"',
																							'actions' => array(
																								'valid' => array('einrichtung'),
																								'hide' => array('field_einrichtung'),
																							),
																						),
																						array(
																							'condition' => '$(elem).val() == "ZM"',
																							'actions' => array(
																								'required' => array('einrichtung'),
																								'show' => array('field_einrichtung'),
																							),
																						),
																				),
																			),
																		);
		$this->formView->setValidationDependencies($validationDependencies);		
	}

	function createFilterBereiche(&$sessionDaten,$titel) {
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichData = $bereicheModel->getSelectList($this->getPid());
		return $this->formView->createFilter('select','bereich',$titel,$sessionDaten,$bereichData);
	}
	
	function createFilterZeitraeume(&$sessionDaten,$titel) {
		$zeitraeumeModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_zeitraeume');
		$alleZeitraeume = $zeitraeumeModel->getSelectList($this->getPid());
		$zeitraumListe = array();
		foreach ($alleZeitraeume as $uid=>$zeitraum) {
			$zeitraumListe[$uid] = $zeitraum;
		}
		return $this->formView->createFilter('select','bezugssemester',$titel,$sessionDaten,$zeitraumListe,0,FALSE,'bezugssemester');
	}
	
	function createFilterAntragsstatus(&$sessionDaten,$titel) {
		$statusListe = tx_femanagement_model_qsm_antraege::getListAntragsStatus();
		return $this->formView->createFilter('select','status',$titel,$sessionDaten,$statusListe);
	}
	
	function initFormSingle(&$formData,$mode) {		
		$this->getFieldSettings($fieldSettings,$mode);
		$formData = $this->createFormFields($fieldSettings,$mode);
		$this->createValidationDependencies();
	}
	
	function gibBudgetValidierungsSkript($feld) {
		$validateScriptBudgets = '
		<input type="hidden" required="required" id="' . $feld . '" value="empty" />
		<script type="text/javascript">
		function validate_' . $feld . '() {
			var table = $("#dyn_table_' . $feld . '");
			var rows = $("tr:gt(0)",table);
			var meldungAlleFelder = "Bitte geben Sie beide Felder \'Budget\' und \'Zeitraum\' ein!";
			var meldungMindestensEinEintrag = "Bitte geben Sie mindestens ein Budget ein!";
			var valid = true;
			var filledRows = 0;
			
			rows.each(function(){
				if (valid) {
					var requiredCols = ["zeitraum", "budget" ];
					var checkCols = ["zeitraum", "budget" ];
					var zeitraum = "";
					var budget = "";
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
		$.tools.validator.fn("#' . $feld . '", function(element, value) {
			return validate_' . $feld . '();
		});
		</script>';
		return $validateScriptBudgets;	
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {		
		$personenFelder = array(
													 'bereich','einrichtung','bezugssemester',
													 'antragsteller','verantw',
											);										
		$antragsFelder = array(
													 'title','short_title','ziel',
													 'begruendung','anlage',
		);										
		$datumsFelder = array('start','ende','entscheidung');	
		$budgetMittelFelder = array('beanbudget','bewbudget','anmerkungen','persstellen','mittel',);	
		$bereichsFelder = array('fina_bereich1','fina_bereich2',);
		
		$personenDatenContainer = $this->createContainer($personenFelder,$formData);
		$antragsDatenContainer = $this->createContainer($antragsFelder,$formData);
		$datumsContainer = $this->createContainer($datumsFelder,$formData);
		$budgetMittelContainer = $this->createContainer($budgetMittelFelder,$formData);
		$bereichsContainer = $this->createContainer($bereichsFelder,$formData);
		
		$containerList = array($personenDatenContainer,
													 $antragsDatenContainer,
													 $datumsContainer,
													 $budgetMittelContainer,
													 $bereichsContainer,
					);
		$this->formView->addFieldset($containerList);		#Create new Fieldset with all container
		$this->formView->addFormSingleButtons(array('speichern'=>'Maßnahme speichern'));
	}

	function showListView($aktuelleSeite) {
		if (empty($aktuelleSeite)) {
			$aktuelleSeite = $this->defaultPage;
		}
		parent::initMenu($aktuelleSeite);
		$filterListe = array();
		$buttonListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$filterListe[11] = $this->formView->createFilter('search','personensuche','Personensuche',$sessionDaten);
		$bereicheModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien');
		$bereichData = $bereicheModel->getSelectList($this->getPid());
		$filterListe[20] = $this->formView->createFilter('select','bereich','Bereich',$sessionDaten,$bereichData);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[21] = $this->formView->createFilter('date','dateStart','von',$sessionDaten,'','',TRUE);
		$filterListe[22] = $this->formView->createFilter('date','dateEnd','bis',$sessionDaten,'','',TRUE);
		$filterListe[30] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		
		if ($this->isAdmin()) {
			$filterDeleted = array('1'=>'Nur gelöschte','0'=>'Nur nicht gelöschte');
			$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
			$filterListe[23] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
			$filterListe[24] = $this->formView->createFilter('select','deleted','gelöschte',$sessionDaten,$filterDeleted,0,TRUE);
			$filterListe[31] = $this->formView->createFilter('export','csv','CSV-Export',$sessionDaten);
			$filterListe[32] = $this->formView->createFilter('export','xls','EXCEL-Export',$sessionDaten);
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
		} else if ($this->isEditor()) {
			$filterListe[40] = $this->formView->createFilter('toggle','toggle','',$sessionDaten);
			$filterListe[103] = $this->formView->createFilter('hidden','hidden','','','','0');
			$filterListe[104] = $this->formView->createFilter('hidden','deleted','','','','0');
		}			
		ksort($filterListe);									
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}

	function showDataSingle(&$formData,&$parameter,$mode,$aktuelleSeite) {
		$popup = $this->testParam('popup');
		if (!$popup) {
			parent::initMenu($aktuelleSeite);
		}
		$this->createFormSingle($formData,$parameter,$mode);
		$out = $this->formView->show($mode,$aktuelleSeite);
		if ($popup) {
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
	
	function getListViewFields() {
			return array('title'=>'Titel',
					'bezugssemester'=>'Semester',
					'masnanr'=>'M-Nr.',
					);
	}

	function exitSqlAjaxFilter(&$view,&$data,&$select,&$limit) {
		$this->limit = '0';
		$this->page = 0;
		$limitStart = 0;
		if (isset($data['limit']) || isset($data['num_entries'])) {
			$this->anzahl = $this->model->getSqlCount($select);
			if (isset($data['num_entries'])) {
				if ($data['num_entries']!='all') {
					$this->limit = $data['num_entries'];
				}
			} else {
				$this->limit = $data['limit'];
			}
			if (isset($data['page'])  && $this->limit>0) {
				$this->page = $data['page'];
				/* Überlauf prüfen */
				if ($this->page*$this->limit>$this->anzahl) {
					$this->page = floor($this->anzahl/$this->limit);
				}
				$limitStart = $this->page*$this->limit;
			}
		}
		if (!empty($data['sortField'])) {
			if (!empty($data['sortMode'])) {
				$configArray['orderBy'] = $data['sortField'] . ' ' . 
																	$data['sortMode'];
			} else {
				$configArray['orderBy'] = $data['sortField'] . ' ' . 
																	'ASC';
			}
		}
		if ($this->limit>0) {
			$limit = ' LIMIT ' . $limitStart . ',' . $this->limit;
		}
		$out = '';
		if ($this->limit>0 && $this->anzahl>$this->limit) {
			$out .= $view->createPageBrowser($this->anzahl,$this->limit,$this->page);
		}
		return $out;
	}
	
	function createAjaxData(&$view,&$data) {
		$this->model = t3lib_div::makeInstance('tx_femanagement_model_qsm_antraege',
																						'',$data['pid']);
		
		if (isset($data['tx_femanagement']['page'])) {
			$page = $data['tx_femanagement']['page'];
		} else {
			$page = '';
		}
		$configArray = array();
		$this->model->createDataListConfig($data['args'],$page,$configArray);
		if (!empty($data['args']['export'])) {
			$configArray['fields'] = 'uid,masnanr,status,title,short_title,ziel,bezugssemester,start,ende,bereich,einrichtung';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$fieldList = explode(',','uid,masnanr,status,title,short_title,ziel,bezugssemester,start,ende,bereichs_titel,einrichtungs_titel,antragsteller_name');
			$view->createDataExport($daten,$data['args']['export'],'QSM-Anträge',$fieldList);
			exit();
		} else {
			$configArray['fields'] = 'uid,masnanr,status,title,start,ende,bezugssemester,bereich,antragsteller,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
			$out .= $this->exitSqlAjaxFilter($view,$data['args'],$sqlQuery,$limit);
			if (!empty($limit)) {
				$sqlQuery .= $limit;
			}
			$daten = $this->model->selectSqlData($sqlQuery);
			foreach ($daten as $index=>$elem) {
				$daten[$index]['permissions'] = $this->getPermissions($elem,$page,$this->model);
			}
			$out .= $view->createDataList($daten);
			return $out;
		}		
		
	}
	

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_qsm_antraege.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_qsm_antraege.php']);
}

?>
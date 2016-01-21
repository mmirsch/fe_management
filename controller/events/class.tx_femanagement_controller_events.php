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

class tx_femanagement_controller_events extends tx_femanagement_controller {
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
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/events/css/events.css"/>
		';
	}
	
	function initSingleView() {															
		$viewClassName = 'tx_femanagement_view_events_single';						
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				
		
		$this->model = t3lib_div::makeInstance											
		(
			'tx_femanagement_model_events',								
			$this->piBase,																
			$this->getPid()				
		);
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Veranstaltung',
			'events_single',
			$this->eidViewHandler
		);
		$this->formView->setControllerName('tx_femanagement_controller_events');			
		$this->formView->setModelName('tx_femanagement_model_events');					
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_events_list';							
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_events',
			$this->piBase,$this->getPid()
		);
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Veranstaltungen',
			'events_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_events');																		
		$this->formView->setModelName('tx_femanagement_model_events');																		
	}
				
	function initFormSingle(&$formData,$mode) {		
		$fieldSettings = array();
		
		$fieldSettings['title'] = array(
												'title'=>'Titel der Veranstaltung',
												'type'=>'input',
												'validate'=>'required',
												);

    $fieldSettings['subtitle'] = array(
                'title'=>'Thema der Veranstaltung',
                'type'=>'input',
                'validate'=>'required',
    );

    $fieldSettings['description'] = array(
												'title'=>'Beschreibung',
												'type'=>'rte',
												);

    $fieldSettings['pic'] = array(
      'title'=>'Grafik zur Veranstaltung',
      'type'=>'file',
      'upload_dir'=>'uploads/tx_femanagement_events/',
      'filetyp' => 'all',
    );

    $fieldSettings['contact'] = array(
                        'title'=>'Kontaktinformationen',
                        'type'=>'rte',
                        );

    $fieldSettings['street'] = array(
												'title'=>'Straße/Hausnummer',
												'type'=>'input',
												);		
		
		$fieldSettings['city'] = array(
												'title'=>'Ort',
												'type'=>'input',
												);		
		
		$fieldSettings['zip'] = array(
												'title'=>'PLZ',
												'type'=>'input',
												);		
		
		$fieldSettings['building'] = array(
												'title'=>'Gebäude',
												'type'=>'input',
												);		
		
		$fieldSettings['room'] = array(
												'title'=>'Raum',
												'type'=>'input',
												);		
				
		$fieldSettings['email_text'] = array(
				'title'=>'E-Mail-Text für die Kunden',
				'type'=>'rte',
				'validate'=>'required',
				'prefill'=>'<h2>Vielen Dank für Ihre Anmeldung bei </h2>...<br/><p>Die Versanstaltung findet am ###datum### von ###beginn### bis ###ende### statt.</p>',
			);
		
		$fieldSettings['dates'] = array(
				'title'=>'Termine',
				'type'=>'dyn_table',
				'model'=>'tx_femanagement_model_events_dates',
				'colTitles'=>array('event_date'=>'Datum (TT.MM.JJJJ)', 'start'=>'Beginn (SS:MM)', 'end'=>'ENDE (SS:MM)'),
				'colTypes' => array('event_date'=>'input','start'=>'input','end'=>'input'),
				'numRows'=>5,
				'linkTitle'=>'Zeilen hinzufügen',
				'fieldDescription' => 'Bitte hier die Termine für die Veranstaltung eintragen. <br /><span class="rot">Bitte beachten Sie das Einabeformat der Datumsfelder<span>',
		);

		
		$fieldSettings['pid'] = array(
											 	'type'=>'hidden',
												'prefill'=>$this->getPid(),
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

		$formData = $this->createFormFields($fieldSettings);
	}

	function createFormSingle(&$formData,&$parameter,$mode) {		
		$hauptFelder = array(
				'title',
        'subtitle',
				'description',
        'pic',
        'contact',
        'street',
				'city',
				'zip',
				'building',
				'room',
				'email_text',
				'dates',
				'pid',
			);				
		
		$hauptContainer = $this->createContainer($hauptFelder,$formData);

		$containerListMain = array($hauptContainer);
		$this->formView->addFieldset($containerListMain);
		
		$buttonFelder = array('save','abort');
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
        'subtitle' => 'Thema',
        'description' => 'Beschreibung',
				'building' => 'Gebäude',
				'room' => 'Raum',
        'contact' => 'Kontakt',
        'city' => 'Stadt',
        'street' => 'Straße',
        'email_text' => 'Text für die E-Mail nach der Anmeldung',
        'pic' => 'Grafik zur Veranstaltung',
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
	
	function showListView($aktuelleSeite) {		
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		parent::initGlobalFilters($sessionDaten,$filterListe);
		$buttonListe = array($this->formView->createButton('newElem',$this->params,TRUE));		
		
		$filterListe[] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$filterListe[] = $this->formView->createFilter('date','dateStart','von',$sessionDaten,'','',TRUE);
		$filterListe[] = $this->formView->createFilter('date','dateEnd','bis',$sessionDaten,'','',TRUE);
		$filterHidden = array('1'=>'Nur verborgene','0'=>'Nur nicht verborgene');
		$filterListe[] = $this->formView->createFilter('select','hidden','verborgene',$sessionDaten,$filterHidden,0,TRUE);
		$anzSelect = array('5'=>'5','10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		$filterListe[] = $this->formView->createFilter('hidden','deleted','','','','0');
		ksort($filterListe);
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}
	
	function addFilter(&$filterListe,&$excludeFilters,$type,$name,$title,$sessionDaten,$data='',$defaultValue='',$toggle=FALSE,$additionalCssClass='',$options='') {
		if (!in_array($name,$excludeFilters)) {
			$filterListe[] = $this->formView->createFilter($type,$name,$title,$sessionDaten,$data,$defaultValue,$toggle,$additionalCssClass,$options);
		}
	}
	
	function getListViewFields() {
		return tx_femanagement_lib_util::getFieldList($this->config['showFields']);
	}
	
	function getPermissions(&$elem,$page='',&$model='',$hiddenField = 'hidden') {
		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];

		if (!empty($model)) {
			$owner = $model->isOwner($elem['uid'],$userId);
		} else {
			$model = t3lib_div::makeInstance('tx_femanagement_model_events');
			$owner = $model->isOwner($elem['uid'],$userId);
		}

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
		} else if ($this->isReviser()) {
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
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/events/class.tx_femanagement_controller_events.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/events/class.tx_femanagement_controller_events.php']);
}

?>
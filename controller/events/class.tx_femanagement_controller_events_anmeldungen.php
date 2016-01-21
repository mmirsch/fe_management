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

class tx_femanagement_controller_events_anmeldungen extends tx_femanagement_controller {
var $config = array();
var $eventId;
var $eventDateId;

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
    if (isset($this->params['eventId'])) {
      $this->eventId = $this->params['eventId'];
    }
    if (isset($this->params['eventDateId'])) {
      $this->eventDateId = $this->params['eventDateId'];
    }

    $GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/events/css/events.css"/>
		';
	}
	
	function initSingleView() {															
		$viewClassName = 'tx_femanagement_view_events_anmeldungen_single';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;				
		
		$this->model = t3lib_div::makeInstance											
		(
			'tx_femanagement_model_events_anmeldungen',
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
		$this->formView->setControllerName('tx_femanagement_controller_events_anmeldungen');
		$this->formView->setModelName('tx_femanagement_model_events_anmeldungen');
	}
	
	function initListView() {		
		$viewClassName = 'tx_femanagement_view_events_anmeldungen_list';
		$this->eidViewHandler =  $this->eidUrl . '&view=' . $viewClassName;
		$this->model = t3lib_div::makeInstance
		(
			'tx_femanagement_model_events_anmeldungen',
			$this->piBase,$this->getPid()
		);
		$this->formView = t3lib_div::makeInstance
		(
			$viewClassName,
			$this->piBase,
			$this->getPid(),
			'Anmeldungen',
			'event_registration_list',
			$this->eidViewHandler
		);
		
		$this->formView->setControllerName('tx_femanagement_controller_events_anmeldungen');
		$this->formView->setModelName('tx_femanagement_model_events_anmeldungen');
  }

  function setEventDate($eventId,$eventDateId) {
    $this->eventId = $eventId;
    $this->eventDateId = $eventDateId;
  }
				
	function initFormSingle(&$formData,$mode) {
 		$fieldSettings = array();

    $fieldSettings['error'] = array(
      'title'=>'Zugriffsfehler',
      'type'=>'readonly',
      'prefill'=>'Diese Veranstaltung wurde bereits gebucht',
    );

    /** @var  $eventModel tx_femanagement_model_events */
    $eventModel = t3lib_div::makeInstance('tx_femanagement_model_events',$this->piBase,$this->getPid());

    if (isset($this->params['tx_femanagement']['uid'])) {
      $bookingData = $this->model->getBookingData($this->params['tx_femanagement']['uid']);
      $this->eventId = $bookingData['event'];
      $this->eventDateId = $bookingData['event_date'];
    }

    $eventData = $eventModel->getEventData($this->eventId);

    $fieldSettings['title'] = array(
												'title'=>'Titel der Veranstaltung',
												'type'=>'readonly',
												'prefill'=>$eventData['title'],
												);

    $fieldSettings['subtitle'] = array(
      'title'=>'Thema der Veranstaltung',
      'type'=>'readonly',
      'prefill'=>$eventData['subtitle'],
    );

    /** @var  $eventDateModel tx_femanagement_model_events_dates */
    $eventDateModel = t3lib_div::makeInstance('tx_femanagement_model_events_dates',$this->piBase,$this->getPid());
    $eventDateData = $eventDateModel->getFieldDataSingle($this->eventDateId);
    $datum = date('d.m.Y',$eventDateData['event_date']);
    $von = gmdate('H:i',intval($eventDateData['start'])) . ' Uhr';
    $bis = gmdate('H:i',intval($eventDateData['end'])) . ' Uhr';

    $veranstaltungsZeitraum = $datum . ' von ' . $von . ' bis ' . $bis;

    $fieldSettings['date'] = array(
      'title'=>'Datum/Uhrzeit der Veranstaltung',
      'type'=>'readonly',
      'prefill'=>$veranstaltungsZeitraum,
    );

    $fieldSettings['date_hidden'] = array(
      'type'=>'hidden',
      'prefill'=>$datum,
    );

    $fieldSettings['start_hidden'] = array(
      'type'=>'hidden',
      'prefill'=>$von,
    );

    $fieldSettings['end_hidden'] = array(
      'type'=>'hidden',
      'prefill'=>$bis,
    );


    $fieldSettings['event'] = array(
      'type'=>'hidden',
      'value'=>$this->eventId,
    );

    $fieldSettings['event_date'] = array(
      'type'=>'hidden',
      'value'=>$this->eventDateId,
    );

    $fieldSettings['organization'] = array(
                        'title'=>'Name Ihrer Einrichtung',
                        'type'=>'input',
                        'validate'=>'required',
                        );

    $fieldSettings['organization'] = array(
      'title'=>'Name Ihrer Einrichtung',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['first_name'] = array(
      'title'=>'Vorname',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['last_name'] = array(
      'title'=>'Nachname',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['street'] = array(
      'title'=>'Straße/Hausnummer',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['zip'] = array(
      'title'=>'Plz',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['city'] = array(
      'title'=>'Ort',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['phone'] = array(
      'title'=>'Tel.',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['email'] = array(
      'title'=>'E-Mail-Adresse',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['count_pt'] = array(
      'title'=>'Anzahl der Kinder',
      'type'=>'input',
      'validate'=>'required',
    );

    $fieldSettings['remarks'] = array(
      'title'=>'Bemerkungen',
      'type'=>'text',
    );

		$fieldSettings['pid'] = array(
											 	'type'=>'hidden',
												'prefill'=>$this->getPid(),
											);

    $fieldSettings['save'] = array(
      'value'=>'Termin speichern',
      'type'=>'button',
      'buttonType'=>'submit',
    );
    if ($mode=='new') {
      $fieldSettings['save']['value'] = 'Termin verbindlich buchen';
    }
		$fieldSettings['abort'] = array(
				'value'=>'Abbrechen',
				'type'=>'button',
				'buttonType'=>'abort',
		);

		$formData = $this->createFormFields($fieldSettings);
	}

	function createFormSingle(&$formData,&$parameter,$mode) {
    $error = false;
    if ($mode=='new') {
      if ($this->model->anmeldungVorhanden($this->eventId, $this->eventDateId)) {
        $fehler = array(
          'error',
        );
        $meldeContainer = $this->createContainer($fehler,$formData);
        $this->formView->addFieldset(array($meldeContainer));
        $error = true;
      }
    }
    if (!$error) {
      $hauptFelder = array(
        'title',
        'subtitle',
        'date',
        'date_hidden',
        'start_hidden',
        'end_hidden',
        'event',
        'event_date',
        'organization',
        'first_name',
        'last_name',
        'street',
        'city',
        'zip',
        'email',
        'phone',
        'count_pt',
        'remarks',
      );

      $hauptContainer = $this->createContainer($hauptFelder,$formData);

      $containerListMain = array($hauptContainer);
      $this->formView->addFieldset($containerListMain);

      $buttonFelder = array('save','abort');
      $containerButtons = $this->createContainer($buttonFelder,$formData,FALSE,'buttons');
      $containerList = array($containerButtons);
      $this->formView->addFieldset($containerList,'','');
    }

	}
	
	function showDataSingle(&$formData,&$parameter,$mode,$aktuelleSeite='') {
		if ($mode!='view') {
			return parent::showDataSingle($formData,$parameter,$mode,$aktuelleSeite);
		}
		$uid = $parameter['uid'];
		$fieldList = array(				
				'organization' => 'Einrichtung',
        'event' => 'Veranstaltung',
        'event_date' => 'Datum/Zeit',
        'organization' => 'organization',
        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'street' => 'Straße',
        'city' => 'Ort',
        'zip' => 'Plz',
        'email' => 'E-Mail-Adresse',
        'phone' => 'Tel.',
        'count_pt' => 'Anzahl Kinder',
        'remarks'	 => 'Bemerkungen',
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
		$buttonListe = array();
		
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
    if (!empty($userId)) {
      $permissions = array('edit',
        'copy',
        'delete',
        'undelete',
        'destroy',
        'hide',
        'view',
      );
    } else {
      $permissions = array('view',
      );
    }
    return $permissions;
/*
if (!empty($model)) {
			$owner = $model->isOwner($elem['uid'],$userId);
		} else {
			$model = t3lib_div::makeInstance('tx_femanagement_model_events_anmeldungen');
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
*/
	}

  function postProcessingSaveForm($uid,&$formData,$mode) {
    $editPage = 137295;
    $title = $formData['title']->getValue();
    $veranstaltungsDatum = $formData['date']->getValue();
    $userName = $formData['first_name']->getValue() . ' ' . $formData['last_name']->getValue();
    $userEmail = $formData['email']->getValue();
    $eventId = $formData['event']->getValue();
    /** @var  $eventModel tx_femanagement_model_events */
    $eventModel = t3lib_div::makeInstance('tx_femanagement_model_events',$this->piBase,$this->getPid());
    $eventData = $eventModel->getEventData($eventId);
    $emailText = $eventData['email_text'];


    $markerArray['###datum###'] = $formData['date_hidden']->getValue();
    $markerArray['###beginn###'] = $formData['start_hidden']->getValue();
    $markerArray['###ende###'] = $formData['end_hidden']->getValue();

    $emailBody = $this->piBase->cObj->substituteMarkerArrayCached($emailText,$markerArray);

    if (empty($userEmail)) {
      $userEmail = $GLOBALS['TSFE']->fe_user->user['username'] . '@hs-esslingen.de';
    }

    $adminEmailListe = array(
      array('email'=>'Ulrike.Schmid@hs-esslingen.de','name'=>'Ulrike Schmid'),
      array('email'=>'Anja.Eble@hs-esslingen.de','name'=>'Anja Eble'),
      array('email'=>'Manfred.Mirsch@hs-esslingen.de','name'=>'Manfred Mirsch'),
    );

    $adminEmailListeXX = array(
      array('email'=>'mmirsch@hs-esslingen.de','name'=>'Manfred Mirsch'),
    );

    $adminEmail = 'Ulrike.Schmid@hs-esslingen.de';
    switch ($mode) {
      case 'new':
         /*
         * E-Mail an Absender
        */
        $email = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
        $email->setFrom($adminEmail,'Technolino');
        $email->setTo($userEmail,$userName);
        $email->setSubject('Termin für Technolino gebucht');
        $email->setBodyHtml($emailBody);
        $erg = $email->sendEmail();
        /*
         * E-Mail an Admin
        */
        $email2 = t3lib_div::makeInstance('tx_femanagement_controller_lib_email');
        $email2->setSubject('Neue Buchung für Technolino');
        $email2->setFrom($userEmail,$userName);
        $email2->setTo($adminEmailListe[0]['email'],$adminEmailListe[0]['name']);
        if (count($adminEmailListe)>1) {
          for($i=1;$i<count($adminEmailListe);$i++) {
            $email2->addTo($adminEmailListe[$i]['email'],$adminEmailListe[$i]['name']);
          }
        }
        $link = 'http://www.hs-esslingen.de/index.php?id=' . $editPage . '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $uid;
        $email2->setBodyHtml('<h3>Neue Buchung für Technolino</h3>' .
          '<p>Zur Ansicht: <a href="' . $link . '">' . $link . '</a></p>');
        $erg = $email2->sendEmail();
        break;
      case 'copy':
      case 'edit':
        break;
    }
  }

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/events/class.tx_femanagement_controller_events_anmeldungen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/events/class.tx_femanagement_controller_events_anmeldungen.php']);
}

?>
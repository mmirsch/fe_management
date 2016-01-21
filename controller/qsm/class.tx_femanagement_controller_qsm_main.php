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
class tx_femanagement_controller_qsm_main extends tx_femanagement_controller {
var $admin;
var $applicationAdmin;
var $gremienMitglied;
var $defaultPage = 'verwendung';
var $editMode = 'edit';

public static $antragsStati = array(
		1=>'Entwurf',
		2=>'beantragt',
		3=>'genehmigt',
		4=>'abgelehnt',
		5=>'zurückgestellt',
		6=>'in Bearbeitung (FINA)',
		7=>'verbucht',
		8=>'Maßnahme beendet',
);


public static $status_entwurf = 1;
public static $status_eingereicht = 2;
public static $status_bew_gremium = 3;
public static $status_abgelehnt = 4;
public static $status_zurueckgestellt = 5;
public static $status_bearb_fina = 6;
public static $status_bew_fina = 7;
public static $status_beendet = 8;


	function __construct(&$piBase='',&$params='') {		
		$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
			<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/qsm/css/qsm.css"/>
		';
		parent::__construct($piBase,$params);
		$pid = $this->getPid($this->params['pid']);
		$gremienModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_gremien',
																					 $piBase,
																					 $pid);
		$this->admin = $this->isAdmin();
		$this->applicationAdmin = $this->isApplicationAdmin('tx_femanagement_controller_qsm_main');
		$this->gremienMitglied = $gremienModel->istGremienMitglied($this->feUser);
	}
	
	function getPermissions(&$elem,$page='',&$model='',$hiddenField = 'hidden') {
		$status = $elem['status'];
		if (empty($elem['uid'])) {
			$permissions = array('edit',
													 'copy',
													 'view',
			);
			return $permissions;
		}
		if (empty($model)) {
			$model = $this->model;
		}
	
		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
		$owner = $model->isOwner($elem['uid'],$userId);
		
		switch ($page) {
		case 'meineAntraege':
			$permissions = $this->permissionsMeineAntraege($elem,$model,$hiddenField,$userId);
			break;
		case 'alleAntraege':
			$permissions = $this->permissionsAlleAntraege($elem,$model,$hiddenField,$userId);
			break;
		case 'gremienAntraege':
			$permissions = $this->permissionsGremienAntraege($elem,$model,$hiddenField,$userId);
			break;
		case 'finaAntraege':
			$permissions = $this->permissionsFinaAntraege($elem,$model,$hiddenField,$userId);
			break;
		case 'gremien':
			$permissions = $this->permissionsGremienVerwaltung($elem,$model,$hiddenField,$userId);
			break;
		case 'einrichtungen':
			$permissions = $this->permissionsEinrichtungenVerwaltung($elem,$model,$hiddenField,$userId);
			break;
		case 'zeitraeume':
			$permissions = $this->permissionsZeitraeumeVerwaltung($elem,$model,$hiddenField,$userId);
			break;
		default: 
			$permissions = $this->permissionsVerwendungAntraege($elem,$model,$hiddenField,$userId);
			break;
		}		
		return $permissions;
	}
	
	function permissionsVerwendungAntraege(&$elem,&$model='',$hiddenField, $user) {
		$permissions = array(
				'view',
		);
		return $permissions;
	}
	
	function permissionsAlleAntraege(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			$permissions = '';
		}
		return $permissions;
	}
	
	function permissionsMeineAntraege(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			switch ($elem['status']) {
			case self::$status_entwurf:
				$permissions = array('edit',
						'view',
						'copy',
				
				);
				break;
			default:
				$permissions = array(
						'view',
						'copy',
				);
				break;
			}
		}
		return $permissions;
	}
	
	function permissionsGremienAntraege(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'bewilligen',
													 'ablehnen',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			switch ($elem['status']) {
			case self::$status_bew_gremium:
				$permissions = array('edit',
						'view',
						'copy',
				
				);
				break;
			default:
				$permissions = array(
						'view',
						'copy',
				);
				break;
			}
		}
		return $permissions;
	}
	
	function permissionsFinaAntraege(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'verbuchen',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			switch ($elem['status']) {
			case self::$status_eingereicht:
				$permissions = array('edit',
						'view',
						'copy',
				
				);
				break;
			default:
				$permissions = array(
						'view',
						'copy',
				);
				break;
			}
		}
		return $permissions;
	}
	
	function permissionsGremienVerwaltung(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			$permissions = array(
						'view',
						'copy',
				);
		}
		return $permissions;
	}
	
	function permissionsEinrichtungenVerwaltung(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			$permissions = array(
						'view',
						'copy',
				);
		}
		return $permissions;
	}

	function permissionsZeitraeumeVerwaltung(&$elem,&$model='',$hiddenField, $user) {
		if ($this->admin || $this->applicationAdmin) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else {
			$permissions = array(
						'view',
						'copy',
				);
		}
		return $permissions;
	}
	
	function initMenu($aktuelleSeite) {
		$verwendung = $this->formView->createMenuEntry('Verwendung','verwendung');
		$meineAntraege = $this->formView->createMenuEntry('Meine Anträge','meineAntraege');
		$neuerAntrag = $this->formView->createMenuEntry('Neuen Antrag anlegen','newElem');		
		$trenner = '';
		
		if ($this->admin || $this->applicationAdmin) {
			$alleAntraege = $this->formView->createMenuEntry('Alle Anträge','alleAntraege');
			$gremienAntraege = $this->formView->createMenuEntry('Gremien-Anträge','gremienAntraege');
			$finaAntraege = $this->formView->createMenuEntry('FINA-Anträge','finaAntraege');
			$einrichtungenVerwalten = $this->formView->createMenuEntry('Einrichtungen verwalten','einrichtungen');
			$gremienVerwalten = $this->formView->createMenuEntry('Gremien verwalten','gremien');
			$zeitraeumeVerwalten = $this->formView->createMenuEntry('Zeiträume verwalten','zeitraeume');
			$menu = array($verwendung,
										$alleAntraege,
										$meineAntraege,
										$gremienAntraege,
										$finaAntraege,
										$neuerAntrag,
										$trenner,
										$einrichtungenVerwalten,
										$gremienVerwalten,
										$zeitraeumeVerwalten);
		} else if ($this->gremienMitglied) { 
			$gremienAntraege = $this->formView->createMenuEntry('Gremien-Anträge','gremienAntraege',$aktuelleSeite);
			$menu = array($verwendung,
										$meineAntraege,
										$gremienAntraege,
										$neuerAntrag,
			);
			
		} else {
			$menu = array($verwendung,
										$meineAntraege,
										$neuerAntrag);
		}
		$this->formView->setMenu($menu,'menu_right');
	}

	function showListView($aktuelleSeite) {
		$this->initMenu($aktuelleSeite);
		return parent::showListView($aktuelleSeite);
	}	
	
	// Dummy functtion für Vererbung
	function handleStatus(&$formData,&$parameter,$uid,$hidden,$mode) {
	}
	
	function saveForm(&$formData,&$parameter,$uid='',$hidden,$mode) {
		$this->handleStatus($formData,$parameter,$uid,$hidden,$mode);
		return parent::saveForm($formData,$parameter,$uid,$hidden,$mode);
	}

	function saveFormData(&$formData,$uid,$hidden) {
		if (!empty($uid)) {
			$res = $this->model->updateDbEntry($formData,$uid,'hidden',0);
		} else {
			$res = $this->model->insertDbEntry($formData,'hidden',0);
		}
		return $res;
	}
	
	function handleCustomMode(&$controller,$mode,&$parameter,$post,$uid,$aktuelleSeite='') {
		switch ($mode) {
			case 'ablehnen':
				$content = $controller->handle_ablehnen($parameter,$post,$uid,$aktuelleSeite);
				break;
			case 'bewilligen':
				$content = $controller->handle_bewilligen($parameter,$post,$uid,$aktuelleSeite);
				break;
			default:
				$content = '<h2>Noch nicht implementiert!</h2>';
				$content .= 'Modus: ' . $mode . '<br />';
				$content .= 'Id: ' . $uid . '<br />';
				break;
		}
		return $content;
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_qsm_main.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_qsm_main.php']);
}

?>
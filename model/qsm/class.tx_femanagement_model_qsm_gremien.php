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

class tx_femanagement_model_qsm_gremien	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_qsm_gremien');
	}

	function initFormFields() {
 		$this->formFields = array(
 				'title' => 'title',
 				'kuerzel' => 'kuerzel',
 		);
	}
		
	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_qsm_fe_users');
		$formDataNew['admins'] = array();
		$admins = $this->gibMitglieder($dbData['uid'],$dbData['pid'],'uid','admin');
		if (is_array($admins) && count($admins)>0) {
			foreach ($admins as $mitglied) {
				$mitgliedsName = $feUserModel->getSingle($mitglied['username']);
				$formDataNew['admins'][] = array('value'=>$mitglied['username'],'valueSelect'=>$mitgliedsName);
			}
		}
		$formDataNew['mitglieder'] = array();
		$mitglieder = $this->gibMitglieder($dbData['uid'],$dbData['pid'],'uid','mitglied');
		if (is_array($mitglieder) && count($mitglieder)>0) {
			foreach ($mitglieder as $mitglied) {
				$mitgliedsName = $feUserModel->getSingle($mitglied['username']);
				$formDataNew['mitglieder'][] = array('value'=>$mitglied['username'],'valueSelect'=>$mitgliedsName);
			}
		}
		return $formDataNew;
	}
	
	function getSelectList($pid) {
		$configArray['pid'] = $pid;
		$configArray['fields'] = 'title,kuerzel';
		$configArray['orderBy'] = 'title,kuerzel';
		$list = parent::selectData($configArray);
		$result = array();
		foreach($list as $elem) {
			$result[$elem['kuerzel']] = $elem['title'];
		}
		return $result;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		$admins = $formData['admins']->getValue();
		if (is_array($admins) && $uidNeu) {
			$where = 'rolle="admin" AND gremium=' . $uidNeu;
			$this->delete($where,'tx_qsm_gremien_personen');			
			$data['gremium'] = $uidNeu;
			$data['tstamp'] = time();
			$data['tstamp'] = time();
			$data['pid'] = $this->storagePid;
			foreach ($admins as $mitglied) {
				if (!empty($mitglied['value'])) {
					$data['username'] = $mitglied['value'];
					$data['rolle'] = 'admin';
					$res = $this->insert($data,'tx_qsm_gremien_personen');
					if (!$res) {
						return FALSE;
					}
				}
			}
		}
		$mitglieder = $formData['mitglieder']->getValue();
		if (is_array($mitglieder) && $uidNeu) {
			$where = 'rolle="mitglied" AND gremium=' . $uidNeu;
			$this->delete($where,'tx_qsm_gremien_personen');			
			$data['gremium'] = $uidNeu;
			$data['tstamp'] = time();
			$data['tstamp'] = time();
			$data['pid'] = $this->storagePid;
			foreach ($mitglieder as $mitglied) {
				if (!empty($mitglied['value'])) {
					$data['username'] = $mitglied['value'];
					$data['rolle'] = 'mitglied';
					$res = $this->insert($data,'tx_qsm_gremien_personen');
					if (!$res) {
						return FALSE;
					}
				}
			}
		}
		return TRUE;
	}

/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

	function getFieldData($uid) {
		$configArray['fields'] = 'kuerzel,title';
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$configArray['orderBy'] = 'sorting';
		return parent::selectData($configArray);
	}

	function gibMitglieder($gremium,$pid,$orderBy='uid',$rolle) {
		$configArray['fields'] = 'username,rolle';
		$configArray['orderBy'] = $orderBy;
		$configArray['table'] = 'tx_qsm_gremien_personen';
		$configArray['pid'] = $pid;
		$configArray['sqlFilter'] = 'gremium=' . $gremium . ' AND rolle="' . $rolle . '"';
		$mitgliederDaten = $this->selectData($configArray);
		$mitglieder = array();
		foreach($mitgliederDaten as $mitglied) {
			$mitglieder[] = $mitglied;
		}
		return $mitglieder;
	}
	
	function gibBenutzerGremien($username,$rolle='') {
		$configArray['fields'] = 'gremium';
		$configArray['table'] = 'tx_qsm_gremien_personen';
		$configArray['sqlFilter'] .= 'username="' . $username . '"';
		if (!empty($rolle)) {
			$configArray['sqlFilter'] .= ' AND rolle="' . $rolle . '"';
		}
		$gremienDaten = $this->selectData($configArray);
		foreach($gremienDaten as $gremium) {
			$gremien[] = $gremium['gremium'];
		}
		return $gremien;
	}
	
	function istGremienMitglied($username,$gremium='*') {
		$gremien = $this->gibBenutzerGremien($username);
		if ($gremium!='*') {
			$istGremienMitglied = in_array($gremium,$gremien);
		} else {
			$istGremienMitglied = (count($gremien)>0);
		}
		return $istGremienMitglied;
	}
	
	function istGremienAdmin($username,$gremium='*') {
		$gremien = $this->gibBenutzerGremien($username,'admin');
		if ($gremium!='*') {
			$istGremienMitglied = in_array($gremium,$gremien);
		} else {
			$istGremienMitglied = (count($gremien)>0);
		}
		return $istGremienMitglied;
	}
	
}
?>

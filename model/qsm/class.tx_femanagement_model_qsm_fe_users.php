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

class tx_femanagement_model_qsm_fe_users	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'fe_users');
	}

		
/*
 * ########################## LISTS ##########################
 */	
	function getList($eingabe,$pid,$limit) {
		$configArray = array();
		if (!empty($eingabe)) {
			$configArray['sqlFilter'] =  '(username LIKE "%' . $eingabe . '%" OR
																		last_name LIKE "%' . $eingabe . '%" OR
																		first_name LIKE "%' . $eingabe . '%")';
		} else {
			$configArray['sqlFilter'] = 'TRUE';
		}
		$configArray['sqlFilter'] .=  ' AND NOT FIND_IN_SET (71,usergroup)';
		$configArray['orderBy'] = 'last_name,first_name';
//		$configArray['table'] = 'fe_users';		
		$configArray['hiddenFieldName'] = 'disable';	
//		$configArray['pid'] = 22881;
		$configArray['all_pids'] = TRUE;
		$configArray['limit'] = $limit;
		$configArray['fields'] = 'username,first_name,last_name,tx_hepersonen_akad_grad';
		$liste = $this->selectData($configArray);	
		$data = array();	
		foreach ($liste as $elem) {
			if (!empty($elem['tx_hepersonen_akad_grad'])) {
				$data[$elem['username']] = $elem['tx_hepersonen_akad_grad'] . ' ' . $elem['first_name'] . ' ' . $elem['last_name'] . ' (' . $elem['username'] . ')';
			} else {
				$data[$elem['username']] = $elem['first_name'] . ' ' . $elem['last_name'] . ' (' . $elem['username'] . ')';
			}
		}
		return $data;
	}
	
	function getFieldData($username,$includeUsername=FALSE) {
		$configArray['sqlFilter'] =  'username="' . $username . '"';
		$configArray['fields'] = 'username,first_name,last_name,tx_hepersonen_akad_grad';
		$configArray['all_pids'] = TRUE;
//		$configArray['table'] = 'fe_users';
		$configArray['hiddenFieldName'] = 'disable';
		$data = $this->selectData($configArray);
		if (count($data)>0) {
			if ($includeUsername) {
				$endung = ' (' . $data[0]['username'] . ')';
			} else {
				$endung = '';
			}
			if (!empty($data[0]['tx_hepersonen_akad_grad'])) {
				$value = $data[0]['tx_hepersonen_akad_grad'] . ' ' . $data[0]['first_name'] . ' ' . $data[0]['last_name'] . $endung;
			} else {
				$value = $data[0]['first_name'] . ' ' . $data[0]['last_name'] . $endung;
			}
		}	else {
			$value = '';
		}
		return $value;
	}
	
	function gibAntragsVerantwortliche($antrag) {
		$antragsVerantwortliche = array();
		$configArray['sqlFilter'] =  'antrag=' . $antrag;
		$configArray['fields'] = 'name';
		$configArray['all_pids'] = TRUE;
		$configArray['hiddenFieldName'] = 'hidden';
		$configArray['table'] = 'tx_qsm_antraege_verantwortliche';
		$data = $this->selectData($configArray);
		if (count($data)>0) {
			foreach ($data as $eintrag) {
				$antragsVerantwortliche[] = $eintrag['name'];
			}			
		}
		return $antragsVerantwortliche;
	}
	
	function getSingle($username) {
		return $this->getFieldData($username);
	}
	
	
}
?>

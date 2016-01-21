<?php
/***************************************************************
 *	Copyright notice
*
*	(c) Hochschule Esslingen
*	All rights reserved
*
*	This script is part of the TYPO3 project. The TYPO3 project is
*	free software; you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation; either version 2 of the License, or
*	(at your option) any later version.
*
*	The GNU General Public License can be found at
*	http://www.gnu.org/copyleft/gpl.html.
*
*	This script is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
*	GNU General Public License for more details.
*
*	This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_femanagement_model_events extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_events');
	}
	
	function initFormFields() {
 		$this->formFields = array(
			'pid' => 'pid',
 			'title' => 'title',
      'subtitle' => 'subtitle',
      'description' => 'description',
      'contact' => 'contact',
      'street' => 'street',
 			'city' => 'city',
 			'building' => 'building',
 			'room' => 'room',
 			'email_text' => 'email_text',
			'pic' => 'pic',
		);
	}

	function createFormData(&$formData,&$dbData) {	
		$formDataNew = parent::createFormData($formData,$dbData);
		/*
		 * Termine behandeln
		 */
		if (!empty($dbData['uid'])) {
			$modelDates = t3lib_div::makeInstance('tx_femanagement_model_events_dates',
																			 $this->piBase,
																			 $this->storagePid);
			$formDataNew['dates'] = $modelDates->getEventDates($dbData['uid']);
		}
		return $formDataNew;
	}
	
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (empty($uid)) {
			$dbData['hidden'] = 0;
		}
		if (isset($formData['dates'])) {
			$termine = $formData['dates']->getValue();
		}
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);
		if (is_array($termine) && $uidNeu) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_events_dates',
																			 $this->piBase,
																			 $this->storagePid);
			$res = $model->storeFieldData($uidNeu,$termine);
		}
		return $res;
	}
	
	function cleanDataWrite($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
				case 'event_date':
					if (!empty($value)) {
						$valueNew = intval($value / 86400) * 86400;
						$ergebnisDaten[$key] = $valueNew;
					} else {
						$ergebnisDaten[$key] = 0;
					}
					break;
				default:
					$ergebnisDaten[$key] = $value;
			}
		}
		return $ergebnisDaten;
	}

  function getEventData($uid) {
    $configArray['fields'] = 'title,subtitle,description,street,city,zip,building,room,contact,email_text,pic';
    $configArray['sqlFilter'] = 'uid=' . $uid;
    $configArray['all_pids'] = 'TRUE';
    $data = $this->selectData($configArray);
    if (count($data)==1) {
      return $data[0];
    }
    return '';
  }

  function getEventList(&$pibase, $pid='', $minDate='') {
    /** @var  $model tx_femanagement_model_events_dates */
    $model = t3lib_div::makeInstance('tx_femanagement_model_events_dates',$pibase,$pid);
    return $model->getEventList($minDate,'',$pid);
  }

  function deleteElem($uid) {
    $data['deleted'] = 1;
    $where = 'uid=' . $uid;
    $ok = $this->update($where,$data,$this->table);
    if ($ok) {
      $data['deleted'] = 1;
      $where = 'event=' . $uid;
      $ok = $this->update($where,$data,'tx_femanagement_events_dates');
    }
    return $ok;
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/events/class.tx_femanagement_model_events.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/events/class.tx_femanagement_model_events.php']);
}
?>

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

class tx_femanagement_model_events_anmeldungen extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_event_anmeldungen');
	}
	
	function initFormFields() {
 		$this->formFields = array(
			'pid' => 'pid',
      'event' => 'event',
      'event_date' => 'event_date',
      'organization' => 'organization',
			'first_name' => 'first_name',
			'last_name' => 'last_name',
			'street' => 'street',
			'city' => 'city',
			'zip' => 'zip',
 			'link' => 'link',
 			'email' => 'email',
      'phone' => 'phone',
      'count_pt' => 'count_pt',
 			'remarks'	 => 'remarks',
		);
	}
		
	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (empty($uid)) {
			$dbData['hidden'] = 0;
		}
		return parent::storeFormEntry($formData,$dbData,$uid);
	}

  function anmeldungVorhanden($event, $eventDate) {
    $configArray['fields'] = 'uid';
    $configArray['sqlFilter'] = 'event=' . $event . ' AND event_date=' . $eventDate;
    $eventDates = $this->selectData($configArray);
    if (count($eventDates)>0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  function saveEntry(&$data) {
    if (empty($data['uid'])) {
      return $this->insert($data,'tx_femanagement_anmeldungen');
    } else {
      $where = 'uid=' . $data['uid'];
      return $this->update($where,$data,'tx_femanagement_anmeldungen');
    }
  }

  function getBookingData($uid) {
    $configArray['fields'] = '*';
    $configArray['sqlFilter'] = 'uid=' . $uid;
    $configArray['all_pids'] = 'TRUE';
    $data = $this->selectData($configArray);
    if (count($data)==1) {
      return $data[0];
    }
    return '';
  }


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/events/class.tx_femanagement_model_events_anmeldungen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/model/events/class.tx_femanagement_model_events_anmeldungen.php']);
}
?>

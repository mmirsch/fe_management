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

class tx_femanagement_model_events_dates	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_femanagement_events_dates');
	}

		function initFormFields() {
 			$this->formFields = array(
				'event' => 'event',
				'event_date' => 'event_date',
				'start' => 'start',
 				'end' => 'end',
 		);
	}
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}

  function buildSelect(&$configArray) {
    $sqlQuery = $this->buildQuery($configArray);
    $select = $this->buildSqlSelect($sqlQuery);
    return $select;
  }

  function buildJoinSelect(&$configArray) {
		$sqlQuery = $this->buildJoinQuery($configArray);
		$select = $this->buildSqlSelect($sqlQuery);
		return $select;
	}

  function selectData($configArray) {
		$select = $this->buildSelect($configArray);
		return $this->fetchData($select);
	}

  function selectJoinData($configArray) {
    $select = $this->buildJoinSelect($configArray);
    return $this->fetchData($select);
  }

  function getFieldData($uid) {
    $configArray['fields'] = 'event_date,start,end';
    $configArray['sqlFilter'] = 'event=' . $uid;
    $configArray['orderBy'] = 'sorting';
    return $this->selectData($configArray);
  }

  function getFieldDataSingle($uid) {
    $configArray['fields'] = 'event_date,start,end';
    $configArray['sqlFilter'] = 'uid=' . $uid;
    $configArray['orderBy'] = 'sorting';
    $data = $this->selectData($configArray);
    if (count($data)==1) {
      return $data[0];
    }
    return '';
  }

  function getEventDates($uid) {
		$configArray['fields'] = 'uid,event_date,start,end';
		$configArray['sqlFilter'] = 'event=' . $uid;
		$configArray['orderBy'] = 'sorting';		
		$eventDates = $this->selectData($configArray);
		$resultData = array();
		foreach ($eventDates as $eintrag) {
			$datum = $this->getDateString($eintrag['event_date']);
			$start = $this->getTimeString($eintrag['start']);
			$end = $this->getTimeString($eintrag['end']);
			$resultData[] = array('uid'=>$eintrag['uid'],'event_date'=>$datum,'start'=>$start,'end'=>$end);
		}
		return $resultData;
	}

  function getEventList($startDate='',$endDate='',$pid='') {
    $configArray['sqlFilter'] = 'TRUE';
    if (!empty($startDate)) {
      $configArray['sqlFilter'] .= ' AND (event_date>=' . $startDate . ')';
    }
    if (!empty($endDate)) {
      $configArray['sqlFilter'] .= ' AND (event_date<=' . $endDate . ')';
    }
    if (!empty($pid)) {
      $configArray['pid'] = $pid;
    } else {
      $configArray['all_pids'] = TRUE;
    }
    $configArray['joins'] = array(
      array('table'=>'tx_femanagement_events',
        'fields'=>'uid as event_uid',
        'joinFieldLocal'=>'uid',
        'joinFieldMain'=>'event',
        'mode'=>'INNER JOIN',
        'where' => 'tx_femanagement_events_dates.deleted=0  AND tx_femanagement_events_dates.hidden=0  AND ' .
                   'tx_femanagement_events.deleted=0 AND tx_femanagement_events.hidden=0',
      ),
    );
    $configArray['fields'] = 'uid,event,event_date,start,end';
    $configArray['orderBy'] = 'event_date,start';
    $eventDates = $this->selectData($configArray);
    $resultData = array();
    foreach ($eventDates as $entry) {
      $event = $entry['event'];
      if (!array_key_exists($event,$resultData)) {
        $resultData[$event] = array();
      }

      $eventDate = $entry['event_date'];
      $year = date('Y',$eventDate);
      if (!array_key_exists($year,$resultData[$event])) {
        $resultData[$event][$year] = array();
      }
      $month = date('n',$eventDate);
      if (!array_key_exists($month,$resultData[$event][$year])) {
        $resultData[$event][$year][$month] = array();
      }

      if (!array_key_exists ($eventDate,$resultData[$event][$year][$month])) {
        $resultData[$event][$year][$month][$eventDate] = array();
      }
      $resultData[$event][$year][$month][$eventDate][$entry['uid']] = array('start'=>$entry['start'],'end'=>$entry['end']);
    }
    return $resultData;
  }

  function getEventListGroupByDate($startDate='',$endDate='',$pid='') {
    $configArray['sqlFilter'] = 'TRUE';
    if (!empty($startDate)) {
      $configArray['sqlFilter'] .= ' AND (event_date>=' . $startDate . ')';
    }
    if (!empty($endDate)) {
      $configArray['sqlFilter'] .= ' AND (event_date<=' . $endDate . ')';
    }
    if (!empty($pid)) {
      $configArray['pid'] = $pid;
    } else {
      $configArray['all_pids'] = TRUE;
    }
    $configArray['fields'] = 'event,event_date,start,end';
    $configArray['orderBy'] = 'event_date,start';
    $eventDates = $this->selectJoinData($configArray);
    $resultData = array();
    foreach ($eventDates as $entry) {
      $eventDate = $entry['event_date'];

      $year = date('Y',$eventDate);
      if (!array_key_exists($year,$resultData)) {
        $resultData[$year] = array();
      }
      $month = date('n',$eventDate);
      if (!array_key_exists($month,$resultData[$year])) {
        $resultData[$year][$month] = array();
      }

      if (!array_key_exists ($eventDate,$resultData[$year][$month])) {
        $resultData[$year][$month][$eventDate] = array();
      }
      $event = $entry['event'];
      if (!array_key_exists ($event,$resultData[$year][$month][$eventDate])) {
        $resultData[$year][$month][$eventDate][$event] = array();
      }
      $resultData[$year][$month][$eventDate][$event][] = array('start'=>$entry['start'],'end'=>$entry['end']);
    }
    return $resultData;
  }

  function storeFieldData($eventId,&$data) {
    if (count($data)>0) {
      /*
       * Vorhandene Einträge abfragen
       */
      $existingEntries = $this->getEventDates($eventId);
      $existingEntriesHashed = array();
      foreach($existingEntries as $entry) {
        $existingEntriesHashed[$entry['event_date'] . $entry['start'] . $entry['end']] = $entry['uid'];
      }
      $deletedUids = array();
      $newEntries = array();
      $importedEntries = array();

      foreach($data as $entry) {
        $hash = $entry['event_date'] . $entry['start'] . $entry['end'];
        if (!array_key_exists($hash,$existingEntriesHashed)) {
          $newEntries[$hash] = $entry;
        }
        $importedEntries[$hash] = $entry;
      }
      foreach($existingEntriesHashed as $hash=>$uid) {
        if (!array_key_exists($hash,$importedEntries)) {
          $deletedUids[] = $uid;
        }
      }
      if (count($deletedUids)>0) {
        /*
         * Nicht mehr vorhandene Einträge löschen
         */
        $deleteList = implode(',',$deletedUids);
        $whereDelete = 'event=' . $eventId . ' AND uid IN (' . $deleteList . ')';
        $res = $this->delete($whereDelete,'tx_femanagement_events_dates');
        if (!$res) {
          t3lib_div::devLog('Fehler beim Löschen der Event-Termine:', 'event_dates', 0, $deletedUids);
          return FALSE;
        }
      }
    }

		$sorting = 1;
		foreach($importedEntries as $hash=>$entry) {
			if (!empty($entry['event_date']) &&
					!empty($entry['start']) &&
					!empty($entry['end'])) {
				$datum = $this->getDate($entry['event_date']);
				$start = $this->getTime($entry['start']);
				$end = $this->getTime($entry['end']);


				//				$datum = intval($eintrag['event_date'] / 86400) * 86400;
				$saveData = array(
							'pid' => $this->storagePid,
							'tstamp' => time(),
							'hidden' => '0',
							'deleted' => '0',
							'event' => $eventId,
							'event_date' => $datum,
							'start' => $start,
							'end' => $end,
							'sorting' => $sorting,
					);
        if (!array_key_exists($hash,$existingEntriesHashed)) {
          $saveData['crdate'] = time();
          $res = $this->insert($saveData,'tx_femanagement_events_dates');
        } else {
          $whereUpdate = 'uid=' . $existingEntriesHashed[$hash];
          $res = $this->update($whereUpdate,$saveData,'tx_femanagement_events_dates');
        }
				$sorting++;

				if (!$res) {
					if ($this->piBase->settings['debug']) t3lib_div::devLog('Fehler beim Speichern der Event-Termine:', 'fe_managment', 0, $saveData);
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	function getTime($uhrzeit) {
		$uhrzeit = str_replace('.',':',trim($uhrzeit));
		$zeitDaten = explode(':',$uhrzeit);
		if (count($zeitDaten)==2) {
			$timestamp = ($zeitDaten[0] * 3600 + $zeitDaten[1] * 60);
		} else {
			$timestamp = 0;
		}
		return $timestamp;
	}
	
	function getTimeString($timestamp) {
		$stunden = $timestamp / 3600;
		$minuten = ($timestamp % 3600) / 60;
		$uhrzeit = sprintf("%02d:%02d",$stunden,$minuten);
		return $uhrzeit;
	}
	
	function getDate($datum) {
		$datum = str_replace(':','.',trim($datum));	
		$datumsDaten = explode('.',$datum);
		if (count($datumsDaten)==3) {
			date_default_timezone_set('UTC');
			$timestamp = mktime(0, 0, 0, $datumsDaten[1], $datumsDaten[0], $datumsDaten[2]);
		} else {
			$timestamp = 0;
		}
		return $timestamp;
	}
	
	function getDateString($timestamp) {
		$datum = date("d.m.Y",$timestamp);
		return $datum;
	}
	
}
?>

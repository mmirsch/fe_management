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

class tx_femanagement_model_cal_event	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_cal_event');
	}

	function initFormFields() {
		$this->formFields = array(
			'calendar_id' => 'calendar_id',
			'title' => 'title',
			'description' => 'description',
			'allday' =>'allday',
			'start_time' => 'start_time',
			'end_time' => 'end_time',
			'start_date' => 'start_date',
			'end_date' => 'end_date',
			'location_id' => 'location_id',
			'organizer_id' => 'organizer_id',
			'ext_url' => 'ext_url',
			'image' => 'image',
			'attachment' => 'attachment',
			'tx_femanagement_cal_title_infoscreen' => 'tx_femanagement_cal_title_infoscreen',
		);
	}

	function createFormData(&$formData,&$dbData) {
		$formDataNew = parent::createFormData($formData,$dbData);
		/*
		 * Kategorien behandeln
		 */
		if (!empty($dbData['uid'])) {
			$categories = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$this->piBase,$this->storagePid);
			$formDataNew['category'] = $categories->getMmList($dbData['uid']);

			$configArray['show_hidden'] = 1;
			$configArray['all_pids'] = 1;
			$userId = $this->selectField($dbData['uid'],'fe_cruser_id',$configArray);
			$feUserModel = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
			$feUserdata = $feUserModel->selectFields('uid',$userId,'fe_users','name');
			$formDataNew['fe_cruser_id'] = $feUserdata['name'];
		}
		return $formDataNew;
	}

	function storeFormEntry(&$formData,&$dbData,$uid='')  {
		if (isset($formData['beschreibung']) ||
			isset($formData['referent']) ||
			isset($formData['zielgruppe']) ||
			isset($formData['link'])) {
			$description = array();
			if (isset($formData['beschreibung'])) {
				$beschreibung = $formData['beschreibung']->getValue();
				$description[] = '<b>Beschreibung</b>: ' . $beschreibung;
			}
			if (isset($formData['referent'])) {
				$referent = $formData['referent']->getValue();
				$description[] = '<b>Referent/in</b>: ' . $referent;
			}
			if (isset($formData['zielgruppe'])) {
				$zielgruppe = $formData['zielgruppe']->getValue();
				$description[] = '<b>Zielgruppe</b>: ' . $zielgruppe;
			}
			if (isset($formData['link'])) {
				$linkUrl = $formData['link']->getValue();
				if (strpos($linkUrl,'http')===FALSE) {
					$linkUrl = 'http://' . $linkUrl;
				}
				$link = '<a href="' . $linkUrl . '">' . $linkUrl . '</a>';
				$description[] = '<b>Link</b>: ' . $link;
			}
			$dbData['description'] = implode('<br>',$description);
		}
		/*
		 * Kategorien behandeln
		 */
		if (isset($formData['category'])) {
			$categoryData = $formData['category']->getValue();
			$catCount = count($categoryData);
			$dbData['category_id'] = $catCount;
		}
		if (empty($uid)) {
			$dbData['fe_cruser_id'] = $GLOBALS['TSFE']->fe_user->user['uid'];
		}
		$calMode = tx_femanagement_lib_util::getPageConfig('calMode');
		if ($calMode=='Infoscreen' && empty($uid)) {
			$dbData['hidden'] = 0;
		}
		$uidNeu = parent::storeFormEntry($formData,$dbData,$uid);

		/*
		 * Kategorien updaten
		 */
		if (is_array($categoryData) && $uidNeu) {
			$model = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',
				$this->piBase,
				$this->storagePid);
			$res = $model->storeFieldData($uidNeu,$categoryData);
		}
		return $uidNeu;
	}

	function selectData($configArray) {
		$data = parent::selectData($configArray);
		if (isset($configArray['externalData'])) {
			$externalFields = array();
			foreach ($configArray['externalData'] as $field=>$fieldData) {
				$model = t3lib_div::makeInstance($fieldData['model'],
					$this->piBase,
					$this->storagePid);
				$externalFields[$field] = array('model' => $model,
					'field' => $fieldData['field']);
			}
			$dataNew = array();
			foreach ($data as $daten) {
				foreach ($externalFields as $field=>$externelData) {
					if (isset($daten[$field])) {
						$daten[$field] = $externelData['model']->selectField($daten[$field],$externelData['field']);
					}
				}
				$dataNew[] = $daten;
			}
			return $dataNew;
		} else {
			return $data;
		}
	}

	function isOwner($uid,$userId) {
		$userIdField = 'fe_cruser_id';
		$configArray['fields'] = $userIdField;
		$configArray['all_pids'] = TRUE;
		$configArray['show_deleted'] = TRUE;
		$configArray['show_hidden'] = TRUE;
		$configArray['sqlFilter'] = 'uid=' . $uid;
		$data = $this->selectData($configArray);
		if (count($data)==1) {
			return $data[0][$userIdField]==$userId;
		} else {
			return FALSE;
		}
	}

	function infoscreenTermin($calUid,&$piBase,$pid) {
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$piBase,$pid);
		$catList = $catModel->getMmList($calUid);

		$catListInfoscreen = $catModel->getCatIdList('INFO');
		$infoscreen = FALSE;
		foreach ($catList as $cat) {
			if (in_array($cat,$catListInfoscreen)) {
				$infoscreen = TRUE;
			}
		}
		return $infoscreen;
	}

	function getCategoryTitles($calUid,&$piBase,$pid) {
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories',$piBase,$pid);
		return $catModel->getMmTitleList($calUid);
	}

	/*
	 * ########################## CONVERT DATA ##########################
	 */

	function cleanDataRead($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
				case 'start_date':
				case 'end_date':
					if (!empty($value)) {
						$valueNew = strtotime($value);
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

	function cleanDataWrite($daten) {
		$ergebnisDaten = array();
		foreach ($daten as $key=>$value) {
			switch ($key) {
				case 'start_date':
				case 'end_date':
					if (!empty($value)) {
						$valueNew = date('Ymd',$value);
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

	function gibFlexformElement(&$piFlexform, $element) {
		$elemente = '';
		$startPos = strpos($piFlexform, $element);
		if ($startPos > 0) {
			$suchStringInfoElemente = substr($piFlexform, $startPos, 150);
			$startPosVdef = strpos($suchStringInfoElemente, 'vDEF');
			$suchStringVdef = substr($suchStringInfoElemente, $startPosVdef,20);
			$startPosValue = strpos($suchStringVdef, '">') + 2;
			$endPosValue = strpos($suchStringVdef, '</');
			$elemente = substr($suchStringVdef, $startPosValue, $endPosValue - $startPosValue);
		}
		return $elemente;
	}

	function gibInfoscreenElemente(&$piFlexform ) {
		return $this->gibFlexformElement($piFlexform, 'infoscreen_elemente');
	}

	function aktualisiereSeitenTstampInfoscreen($calUid) {
		$catModel = t3lib_div::makeInstance('tx_femanagement_model_cal_categories');
		$catList = $catModel->getMmList($calUid);
		$daten = $catModel->getList('','');
		if (!is_array($catList) || count($catList)==0 ||
			!is_array($daten) || count($daten)==0) {
			return;
		}
		$kategorieTitelListe = array();
		foreach ($daten as $uid=>$titel) {
			if (in_array($uid,$catList) && strpos($titel,'Infoscreen')!==FALSE) {
				$kategorieIds[] = $uid;
			}
		}
		$sql = 'SELECT pi_flexform,pid FROM tt_content
						where deleted=0 AND hidden=0 AND
						pi_flexform LIKE "%>TOOLS<%" AND
						pi_flexform LIKE "%>INFOSCREEN<%"
		';

		$data = array();
		$ergebnis = $this->selectSqlData($sql);
		if (is_array($ergebnis) && count($ergebnis)>0 &&
			is_array($kategorieIds) && count($kategorieIds)>0) {
			foreach ($ergebnis as $eintrag) {

				$infoscreenElemente = $this->gibInfoscreenElemente($eintrag['pi_flexform']);
				$elementListe = explode(',',$infoscreenElemente);
				if (count($elementListe)>0) {
					foreach ($elementListe as $element) {
						$sql = 'SELECT kalenderKategorie FROM tx_hetools_infoscreen_anzeige_zeitraeume
						          where deleted=0 AND hidden=0 AND uid=' . $element . '
						AND anzeigetyp LIKE "KALENDERTERMINE" 
		      ';

						$data = array();
						$ergebnis = $this->selectSqlData($sql);
						if (isset($ergebnis[0]['kalenderKategorie'])) {
							$catId = $ergebnis[0]['kalenderKategorie'];
							if (in_array($catId,$kategorieIds)) {
								$data['tstamp'] = time();
								parent::update('uid=' . $eintrag['pid'],$data,'pages');
							}
						}
					}
				}
			}
		}
	}

}


?>

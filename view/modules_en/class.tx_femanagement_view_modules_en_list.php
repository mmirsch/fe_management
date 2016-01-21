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

 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_modules_en_list extends tx_femanagement_view_form_list {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_modules_en',$this->piBase,$pid);
	}
				
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		$model = t3lib_div::makeInstance('tx_femanagement_model_modules_en',$this->piBase,$this->pid);
		foreach ($fieldList as $key=>$field) {
			$studiengaenge = unserialize($elem['studiengang']);
			$fakultaet = $model->gibStudiengangFakultaet($studiengaenge[0]['studiengang']);
			$standort = $model->gibFakultaetsStandort($fakultaet);
			switch ($key) {
				case 'title':
					$out .= '<td class="title">';
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
						$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE,'',TRUE);
					} else {
						$out .= $title;
					}
					$out .= '</td>';
					break;
				case 'standort':
					$standortTitel = $model->gibStandortTitel($standort); 	
					$out .= '<td class="campus">';
					$out .= $standortTitel;
					$out .= '</td>';
					break;
				case 'fakultaet':
					$fakultaetTitel = $model->gibFakultaetsTitel($fakultaet); 	
					$out .= '<td class="fakultaet">';
					$out .= $fakultaetTitel;
					$out .= '</td>';
					break;
				case 'studiengang':
					$studiengaenge = unserialize($elem['studiengang']);
					$out .= '<td class="studiengang">';
					$studiengangTitel = array();
					foreach ($studiengaenge as $studiengang) {
						$studiengangTitel[] = $model->gibStudiengangTitel($studiengang['studiengang']);						
					}
					$out .= '<ul><li class="studiengaenge">' . implode('</li><li class="studiengaenge">' , $studiengangTitel) . '</li></ul>';
					$out .= '</td>';
					break;
				case 'verantwortliche':
					$verantwortliche = unserialize($elem['verantwortliche']);
					$out .= '<td class="verantwortliche">';
					$eintraege = array();
					if (!empty($verantwortliche) && count($verantwortliche)>0) {
						$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
						foreach ($verantwortliche as $eintrag) {
							/*
							 *  Wiss. Leitung alt
							 */

							if (!empty($eintrag['value']) && !empty($eintrag['valueSelect'])) {
								$username = $eintrag['value'];
								$benutzerDaten = $modelFeUser->selectFields('username',$username,'fe_users','tx_hepersonen_akad_grad,first_name,last_name,tx_hepersonen_profilseite');
								$name = $benutzerDaten['first_name'] . ' ' . $benutzerDaten['last_name'];
								if (!empty($benutzerDaten['tx_hepersonen_akad_grad'])) {
									$name = $benutzerDaten['tx_hepersonen_akad_grad'] . ' ' . $name;
								}
								$eintrag = '<a target="_blank" href="index.php?id=' . $benutzerDaten['tx_hepersonen_profilseite'] . '">' . $name . '</a>';
								$eintraege[] = $eintrag;
							} else if (!empty($eintrag['vorname']) && !empty($eintrag['nachname'])) {
								$name = $eintrag['vorname'] . ' ' . $eintrag['nachname'];
								if (!empty($eintrag['titel'])) {
									$name = $eintrag['titel'] . ' ' . $name;
								}
								if (!empty($eintrag['email'])) {
									$eintrag = '<a class="mail" target="_blank" href="mailto:' . $eintrag['email'] . '">' . $name . '</a>';
								} else {
									$eintrag = $name;
								}
								$eintraege[] = $eintrag;
							} else {
								$eintraege[] = $eintrag['valueSelect'];
							}
						}
						$out .= implode('<br />',$eintraege);
						$out .= '</td>';
					}					
					break;
			}
		}
		$out .= '<td class="actions">';
		$previewLinkUrl = 'http://www.hs-esslingen.de/index.php?id=' . $this->controller->getPreviewPage();
		$out .= $this->showActions($elem,$permissions,$previewLinkUrl);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}

	function createLinkSingleView($uid,$linkUrl='') {
		$iconClass = 'icon-actions t3-icon-version-workspace-preview popup_window';
		$title = 'Vorschau';
		$previewLinkUrl = 'http://www.hs-esslingen.de/index.php?id=' . $this->controller->getPreviewPage();
		$linkUrl = $previewLinkUrl . '&popup=1#uid' . $uid ;
		return '<a data-linkurl="' . $linkUrl . '" class="' . $iconClass . '" target="_blank" title="' . $title . '" ' .
						   'href="' . $linkUrl . '" data-window-x="10" data-window-y="10" data-window-w="800" data-window-h="600"></a>';
	}

	function getNewelemButtonTitle() {
		return 'Neuen Eintrag anlegen'; 
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		$configArray['joins'] = array(
				array('table'=>'fe_users',
						'fields'=>'name as cruser_id,email,username',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'cruser_id',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'tx_he_standorte',
						'fields'=>'title as standort',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'campus',
						'mode'=>'LEFT JOIN',
				),
				array('table'=>'tx_he_fakultaeten',
						'fields'=>'title as fakultaet',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'fakultaet',
						'mode'=>'LEFT JOIN',
				),
		);
		
		if (isset($this->args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tx_he_modules_en.title LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tx_he_modules_en.verantwortliche LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		if (!empty($this->args['fakultaet']) && $this->args['fakultaet']!='all') {
			$configArray['where'] .= ' AND (tx_he_fakultaeten.uid=' . $this->args['fakultaet'] . ')';
		}
		if ($this->args['hidden']!='all') {
			$configArray['where'] .= ' AND tx_he_modules_en.hidden=' . $this->args['hidden'];
		}
		if ($this->args['deleted']!='all') {
			$configArray['where'] .= ' AND tx_he_modules_en.deleted=' . $this->args['deleted'];
		}
		/*
		 * Sortierung prüfen
		*/
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$sortMode = $this->args['sortMode'];
			} else {
				$sortMode = 'ASC';
			}
			$sortField = 'tx_he_modules_en.' . $this->args['sortField'];
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'tx_he_modules_en.title ASC';
		}
		
		$configArray['fields'] = 'uid,title,campus,fakultaet,studiengang,verantwortliche,download,link,credits,level,semester,hidden,deleted';
		$sqlQuery = $this->model->buildJoinQuery($configArray);

		$out = '';
		
		$out .= $this->exitSqlAjaxFilter($sqlQuery,$limit);
	
		if (!empty($limit)) {
			$sqlQuery .= $limit;
		}
	
		$daten = $this->model->selectSqlData($sqlQuery);
		foreach ($daten as $index=>$elem) {
			$daten[$index]['permissions'] = $this->getPermissions($elem,$page,$this->model);
		}
		$out .= $this->createDataList($daten);
		return $out;
	}
/*
	function deleteEntry($data) {
		$erg = parent::deleteEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function undeleteEntry($data) {
		$erg = parent::undeleteEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function hideEntry($data) {
		$erg = parent::hideEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function unhideEntry($data) {
		$erg = parent::unhideEntry($data);
		$this->postProcessing($data);
		return $erg;
	}
	
	function postProcessing($data) {
		if (isset($data['ctrl'])) {
			$controller = t3lib_div::makeInstance($data['ctrl']);
			$controller->postProcessingDataChange($data['args']);
		}
	}
*/	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/modules_en/class.tx_femanagement_view_modules_en_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/modules_en/class.tx_femanagement_view_modules_en_list.php']);
}

?>
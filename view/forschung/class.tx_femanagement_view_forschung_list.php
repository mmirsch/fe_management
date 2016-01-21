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

require_once (PATH_site.'typo3/sysext/cms/tslib/class.tslib_fe.php');
require_once (PATH_site.'typo3/sysext/cms/tslib/class.tslib_content.php') ;


class tx_femanagement_view_forschung_list extends tx_femanagement_view_form_list {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_forschung',$this->piBase,$pid);
	}

  function formatExportItemWord(&$wordExporter, &$table, &$eintrag) {
    $hoeheZeile = 300;
    $breiteLinks = 2000;
    $breiteRechts = 7000;
    $imageWidth = 450;

// Titel
    $key = 'title';
    $title = iconv('UTF-8','ISO-8859-1','Titel:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag[$key]));
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $wordExporter->addTableCellWithText($table, $breiteRechts, $wertWord, 'title');

// Förderung
    $key = 'foerderung_oeffentlich';
    $title = iconv('UTF-8','ISO-8859-1','Förderung öffentlich:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag['foerderung_wer']));
    $liste = explode("\r\n",$wertWord);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $cell = $wordExporter->addTableCell($table, $breiteRechts);
    if (count($liste)>1) {
    	foreach ($liste as $listenEintrag) {
    		$wordExporter->addListItem($cell, trim($listenEintrag));
    	}
    } else {
    	$wordExporter->addText($cell, $wertWord);
    }
    
    $key = 'foerderung_privat';
    $title = iconv('UTF-8','ISO-8859-1','Förderung privat:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag['foerderung_wer']));
    $liste = explode("\r\n",$wertWord);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $cell = $wordExporter->addTableCell($table, $breiteRechts);
    if (count($liste)>1) {
    	foreach ($liste as $listenEintrag) {
    		$wordExporter->addListItem($cell, trim($listenEintrag));
    	}
    } else {
    	$wordExporter->addText($cell, $wertWord);
    }
    
// Laufzeit
    $keyVon = 'start_datum';
    $keyBis = 'end_datum';
    $title = iconv('UTF-8','ISO-8859-1','Laufzeit:');
    $wertVon = $wordExporter->stripHtml($this->formatExportItem($keyVon, $eintrag[$keyVon]));
    $wertBis = $wordExporter->stripHtml($this->formatExportItem($keyBis, $eintrag[$keyBis]));
    $wertWord = $wordExporter->stripHtml($wertVon . ' - ' . $wertBis);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $wordExporter->addTableCellWithText($table, $breiteRechts, $wertWord);
    
// Fördersumme
    $key = 'foerdersumme';
    $title = iconv('UTF-8','ISO-8859-1','Fördersumme:');
    $wertWord = 'gesamt: ' . $wordExporter->stripHtml($this->formatExportItem($key, $eintrag[$key])) .
                ' | Anteil 2014: ';
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $wordExporter->addTableCellWithText($table, $breiteRechts, $wertWord);

// Projektleitung
    $key = 'wiss_leitung';
    $title = iconv('UTF-8','ISO-8859-1','Projektleitung:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag[$key]));
    $liste = explode("\r\n",$wertWord);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);    
    $cell = $wordExporter->addTableCell($table, $breiteRechts);
    if (count($liste)>1) {
      foreach ($liste as $listenEintrag) {
        $wordExporter->addListItem($cell, trim($listenEintrag));
      }
    } else {
      $wordExporter->addText($cell, $wertWord);
    }

// wiss. Mitarbeiter/innen
    $key = 'wiss_mitarbeiter';
    $title = iconv('UTF-8','ISO-8859-1','wiss. Mitarbeiter/innen:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag[$key]));
    $liste = explode("\r\n",$wertWord);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $cell = $wordExporter->addTableCell($table, $breiteRechts);
    if (count($liste)>1) {
    	foreach ($liste as $listenEintrag) {
    		$wordExporter->addListItem($cell, trim($listenEintrag));
    	}
    } else {
    	$wordExporter->addText($cell, $wertWord);
    }


// Kooperations-partner/innen
    $key = 'kooperationspartner';
    $title = iconv('UTF-8','ISO-8859-1','Kooperations-partner/innen:');
    $wertWord = $wordExporter->stripHtml($this->formatExportItem($key, $eintrag[$key]));
    $liste = explode("\r\n",$wertWord);
    $wordExporter->addTableRow($table, $hoeheZeile);
    $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    $cell = $wordExporter->addTableCell($table, $breiteRechts);
    if (count($liste)>1) {
    	foreach ($liste as $listenEintrag) {
    		$wordExporter->addListItem($cell, trim($listenEintrag));
    	}
    } else {
    	$wordExporter->addText($cell, $wertWord);
    }
    
    
// Kurzbeschreibung
    $key = 'beschreibung_kurz';    
    if (!empty($eintrag[$key])) {
    	$title = iconv('UTF-8','ISO-8859-1','Kurzbeschreibung:');
    	$wertWord = $this->formatExportItem($key, $eintrag[$key]);
    	$wordExporter->addTableRow($table, $hoeheZeile);
    	$wordExporter->addTableCellWithText($table, $breiteLinks, $title);
    	$cell = $wordExporter->addTableCell($table, $breiteRechts);
    	$wordExporter->handleHtml($cell, $wertWord);
    }

// Abbildung
    $keyMedien = 'medien1';
    $dateiname = $this->formatExportItem($keyMedien, $eintrag[$keyMedien]);

    if (!empty($dateiname)) {
    	$title = iconv('UTF-8','ISO-8859-1','Abbildung:');
    	$imageHeight = 0;
      $dateiWord = tx_femanagement_lib_util::createJpgImage($dateiname, $imageWidth, $imageHeight);
      $imageStyle = array('width'=>$imageWidth, 'height'=>$imageHeight, 'align'=>'left');
      $wordExporter->addTableRow($table, $hoeheZeile);
      $wordExporter->addTableCellWithText($table, $breiteLinks, $title);
      $cell = $wordExporter->addTableCell($table, $breiteRechts);
      $wordExporter->addImage($cell, $dateiWord, $imageStyle);
       
      $keyBildunterschrift = 'bildunterschrift1';
      $bildunterschrift = $wordExporter->stripHtml($this->formatExportItem($keyBildunterschrift, $eintrag[$keyBildunterschrift]));
      if (!empty($bildunterschrift)) {
      	$wordExporter->addText($cell, $bildunterschrift);
      }
    }
  }

 	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$feUserdata = $this->model->getUserData($elem);
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'medien1':
					$out .= '<td class="grafik">';
					if (!empty($elem[$key])) {
						$pfad = 'uploads/tx_femanagement_forschungsprojekte/pics/' . $elem[$key];
						$bildadresse = tx_femanagement_lib_util::createJpgImage($pfad, 50, 20);
						$out .= '<a class="lightbox" data-fancybox-group="lightbox" href="' . $pfad . '"><img height="20" src="' . $bildadresse . '" /></a>';
					}
					$out .= '</td>';
					break;
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
				case 'start_datum':
				case 'end_datum':
					$out .= '<td class="' . $key . '">';
					if (!empty($elem[$key])) {
						$out .= date('d.m.Y', $elem[$key]);
					} else {
						$out .= ' - ';
					}
					$out .= '</td>';
					break;
				case 'leitende_einrichtung':
					$model = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->pid);
					$leitendeEinrichtung = $model->getTitle($elem['leitende_einrichtung']); 	
					$out .= '<td class="leitende_einrichtung">';
					$out .= $leitendeEinrichtung;
					$out .= '</td>';
					break;
				case 'wiss_leitung':
					$wissLeitung = unserialize($elem['wiss_leitung']);
					$out .= '<td class="wiss_leitung">';
					$eintraege = array();
					if (!empty($wissLeitung) && count($wissLeitung)>0) {
						$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
						foreach ($wissLeitung as $eintrag) {
							/*
							 *  Wiss. Leitung alt
							 */
							if (isset($eintrag['value']) && $eintrag['valueSelect']) {
								$username = $eintrag['value'];
								$benutzerDaten = $modelFeUser->selectFields('username',$username,'fe_users','tx_hepersonen_akad_grad,first_name,last_name,tx_hepersonen_profilseite');
								$name = $benutzerDaten['first_name'] . ' ' . $benutzerDaten['last_name'];
								if (!empty($benutzerDaten['tx_hepersonen_akad_grad'])) {
									$name = $benutzerDaten['tx_hepersonen_akad_grad'] . ' ' . $name;
								}
								$eintrag = '<a target="_blank" href="index.php?id=' . $benutzerDaten['tx_hepersonen_profilseite'] . '">' . $name . '</a>';
								$eintraege[] = $eintrag;								
							} else {
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
							}
						}
						$out .= implode('<br />',$eintraege);
					}
					$out .= '</td>';
					break;
				case 'wiss_mitarbeiter':
					$mitarbeiterListe = unserialize($elem['wiss_mitarbeiter']);
					$out .= '<td class="wiss_mitarbeiter">';
					$eintraege = array();
					if (!empty($mitarbeiterListe) && count($mitarbeiterListe)>0) {
						foreach($mitarbeiterListe as $mitarbeiterDaten) {
							if (!empty($mitarbeiterDaten['nachname']) && $mitarbeiterDaten['genehmigung']=='on') {
								$eintrag = '';
								if (!empty($mitarbeiterDaten['titel'])) {
									$eintrag .= $mitarbeiterDaten['titel'] . ' ';
								}
								$eintrag = $mitarbeiterDaten['vorname'] . ' ' . $mitarbeiterDaten['nachname'];
								if (!empty($mitarbeiterDaten['email'])) {
									$eintrag = '<a href="mailto:' . $mitarbeiterDaten['email'] . '">' . $eintrag . '</a>';
								}
								$eintraege[] = $eintrag;
							}
						}
						$out .= implode('<br />',$eintraege);
					}
					$out .= '</td>';
					break;
				case 'fe_cruser_id':
					$out .= '<td class="fe_cruser_id">';
					if (!empty($feUserdata)) {
						$out .= '<a href="mailto:' . $feUserdata['email'] . '?subject=Ihr Eintrag">' . $feUserdata['name'] . '</a>';
					}
					$out .= '</td>';
					break;
				case 'diss':
					if (!empty($elem[$key])) {
						$dissId = $elem[$key];
						/**@var tx_femanagement_model_promotionen $model */
						$model = 	t3lib_div::makeInstance('tx_femanagement_model_promotionen',$this->piBase,$this->pid);
						$promotion = $model->geTitle($dissId);
						$promotionenPageId = tx_femanagement_lib_util::getPageConfig('config.,promotionenPageId');
						if (!empty($promotionenPageId)) {
							$promotion = '<a href="https://www.hs-esslingen.de/index.php?id=' . $promotionenPageId . '&tx_femanagement[mode]=view&tx_femanagement[uid]=13&popup=1&norefresh=1">' . $promotion . '</a>';
						}
						$out .= '<td class="promotion">' . $promotion . '</td>';
					}
					break;
			}
		}
		$out .= '<td class="actions">';
		$previewLinkUrl = $this->createPreviewLinkUrl($elem['uid']);
		$out .= $this->showActions($elem,$permissions,$previewLinkUrl);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}

	function showTemplateListItem(&$templateCode,$elem,$fieldList,$permissions,$rowClass='') {
		$out = '<div class="row_elem ' . $rowClass . '">';
		$rowContent = $templateCode;

		//t3lib_div::debug($feUserdata,'feuserdata');
		//t3lib_div::debug($elem,'$elem');
		foreach ($fieldList as $key=>$field) {
			$elemVal = '';
			switch ($key) {
				case 'medien1':
					if (!empty($elem[$key])) {
						$pfad = 'uploads/tx_femanagement_forschungsprojekte/pics/' . $elem['medien1'];
						$bildadresse = tx_femanagement_lib_util::createJpgImage($pfad, 200);

						$img = '<img src="' . $bildadresse . '" />';
//						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$img,'textLink',$title,'_blank',TRUE,$this->singlePageConfig['pageId']);
						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$img,'textLink',$title,'_blank',TRUE,'',TRUE);
					}
					break;
				case 'title':
					$title = $this->getTitleCreateLinkSingleView();
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
//						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE,$this->singlePageConfig['pageId']);
						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE,'',TRUE);
					} else {
						$elemVal = $title;
					}
					unset($permissions[$viewIndex]);
					break;
				case 'start_datum':
				case 'end_datum':
					if (!empty($elem[$key])) {
						$elemVal = date('d.m.Y', $elem[$key]);
					} else {
						$elemVal = ' - ';
					}
					break;
				case 'beschreibung_kurz':
					$elemVal = $elem[$key];
					break;
				case 'wiss_leitung':
					$wissLeitung = unserialize($elem['wiss_leitung']);
					$leitung = '';
					$eintraege = array();
					if (!empty($wissLeitung) && count($wissLeitung)>0) {
						$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
						foreach ($wissLeitung as $eintrag) {
							/*
							 *  Wiss. Leitung alt
							*/
							if (isset($eintrag['value']) && $eintrag['valueSelect']) {
								$username = $eintrag['value'];
								$benutzerDaten = $modelFeUser->selectFields('username',$username,'fe_users','tx_hepersonen_akad_grad,first_name,last_name,tx_hepersonen_profilseite');
								$name = $benutzerDaten['first_name'] . ' ' . $benutzerDaten['last_name'];
								if (!empty($benutzerDaten['tx_hepersonen_akad_grad'])) {
									$name = $benutzerDaten['tx_hepersonen_akad_grad'] . ' ' . $name;
								}
								$eintrag = '<a target="_blank" href="index.php?id=' . $benutzerDaten['tx_hepersonen_profilseite'] . '">' . $name . '</a>';
								$eintraege[] = $eintrag;
							} else {
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
							}
							if (count($eintraege)>0) {
								$leitung = '<h4>Wissenschaftliche Leitung</h4>';
								$leitung .= '<div class="wiss_leitung">';
								$leitung .= implode('<br />',$eintraege);
								$leitung .= '</div>';
							}
							
						}
					}
					$elemVal = $leitung;
					break;
				case 'wiss_mitarbeiter':
					$mitarbeiterListe = unserialize($elem['wiss_mitarbeiter']);
					$mitarbeiter = '';
					$eintraege = array();

					if (is_array($mitarbeiterListe) && count($mitarbeiterListe)>0) {
						foreach($mitarbeiterListe as $mitarbeiterDaten) {
							if (!empty($mitarbeiterDaten['nachname']) && $mitarbeiterDaten['genehmigung']=='on') {
								$eintrag = '';
								if (!empty($mitarbeiterDaten['titel'])) {
									$eintrag = $mitarbeiterDaten['titel'] . ' ';
								}
								$eintrag .= $mitarbeiterDaten['vorname'] . ' ' . $mitarbeiterDaten['nachname'];
								if (!empty($mitarbeiterDaten['email'])) {
									$eintrag = '<a href="mailto:' . $mitarbeiterDaten['email'] . '">' . $eintrag . '</a>';
								}
								$eintraege[] = $eintrag;
							}
						}
						if (count($eintraege)>0) {
							$mitarbeiter = '<h4>Wissenschaftliche Mitarbeiter/innen</h4>';
							$mitarbeiter .= '<div class="mitarbeiter">';
							$mitarbeiter .= implode('<br />',$eintraege);
							$mitarbeiter .= '</div>';
						}
					}
					$elemVal = $mitarbeiter;
					break;
				case 'leitende_einrichtung':
					$model = 	t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->pid);
					$elemVal = $model->getTitle($elem['leitende_einrichtung']); 	
					break;
				case 'diss':
					$wert = '';
					$dissList = explode(',',$elem[$key]);

					if (intval($dissList[0])!=0) {
						/**@var tx_femanagement_model_promotionen $model */
						$model = 	t3lib_div::makeInstance('tx_femanagement_model_promotionen');
						$eintraege = array();
						foreach($dissList as $dissId) {
							$promotion = $model->geTitle($dissId);
							$promotionenPageId = 138533;
							$linkUrl = 'https://www.hs-esslingen.de/index.php?id=' . $promotionenPageId . '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $dissId . '&popup=1&norefresh=1';
							if (!empty($promotionenPageId)) {
								$promotion = '<a class="textLink popup_window" target="_blank" data-linkurl="' . $linkUrl . '" href="' . $linkUrl . '">' . $promotion . '</a>';
							}
							$eintraege[] = $promotion;
						}
						if (count($eintraege)==1) {
							$promotionen = $eintraege[0];
						} else {
							$promotionen = '<ul><li>' . implode('</li><li>',$eintraege) . '</li></ul>';
						}
						$elemVal = '<div class="promotion"><span class="label">' . $field . ':</span><br/>' . $promotionen . '</div>';
					}
					break;

			}
			$rowContent = str_replace('###' . strtoupper($key) . '###',$elemVal,$rowContent);
		}
		$out .= $rowContent;
		$out .= '</div>';
		return $out;
	}
	
	function getDataExportFieldTitle($field) {
			switch ($field) {
			case 'title':
				return 'Titel';
      case 'beschreibung_kurz':
        return 'Kurzbeschreibung';
      case 'leitende_einrichtung':
				return 'Fakultät/ Institut (federführend)';
			case 'fakultaet':
				return 'Beteiligte Fakultäten/ Institute';
      case 'faku_link':
				return 'Link zu Fakultät/Institut';
      case 'projektnummer':
				return 'Projektnummer';
      case 'foerderkennzeichen':
				return 'Förderkennzeichen';
      case 'foerderung_wer':
				return 'Förderung durch';
      case 'kooperationspartner':
				return 'Kooperations-/ Projektpartner';
      case 'start_datum':
        return 'Projektlaufzeit Start';
      case 'end_datum':
        return 'Projektlaufzeit Ende';
      case 'wiss_leitung':
        return 'Wissenschaftliche Leitung';
      case 'wiss_mitarbeiter':
        return 'Wissenschaftliche Mitarbeiter/innen';
      case 'foerdersumme':
        return 'Fördersumme';
      case 'webseite':
        return 'Spezielle Webseite des Projekts';
      case 'veroeff_title':
        return 'Veröffentlichungen';
      case 'veroeff_link':
        return 'Veröffentlichungen (Link)';
      case 'diss':
        return 'Promotionen';
      case 'medien1':
        return 'Grafik';
      case 'bildunterschrift1':
        return 'Bildunterschrift';
		}
		return '';
	}	
	
	function formatExportItem($field, $value) {
    if (empty($value)) {
      $value = ' ';
    }
    $modelEinrichtungen = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->pid);
    switch ($field) {
      case 'title':
      case 'projektnummer':
      case 'faku_link':
      case 'foerderkennzeichen':
      case 'veroeff_title':
      case 'veroeff_link':
      case 'diss':
      case 'bildunterschrift1':
        $out = $value;
        break;
      case 'medien1':
        $value = trim($value);
        if (!empty($value)) {
          $out = 'uploads/tx_femanagement_forschungsprojekte/pics/' . $value;
        } else {
          $out = '';
        }
        break;
      case 'beschreibung_kurz':
        $out = $value;
        break;
      case 'leitende_einrichtung':
        $out = $modelEinrichtungen->getTitle($value);
        break;
      case 'wiss_leitung':
        $personen = unserialize($value);
        $eintraege = array();
        $out = 'keine';
        foreach ($personen as $personenDaten) {
          $eintrag = '';
          if (!empty($personenDaten['titel'])) {
            $eintrag = $personenDaten['titel'] . ' ';
          }
          $eintrag .= $personenDaten['vorname'] . ' ' . $personenDaten['nachname'];
/*
          if (!empty($personenDaten['email'])) {
            $eintrag .= ', ' . $personenDaten['email'];
          }
*/
          $eintraege[] = $eintrag;
        }
        if (count($eintraege)>0) {
          $out = implode('<br/>',$eintraege);
        }
        break;
      case 'wiss_mitarbeiter':
        $out = 'keine';
        if (!empty($value)) {
          $personen = unserialize($value);
          $eintraege = array();
          $out = 'keine';
          foreach ($personen as $personenDaten) {
            if (!empty($personenDaten['nachname'])) {
              $eintrag = '';
              if (!empty($personenDaten['titel'])) {
                $eintrag = $personenDaten['titel'] . ' ';
              }
              $eintrag .= $personenDaten['vorname'] . ' ' . $personenDaten['nachname'];
  /*
              if (!empty($personenDaten['email'])) {
                $eintrag .= ', ' . $personenDaten['email'];
              }
  */
              $eintraege[] = $eintrag;
            }
          }
          if (count($eintraege)>0) {
            $out = implode('<br/>',$eintraege);
          }
        }
        break;
      case 'foerdersumme':
        $out = $value;
        break;
      case 'fakultaet':
        $out = 'keine';
        if (!empty($value)) {
          $ids = explode(',',$value);
          $eintraege = array();
          foreach ($ids as $id) {
            $eintraege[] = $modelEinrichtungen->getTitle($id);
          }
          $out = implode('<br />',$eintraege);
        }
        break;
      case 'foerderung_oeffentlich':
      case 'foerderung_privat':
      	if ($field=='foerderung_oeffentlich') {
      		$art = 'oeffentlich';
      	} else {
      		$art = 'privat';
      	}
      	$out = 'keine';
        $foerdererListe = unserialize($value);
  
        $eintraege = array();
        foreach ($foerdererListe as $foerderer) {
          if (!empty($foerderer['einrichtung']) && $foerderer['art']==$art) {
            $eintrag = $foerderer['einrichtung'];
            if (!empty($foerderer['foerderprogramm'])) {
            	$eintrag .= ' / ' . $foerderer['foerderprogramm'];
            }
            $eintraege[] = $eintrag;
          }
        }
        if (count($eintraege)>0) {
          $out = implode('<br />',$eintraege);
        }
        break;        
      case 'foerderung_wer':
        $out = 'keine';
        $foerdererListe = unserialize($value);
        $eintraege = array();
        foreach ($foerdererListe as $foerderer) {
          if (!empty($foerderer['einrichtung'])) {
            $eintrag['einrichtung'] = '<span class="foerderer">' . $foerderer['einrichtung'] . '</span>';
            $eintraege[] = $foerderer['einrichtung'];
          }
        }
        if (count($eintraege)>0) {
          $out = implode('<br />',$eintraege);
        }
        break;
       case 'kooperationspartner':
        $out = 'keine';
        $partnerListe = unserialize($value);
        $eintraege = array();
        foreach ($partnerListe as $partner) {
          if (!empty($partner['einrichtung'])) {
            $eintraege[] = $partner['einrichtung'];
          }
        }
        if (count($eintraege)>0) {
          $out = implode('<br />',$eintraege);
        }
        break;
			case 'start_datum':
			case 'end_datum':
				if (!empty($value)) {
					$out = date('d.m.Y', $value);
				} else {
					$out = ' ';
				}
				break;
			case 'fe_cruser_id':
				$out = '';
				break;
				
			default:
				$out = $value;
				break;
		}
		return $out;
	}

	function createPreviewLinkUrl($uid) {
		$additionalParams = '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $uid;
		$linkUrl = 'index.php?id=' . $this->pageId . $additionalParams;
		return $linkUrl;
	}

	function createPreview($uid, $text, $linkClass, $title, $target='_blank') {
		$linkUrl = $this->createPreviewLinkUrl($uid);
		$link = '<a class="' . $linkClass . '" target="' . $target . '" class="' . $linkClass . 
					  '" title="' . $title . '" href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}

	function getNewelemButtonTitle() {
		return 'Neuen Eintrag anlegen'; 
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray['where'] = ' WHERE TRUE';
		$configArray['joins'] = array(
				array('table'=>'tx_femanagement_forschung_einrichtungen',
						'fields'=>'title as einrichtung, uid as leitende_einrichtung',
						'joinFieldLocal'=>'uid',
						'joinFieldMain'=>'leitende_einrichtung',
						'mode'=>'LEFT JOIN',
				),
		);
		if (isset($this->args['volltextsuche'])) {
			$configArray['where'] .= ' AND (tx_femanagement_forschungsprojekte.title LIKE "%' . $this->args['volltextsuche'] . '%"';
			$configArray['where'] .= ' OR tx_femanagement_forschungsprojekte.beschreibung_kurz LIKE "%' . $this->args['volltextsuche'] . '%")';
		}
		if (!empty($this->args['personensuche'])) {
			$configArray['where'] .= ' AND (tx_femanagement_forschungsprojekte.wiss_leitung  LIKE "%' . $this->args['personensuche'] . '%" OR ' .
															 ' tx_femanagement_forschungsprojekte.wiss_mitarbeiter  LIKE "%' . $this->args['personensuche'] . '%")';
		}
		if (!empty($this->args['einrichtung']) && $this->args['einrichtung']!='all') {
			$configArray['where'] .= ' AND (tx_femanagement_forschungsprojekte.leitende_einrichtung=' . $this->args['einrichtung'] . ' OR ' .
															 ' FIND_IN_SET (' . $this->args['einrichtung'] . ',tx_femanagement_forschungsprojekte.fakultaet))';
		}
		
		if (!empty($this->args['start_datum'])) {
			$date = explode('.',$this->args['start_datum']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.start_datum<=' . $dateTstamp;
		}
		if (!empty($this->args['end_datum'])) {
			$date = explode('.',$this->args['end_datum']);
			$dateTstamp = mktime(0, 0, 0, $date[1], $date[0], $date[2]);
			$configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.end_datum>=' . $dateTstamp;
		}
		if (isset($this->args['az']) AND $this->args['az']!='all') {
			$whereAz = ' AND (tx_femanagement_forschungsprojekte.title LIKE "' . $this->args['az'] . '%")';
		} else {
			$whereAz = '';
		}
		if ($this->args['hidden']!='all') {
			$configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.hidden=' . $this->args['hidden'];
		}
		if ($this->args['deleted']!='all') {
			$configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.deleted=' . $this->args['deleted'];
		}
    $projektStatus = 'alle';
    $bezugsJahr =   date('Y', time());
    if ($this->args['projektstatus']!='all') {
      $bezugsJahr = intval($this->args['bezugsjahr']);
      if ($bezugsJahr<2010 || $bezugsJahr>2099) {
        t3lib_utility_Debug::debug('Bitte tragen Sie ein gültiges Bezugsjahr ein','Ungültiges Bezugsjahr');
      } else {
        $projektStatus = $this->args['projektstatus'];
        $newyearCurrent = mktime(0, 0, 0, 1, 1, $bezugsJahr);
        $newyearNext = mktime(0, 0, 0, 1, 1, $bezugsJahr+1);
        if ($projektStatus=='abgeschlossene') {
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.start_datum<' . $newyearCurrent;
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.end_datum>=' . $newyearCurrent;
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.end_datum<=' . $newyearNext;
        } else if ($projektStatus=='laufende') {
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.start_datum<' . $newyearCurrent;
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.end_datum>' . $newyearNext;
        } else if ($projektStatus=='neu') {
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.start_datum>' . $newyearCurrent;
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.start_datum<' . $newyearNext;
          $configArray['where'] .= ' AND tx_femanagement_forschungsprojekte.end_datum>' . $newyearNext;
        }
      }
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
			switch ($this->args['sortField']) {
				case 'leitende_einrichtung':
					$sortField = 'einrichtung';
					break;
				default:
					$sortField = 'tx_femanagement_forschungsprojekte.' . $this->args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'tx_femanagement_forschungsprojekte.title ASC';
		}
		
		if (!empty($this->args['export'])) {
      $configArray['fields'] = 'title,beschreibung_kurz,medien1,bildunterschrift1,leitende_einrichtung,fakultaet,projektnummer,foerderkennzeichen,foerderung_wer,kooperationspartner,start_datum,end_datum,wiss_leitung,wiss_mitarbeiter,foerdersumme,diss';
      unset($configArray['joins']);
      $sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'forschungsbereiche_' . $bezugsJahr . '_' . $projektStatus);
			exit();
		} else {
			$configArray['fields'] = 'uid,title,start_datum,end_datum,leitende_einrichtung,beschreibung_kurz,wiss_leitung,wiss_mitarbeiter,medien1,diss,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
	
			$out = '';
			
			if (isset($this->args['az']) && $this->args['az']>0) {
				$daten = $this->model->selectSqlData($sqlQuery);
				$out .= $this->createAzList($daten,'title');
			}
			if (!empty($whereAz)) {
				$configArray['where'] .= $whereAz;
				$sqlQuery = $this->model->buildJoinQuery($configArray);
			}
			
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
	}

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
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/forschung/class.tx_femanagement_view_forschung_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/forschung/class.tx_femanagement_view_forschung_list.php']);
}

?>
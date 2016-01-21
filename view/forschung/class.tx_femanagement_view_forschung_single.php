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
 *
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */
class tx_femanagement_view_forschung_single extends tx_femanagement_view_form_single {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function initElemCodeSingle($mode)	{
		if (!empty($this->wrapClass)) {
			$classCode = ' class="' . $this->wrapClass . '"';
		} else {
			$classCode = '';
		}
    $out = '';
		if ($mode=='view') {
			$out .= '<div id="fe_management_viewdata"' . $classCode . '>';
		} else {
			if (!empty($this->title)) {
				$out .= '<h2>' . $this->title . '</h2>';
			}
			$out .= '<form autocomplete="off" id="fe_management" action="" enctype="multipart/form-data" method="POST" ' . $classCode . '>';
		}
		return $out;
	}
	
	function showSingleView(&$model,$fields,$mode,$aktuelleSeite,$uid)	{

		$config = tx_femanagement_lib_util::getPageConfig('config.,singleview.');
		$templateFile = $config['template'];
		if (!empty($templateFile)) {
			$templateFile = 'typo3conf/ext/fe_management/' . $templateFile;
			$templateCode = $this->piBase->cObj->fileResource($templateFile);
			$singleView = $this->piBase->cObj->getSubpart($templateCode,'###TEMPLATE_SINGLE###');
			$fieldList = implode(',',array_keys($fields));
			$data = $model->selectFieldData($uid,$fieldList);
			$markerArray = array();
			$modelEinrichtungen = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen',$this->piBase,$this->pid);		
			foreach ($data as $field=>$value) {
				$wert = '';
				if (!empty($value)) {
					switch ($field) {
						case 'medien1':
						case 'medien2':
							if ($field=='medien1') {
								$index = 1;
							} else {
								$index = 2;
							}
							if (!empty($data['bildunterschrift' . $index])) {
								$title = ' title="' . $data['bildunterschrift' . $index] . '" ';
							} else {
								$title = '';
							}
							$pfad = 'uploads/tx_femanagement_forschungsprojekte/pics/' . $value;

							$imgConfig = array();
							$imgConfig['file'] = $pfad;
							$imgConfig['file.']['maxW'] = 600;
							$imgConfig['file.']['maxH'] = 400;
							$bildadresse = $this->piBase->cObj->IMG_RESOURCE($imgConfig);

							$wert = '<div class="image">' .
											'<a class="lightbox" data-fancybox-group="lightbox' . $uid . '" ' . $title . ' href="' . $pfad . '"><img src="' . $bildadresse . '" /></a>';
							if (!empty($data['bildunterschrift' . $index])) {
								$wert .= '<div class="caption">' . $data['bildunterschrift' . $index] . '</div>';
							}
							$wert .= '</div>';
							break;
						case 'foerderung_wer':
							$wert = '';
							$foerdererListe = unserialize($value);
							$eintraegePrivat = array();
							$foerderung = array();
							$foerderungPrivat = '';
							$eintraegeOeffentlich = '';
							foreach ($foerdererListe as $foerderer) {
								if (!empty($foerderer['einrichtung']) && $foerderer['art']!='' ) {
									if (!empty($foerderer['logo'])) {
										$pfad = '/uploads/tx_femanagement_forschungsprojekte/pics/' . $foerderer['logo'];
										$logo = '<img src="' . $pfad . '" />';
									} else {
										$logo = '';
									}
									$eintrag['logo'] = '<span class="logo">' . $logo . '</span>';
									$eintrag['einrichtung'] = '<span class="foerderer">' . $foerderer['einrichtung'] . '</span>';
									if ($foerderer['art']=='oeffentlich') {
										$eintraegeOeffentlich[] = $eintrag;
									} else if ($foerderer['art']=='privat') {
										$eintraegePrivat[] = $eintrag;
									}

								}
							}
							if (count($eintraegeOeffentlich)>0) {
								$wert .= '<div class="foerderung_oeffentlich"><span class="label">Förderung öffentlich:</span><span class="value">';
								foreach($eintraegeOeffentlich as $eintrag) {
									$wert .= $eintrag['logo'] . $eintrag['einrichtung'] . '<br />';
								}
								$wert .= '</span></div>';
							}
							if (count($eintraegePrivat)>0) {
								$wert .= '<div class="foerderung_privat"><span class="label">Förderung privat:</span><span class="value">';
								foreach($eintraegePrivat as $eintrag) {
									$wert .= $eintrag['logo'] . $eintrag['einrichtung'] . '<br />';
								}
								$wert .= '</span></div>';
							}

							break;
							/*
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
									if (!empty($foerderer['einrichtung']) && $foerderer['genehmigung']=='on' && $foerderer['art']==$art) {
										$eintrag['einrichtung'] = '<span class="foerderer">' . $foerderer['einrichtung'] . '</span>';
										$eintraege[] = $foerderer['einrichtung'];
									}
								}
								if (count($eintraege)>0) {
									$out = implode('<br />',$eintraege);
								}
								break;
							*/
						case 'kooperationspartner':
							$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$partnerListe = unserialize($value);
							$eintraege = array();
							foreach ($partnerListe as $partner) {
								if (!empty($partner['einrichtung'])) {
									if (!empty($partner['logo'])) {
										$pfad = '/uploads/tx_femanagement_forschungsprojekte/pics/' . $partner['logo'];
										$logo = '<img src="' . $pfad . '" />';
									} else {
										$logo = '';
									}
									$eintrag['logo'] = '<span class="logo">' . $logo . '</span>';
									$eintrag['einrichtung'] = '<span class="partner">' . $partner['einrichtung'] . '</span>';
									$eintraege[] = $eintrag;
								}
							}
							foreach($eintraege as $eintrag) {
								$wert .= $eintrag['logo'] . $eintrag['einrichtung'] . '<br />';
							}

							$wert .= '</span></div>';
							break;
						case 'beschreibung_lang':
							$wert = '<div class="document"><span class="label">' . $fields[$field] . ':</span><span class="value"><a target="_blank" href="/uploads/tx_femanagement_forschungsprojekte/media/' . $value . '">' . $value . '</a></span></div>';
							break;
						case 'downloads':
							if (!empty($data['downloads_beschriftung'])) {
								$label = $data['downloads_beschriftung'];
							} else {
								$label = $value;
							}
							$wert = '<div class="document"><span class="label">' . $fields[$field] . ':</span><span class="value"><a target="_blank" href="/uploads/tx_femanagement_forschungsprojekte/media/' . $value . '">' . $label . '</a></span></div>';
							break;
						case 'title':
							$wert = '<h1>' . $value . '</h1>';
							break;
						case 'leitende_einrichtung':
							$leitendeEinrichtung = $modelEinrichtungen->getTitle($value);
							$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $leitendeEinrichtung . '</span></div>';
							break;
						case 'fakultaet':
							if (!empty($value)) {
								$ids = explode(',',$value);
								$eintraege = array();
								foreach ($ids as $id) {
									$eintraege[] = $modelEinrichtungen->getTitle($id);
								}
								$fakultaetsBezeichnungen = implode('<br />',$eintraege);
								$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">' .
										$fakultaetsBezeichnungen .
										'</span></div>';
							}
							break;
						case 'beschreibung_kurz':
							$wert = '<div class="beschreibung">' . $value . '</div>';
							break;
						case 'start_datum':
						case 'end_datum':
							$datum = date('d.m.Y', $value);
							$wert = '<div class="datum"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $datum . '</span></div>';
							break;
						case 'wiss_leitung_alt':
							$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
							$wert = '<div class="personenseite"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$leiter = unserialize($value);
							$eintraege = array();
							foreach ($leiter as $eintrag) {
								$username = $eintrag['value'];
								$benutzerDaten = $modelFeUser->selectFields('username',$username,'fe_users','tx_hepersonen_akad_grad,first_name,last_name,email,tx_hepersonen_profilseite');
								$name = $benutzerDaten['first_name'] . ' ' . $benutzerDaten['last_name'];
								if (!empty($benutzerDaten['tx_hepersonen_akad_grad'])) {
									$name = $benutzerDaten['tx_hepersonen_akad_grad'] . ' ' . $name;
								}
								$eintrag = '<a target="_blank" href="index.php?id=' . $benutzerDaten['tx_hepersonen_profilseite'] . '">' . $name . '</a>';
								$eintraege[] = $eintrag;
							}
							$wert .= implode('<br />',$eintraege);
							$wert .= '</span></div>';

							break;
						case 'wiss_leitung':
							$wert = '<div class="personenseite"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$mitarbeiter = unserialize($value);
							$eintraege = array();
							foreach ($mitarbeiter as $mitarbeiterDaten) {
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
							$wert .= implode('<br />',$eintraege);
							$wert .= '</span></div>';
							break;
						case 'wiss_mitarbeiter':
							if (!empty($value)) {
								$label = '<span class="label">' . $fields[$field] . ':</span>';
								$mitarbeiter = unserialize($value);
								$eintraege = array();
								$mitarbeiterEintraege = '';
								foreach ($mitarbeiter as $mitarbeiterDaten) {
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
									$mitarbeiterEintraege = '<span class="value">' . implode('<br />',$eintraege) . '</span>';
									$wert = '<div class="personenseite">' . $label . $mitarbeiterEintraege . '</div>';
								}
							}
							break;
						case 'webseite':
							if (!empty($value)) {
								if (strpos($value,'www.')!==FALSE) {
									if (strpos($value,'http')===FALSE) {
										$value = 'http://' . $value;
									}
									$link = '<a href="' . $value . '">' . $value . '</a>';
								} else {
									$link = $value;
								}
								$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $link . '</span></div>';
							}
							break;
						case 'diss':
							$wert = '';
							$dissList = explode(',',$value);
							if (intval($dissList[0])!=0) {
								$label = '<span class="label">' . $fields[$field] . ':</span>';
								$wert = '<div class="promotion">' . $label;
								/**@var tx_femanagement_model_promotionen $model */
								$model = 	t3lib_div::makeInstance('tx_femanagement_model_promotionen');
								$eintraege = array();
								foreach ($dissList as $dissId) {
									$promotion = $model->geTitle($dissId);
									$promotionenPageId = 138533;
									$linkUrl = 'https://www.hs-esslingen.de/index.php?id=' . $promotionenPageId . '&tx_femanagement[mode]=view&tx_femanagement[uid]=' . $dissId . '&popup=1&norefresh=1';
									if (!empty($promotionenPageId)) {
										$promotion = '<a class="textLink popup_window" target="_blank" data-linkurl="' . $linkUrl . '" href="' . $linkUrl . '">' . $promotion . '</a>';
									}
									$eintraege[] = $promotion;
								}
								$promotionen = implode('<br />',$eintraege);
								$wert .= '<span class="value">' . $promotionen . '</span>';
								$wert .= '</div>';
							}
							break;

						default:
							$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $value . '</span></div>';
							break;
					}
				}
				$markerArray['###' . strtoupper($field) . '###'] = $wert;
			}
			$out .= $this->piBase->cObj->substituteMarkerArrayCached($singleView,$markerArray);
			$out .= $this->exitElemCodeSingle($mode);
			$out .= '<script type="text/javascript">
							function formatTitle(title, currentArray, currentIndex, currentOpts) {
							    return "<div id=\'bild-title\'>" + (title && title.length ? "<b>" + title + "</b>" : "" ) + "Bild " + (currentIndex + 1) + " von " + currentArray.length + "</div>";
							}
							$(".fancyBoxImg").fancybox({
							"fitToView"	: true,
							"autoSize"	: true,
							"showCloseButton"	:	true,
							"enableEscapeButton"	:	true,
							"hideOnContentClick" : true,
							"titlePosition" 		: "inside",
							"titleFormat"		: formatTitle
							});
							</script>';
		} else {
			$singleView = 'Kein Template ausgewählt';
		}
		$out .= '<script>
$(document).ready(function(){
   $(".bottom>div:even").addClass("even");
   $(".bottom>div:odd").addClass("odd");
});
</script>
		';
		return $out;
	}
	
	function getEinrichtungenList($value='',$pid,$exclusive=array(),$excludeIds=array()) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		$datenOrig = $model->getList('',$pid);
		$daten = array();
		if (count($exclusive)>0) {
			foreach($datenOrig as $key=>$title) {
				if (in_array($key,$exclusive)) {
					$daten[$key] = $title;
				}
			}
		} else if (count($exclude)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$exclude)) {
					$daten[$key] = $title;
				}
			}
		} else {
			$daten = $datenOrig;
		}
		return $this->getOptionList($daten,$value);
	}
	
	function getEinrichtungenTitles($list) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_forschung_einrichtungen');
		$titles = array();
		foreach ($list as $id) {
			$titles[] = $model->getTitle($id);
		}
		return implode('<br/>',$titles);
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/forschung/class.tx_femanagement_view_forschung.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/forschung/class.tx_femanagement_view_forschung.php']);
}

?>
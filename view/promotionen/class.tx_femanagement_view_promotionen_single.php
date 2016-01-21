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
class tx_femanagement_view_promotionen_single extends tx_femanagement_view_form_single {
	
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
						case 'grafik':
							if (!empty($data['bildunterschrift'])) {
								$title = ' title="' . $data['bildunterschrift'] . '" ';
							} else {
								$title = '';
							}
							$pfad = 'uploads/tx_femanagement_promotionen/pics/' . $value;
							
							$imgConfig = array();
							$imgConfig['file'] = $pfad;
							$imgConfig['file.']['maxW'] = 600;
							$imgConfig['file.']['maxH'] = 400;
							$bildadresse = $this->piBase->cObj->IMG_RESOURCE($imgConfig);
							
							$wert = '<div class="image">' . 
											'<a class="lightbox" data-fancybox-group="lightbox' . $uid . '" ' . $title . ' href="' . $pfad . '"><img src="' . $bildadresse . '" /></a>';
							if (!empty($data['bildunterschrift'])) {
								$wert .= '<div class="caption">' . $data['bildunterschrift'] . '</div>';
							}
							$wert .= '</div>';
							break;

						case 'kooperations_uni':
							$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$kooperationsUniListe = unserialize($value);
							$eintraege = array();

							foreach ($kooperationsUniListe as $uni) {
								if (!empty($uni['uni'])) {
									if (!empty($uni['logo'])) {
										$pfad = '/uploads/tx_femanagement_promotionen/pics/' . $uni['logo'];
										$logo = '<img src="' . $pfad . '" />';
									} else {
										$logo = '';
									}
									$eintrag['logo'] = '<span class="logo">' . $logo . '</span>';
									$eintrag['uni'] = '<span class="uni">' . $uni['uni'] . '</span>';
									$eintraege[] = $eintrag;
								}
							}
							foreach($eintraege as $eintrag) {
								$wert .= $eintrag['logo'] . $eintrag['uni'] . '<br />';
							}
							
							$wert .= '</span></div>';
							break;
						case 'beschreibung_lang':
							$wert = '<div class="document"><span class="label">' . $fields[$field] . ':</span><span class="value"><a target="_blank" href="/uploads/tx_femanagement_promotionen/media/' . $value . '">' . $value . '</a></span></div>';
							break;
						case 'title':
							$wert = '<h1>' . $value . '</h1>';
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
						case 'erst_betreuer':
							$wert = '<div class="personenseite"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$personen = unserialize($value);
							$eintraege = array();
							foreach ($personen as $personenDaten) {
									$eintrag = '';
									if (!empty($personenDaten['titel'])) {
										$eintrag = $personenDaten['titel'] . ' ';
									}
									$eintrag .= $personenDaten['vorname'] . ' ' . $personenDaten['nachname'];
									if (!empty($personenDaten['email'])) {
										$eintrag = '<a href="mailto:' . $personenDaten['email'] . '">' . $eintrag . '</a>';
									}
									$eintraege[] = $eintrag;
							}
							$wert .= implode('<br />',$eintraege);
							$wert .= '</span></div>';
							break;
						case 'zweit_betreuer':
							$wert = '<div class="personenseite"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$personen = unserialize($value);
							$eintraege = array();
							foreach ($personen as $personenDaten) {
								$eintrag = '';
								if (!empty($personenDaten['titel'])) {
									$eintrag = $personenDaten['titel'] . ' ';
								}
								$eintrag .= $personenDaten['vorname'] . ' ' . $personenDaten['nachname'];
								if (!empty($personenDaten['email'])) {
									$eintrag = '<a href="mailto:' . $personenDaten['email'] . '">' . $eintrag . '</a>';
								}
								$eintraege[] = $eintrag;
							}
							$wert .= implode('<br />',$eintraege);
							$wert .= '</span></div>';

							break;
						default:
							$wert = '<div class="' . $field . '"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $value . '</span></div>';
							break;
					}
				}
				$markerArray['###' . strtoupper($field) . '###'] = $wert;
			}
			$out = $this->piBase->cObj->substituteMarkerArrayCached($singleView,$markerArray);
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
		} else if (count($excludeIds)>0) {
			foreach($datenOrig as $key=>$title) {
				if (!in_array($key,$excludeIds)) {
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/promotionen/class.tx_femanagement_view_promotionen.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/promotionen/class.tx_femanagement_view_promotionen.php']);
}

?>
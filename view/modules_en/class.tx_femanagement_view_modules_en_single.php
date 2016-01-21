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
class tx_femanagement_view_modules_en_single extends tx_femanagement_view_form_single {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
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
			foreach ($data as $field=>$value) {
				$wert = '';
				if (!empty($value)) {
					switch ($field) {
						case 'title':
							$wert = '<h1>' . $value . '</h1>';
							break;
						case 'zusatz':
							$wert = '<h2>' . $value . '</h2>';
							break;
						case 'verantwortliche':
							$modelFeUser = t3lib_div::makeInstance('tx_femanagement_model_general_userdata');
							$wert = '<div class="personenseite"><span class="label">' . $fields[$field] . ':</span><span class="value">';
							$verantwortliche = unserialize($value);
							$eintraege = array();
							foreach ($verantwortliche as $eintrag) {
								$username = $eintrag['value'];
								$benutzerDaten = $modelFeUser->selectFields('username',$username,'fe_users','tx_hepersonen_akad_grad,first_name,last_name,email,tx_hepersonen_profilseite');
								if (empty($benutzerDaten)) {
									$eintraege[] = $eintrag['valueSelect'];
								} else {
									$name = $benutzerDaten['first_name'] . ' ' . $benutzerDaten['last_name'];
									if (!empty($benutzerDaten['tx_hepersonen_akad_grad'])) {
										$name = $benutzerDaten['tx_hepersonen_akad_grad'] . ' ' . $name;
									}
									$eintrag = '<a target="_blank" href="index.php?id=' . $benutzerDaten['tx_hepersonen_profilseite'] . '">' . $name . '</a>';
									$eintraege[] = $eintrag;
								}
							}
							$wert .= implode('<br />',$eintraege);
							$wert .= '</span></div>';
	
							break;
						case 'campus':
							$standort = $model->gibStandortTitel($value);
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $standort . '</span></div>';
							break;
						case 'fakultaet':
							$fakultaet = $model->gibFakultaetsTitel($value);
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $fakultaet . '</span></div>';
							break;
						case 'semester':
							$semester = $model->gibSemesterTitel($value);
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $semester . '</span></div>';
							break;
						case 'level':
							$level = $model->gibLevelTitel($value);
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $level . '</span></div>';
							break;
						case 'studiengang':
							$studiengangListe = unserialize($value);
							$studiengangTitel = array();
							foreach ($studiengangListe as $studiengang) {
								$studiengangTitel[] = $model->gibStudiengangTitel($studiengang['studiengang']);						
							}
							$studiengaenge .= implode('<br />' , $studiengangTitel);
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $studiengaenge . '</span></div>';
							break;
						case 'download':
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value"><a target="_blank" href="uploads/tx_femanagement_module_en/' . $value . '">' . $data['title'] . '</a></span></div>';
							break;
						case 'link':
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value"><a target="_blank" href="' . $value . '">' . $data['title'] . '</a></span></div>';
							break;
						default:
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $value . '</span></div>';
						break;
					}
				}
				$markerArray['###' . strtoupper($field) . '###'] = $wert;
			}
			$out .= $this->piBase->cObj->substituteMarkerArrayCached($singleView,$markerArray);
			$out .= $this->exitElemCodeSingle($mode);
		} else {
			$out = 'Kein Template ausgewählt';
		}
		return $out;
	}
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/modules_en/class.tx_femanagement_view_modules_en_single.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/modules_en/class.tx_femanagement_view_modules_en_single.php']);
}

?>
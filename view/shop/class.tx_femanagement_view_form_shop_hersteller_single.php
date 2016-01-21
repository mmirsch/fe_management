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
class tx_femanagement_view_form_shop_hersteller_single extends tx_femanagement_view_form_single {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function showSingleData(&$model,$uid)	{
		$config = tx_femanagement_lib_util::getPageConfig('config.,singleview.');
		$templateFile = $config['template'];
		$fields = $config['showFields.'];
		if (!empty($templateFile)) {
			$templateFile = 'typo3conf/ext/fe_management/' . $templateFile;
			$templateCode = $this->piBase->cObj->fileResource($templateFile);
			$singleView = $this->piBase->cObj->getSubpart($templateCode,'###TEMPLATE_SINGLE###');
			$fieldList = implode(',',array_keys($fields));
			$data = $model->selectFieldData($uid,$fieldList);
			$markerArray = array();
			foreach ($fields as $field=>$value) {
				$wert = '';
				if (!empty($value)) {
					switch ($field) {
						case 'bild':
							$wert = '<div class="image"><img width="200px" src="/uploads/tx_hebest/' . $data[$field] . '" /></div>';
							break;
						case 'title':
							$wert = '<h2>' .  $data[$field] . '</h2>';
							break;
						case 'adresse':
							if (!empty($data['anschrift']) || !empty($data['plz']) || !empty($data['stadt']) ) {
								$wert = '<div class="adresse">';
								$wert .= $data['title'] . '</br>';
								if (!empty($data['anschrift']) ) {
									$wert .= $data['anschrift'] . '</br>';
								}
								if (!empty($data['plz']) ) {
									$wert .= $data['plz'] . ' ';
								}
								if (!empty($data['stadt']) ) {
									$wert .= $data['stadt'];
								}
								$wert .= '</div>';
							}
							break;
						case 'bemerkung':
							$wert = '<div class="adresse">' . $data[$field] . '</div>';
							break;
						case 'tel':
							if (!empty($elem[$key])) {
								$wert = '<div class="tel"><strong>Tel.:</strong> ' . $data[$field] . '</div>';
							}
							break;
						case 'fax':
							if (!empty($elem[$key])) {
								$wert = '<div class="tel"><strong>Fax.:</strong> ' . $data[$field] . '</div>';
							}
							break;
						case 'www':
							if (!empty($elem[$key])) {
								$wert = '<div class="www"><strong>www:</strong> <a href="' . $data[$field] . '">' . $data[$field] . '</a></div>';
							}
							break;
						case 'email':
							if (!empty($data[$field])) {
								$emailListe = explode('+',$data[$field]);
								if (count($emailListe)==1) {
									$wert = '<div class="email"><strong>E-Mail:</strong> <a href="mailto:' . $data[$field] . '">' . $data[$field] . '</a></div>';
								} else {
									$wert = '<div class="email"><strong>E-Mail:</strong> ';
									$emails = array();
									foreach ($emailListe as $email) {
										$emails[] = '<a href="mailto:' . trim($email) . '">' . trim($email) . '</a>';
									}
									$wert .= implode(', ',$emails);
									$wert .= '</div>';
								}
							}
							break;							
					}
				}
				$markerArray['###' . strtoupper($field) . '###'] = $wert;
			}
			$singleViewHtml = $this->piBase->cObj->substituteMarkerArrayCached($singleView,$markerArray);
			if ($config['mode']!='fancybox') {
				$singleViewHtml .= '<br class="clear"/>
				<input type="button" value="Fenster schliessen" onclick="javascript:window.close();">
				';
			}
		} else {
			$singleView = 'Kein Template ausgewählt';
		}
		return $singleViewHtml;
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_hersteller_single.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_hersteller_single.php']);
}

?>
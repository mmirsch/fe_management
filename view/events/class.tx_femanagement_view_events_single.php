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
class tx_femanagement_view_events_single extends tx_femanagement_view_form_single {
	
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
            case 'subtitle':
              $wert = '<h2>' . $fields[$field] . ': ' . $value . '</h2>';
              break;
 						case 'pic':
              $pfad = 'uploads/tx_femanagement_events/' . $value;

              $imgConfig = array();
              $imgConfig['file'] = $pfad;
              $imgConfig['file.']['maxW'] = 300;
              $imgConfig['file.']['maxH'] = 200;
              $bildadresse = $this->piBase->cObj->IMG_RESOURCE($imgConfig);
              $wert = '<div class="image">' .
                '<a class="lightbox" data-fancybox-group="lightbox' . $uid . '" href="' . $pfad . '"><img src="' . $bildadresse . '" /></a>';
              $wert .= '</div>';
              break;
						default:
							$wert = '<div class="link"><span class="label">' . $fields[$field] . ':</span><span class="value">' . $value . '</span></div>';
						break;
					}
				}
				$markerArray['###' . strtoupper($field) . '###'] = $wert;
			}
			$out = $this->piBase->cObj->substituteMarkerArrayCached($singleView,$markerArray);
			$out .= $this->exitElemCodeSingle($mode);
		} else {
			$out = 'Kein Template ausgewählt';
		}
		return $out;
	}
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/events/class.tx_femanagement_view_events_single.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/events/class.tx_femanagement_view_events_single.php']);
}

?>
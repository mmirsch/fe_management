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
class tx_femanagement_view_form_shop_article_cart extends tx_femanagement_view_form {
	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function showCart($model,$shopId,$feldListe) {		
		$pageId = tx_femanagement_lib_util::getPageConfig('pageId');
		$currentSessionData = tx_femanagement_lib_util::getSessionData($pageId);
		if (!empty($currentSessionData[$shopId]['cart']))	{
			$warenkorb = $currentSessionData[$shopId]['cart'];
		} else {
			$warenkorb = array();
		}
//		$warenkorb = tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
  	$model = t3lib_div::makeInstance('tx_femanagement_model_shop_article',NULL,$pid);
  	$shopPageId = tx_femanagement_lib_util::getPageConfig('config.,shopPageId');
		if (empty($warenkorb))	{
  		$out = '<h3>Keine Artikel im Warenkorb</h3>';
  		$out .= '<h3><a href="index.php?id=' . $shopPageId . '">Zurück zur Artikel-Liste</a></h3>';
		} else {
  		$out = '<h2>Warenkorb</h2>';
			$out .= '<h3><a href="index.php?id=' . $shopPageId . '">Weitere Artikel hinzufügen</a></h3>';
			$out .= '<table class="cart_table">';
			$out .= '<thead><tr>';
			foreach ($feldListe as $feld=>$feldBezeichnung) {
				$out .= '<th>' . $feldBezeichnung . '</th>';
			}
			$out .= '<th></th>';
			$out .= '</tr></head>';
			$out .= '<tbody>';
			$summe = 0;
			foreach ($warenkorb as $id=>$eintrag) {
				$out .= '<tr id="tr_' . $id . '">';
				foreach ($feldListe as $feld=>$feldBezeichnung) {
					$cssClass = '';
					switch ($feld) {
					case 'preis':
						$wert = sprintf("%01.2f &euro;", $eintrag['price']);
						$cssClass = 'preis right';
						break;
					case 'summe':
						$summe += $eintrag['summe'];
						$wert = sprintf("%01.2f &euro;", $eintrag['summe']);
						$cssClass = 'summe right';
						break;
					case 'anzahl':
						if (empty($eintrag['price'])) {
							$preis = '0.00';
						} else {
							$preis = $eintrag['price'];
						}
						$wert = '<input name="' . $preis . '" id="count_' . $id . '" class="edit_anzahl" value="' . $eintrag['count'] . '" />';
						$cssClass = 'anz right';
						break;
					case 'hersteller_bezeichnung':
						$wert = $model->gibFeldwert($id,'hersteller_bezeichnung');
						if ($feldBezeichnung=='Ebene') {
							$cssClass = 'ebene right';
						} else {
							$cssClass = $feld;
						}
						break;
					default:
						$wert = $model->gibFeldwert($id,$feld);
						$cssClass = $feld;
					}
					$out .= '<td ' . $id . ' class="' . $cssClass . '">' . $wert . '</td>';
				}
				$out .= '<td><span class="remove_elem" title="Aus dem Warenkorb entfernen" id="icon_' . $id . '"></span></td>';
				$out .= '</tr>';
			}
			$summe = sprintf("%01.2f &euro;", $summe);
			$anzSpalten = count($feldListe);
			$out .= '<tr class="gesamtSumme">
							 <td class="right no-border-right" colspan="'. $anzSpalten . '">Gesamtsumme: ' . $summe . '</td>
							 <td class="no-border-left"></td>
							</tr>';
			$out .= '</tbody>';
			$out .= '</table>';
			$out .= '<span class="empty_cart" title="Warenkorb leeren"></span>';
			
			$out .= '<script type="text/javascript">
					$(".empty_cart").click(function(e) {
						var url = "' . $this->eidUrl . '&methode=emptyCart&shop_id=' . $shopId . '&page_id=' . $pageId . '";
						executeAjax(url,true);
					});
					$(".remove_elem").click(function(e) {
						var id = this.id;
						var trenner = id.indexOf("_");
						var elemId = id.substring(trenner+1);
						$("#count_" + elemId).val("");
						var url = "' . $this->eidUrl . '&methode=removeFromCart&article_id=" + elemId + "&shop_id=' . $shopId . '&page_id=' . $pageId . '";
						executeAjax(url,true);
					});
					$(".edit_anzahl").change(function(e) {
						var id = this.id;
						var trenner = id.indexOf("_");
						var elemId = id.substring(trenner+1);
						var count = $("#count_" + elemId).val();
						if (isNaN(count) || count==0) {
							$("#count_" + elemId).html("");
						} else {
							var price = encodeURI($("#count_" + elemId).attr("name"));
							var url = "' . $this->eidUrl . '&methode=addToCart&article_id=" + elemId + "&count=" + count + "&price=" + price + "&shop_id=' . $shopId . '&page_id=' . $pageId . '";
							return executeAjax(url,true);
						}
					});
				</script>
			';
		}
		return $out;
	}
	
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_article_cart.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_article_cart.php']);
}

?>
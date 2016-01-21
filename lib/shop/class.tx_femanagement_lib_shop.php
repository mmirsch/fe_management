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

class tx_femanagement_lib_shop  {
	
	static public function clearCart($pageId,$shopId) {
		$cart = array();
		tx_femanagement_lib_util::storeSessionData($pageId,$cart,$shopId . ',cart');
	}
	
	static public function addToCart(&$data) {
		$shopId = $data['shop_id'];
		$pageId = $data['page_id'];
		$warenkorb = tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
		$price = str_replace(',', '.', $data['price']); 
		$summe = $data['count']*$price;
		$artikel = array(
										 'count'=>$data['count'],
										 'price'=>$price,
										 'summe'=>$summe,
								);
		$warenkorb[$data['article_id']] = $artikel;
		tx_femanagement_lib_util::storeSessionData($pageId,$warenkorb,$shopId . ',cart');
		print count($warenkorb);
	}
	
	static public function removeFromCart(&$data) {
		$shopId = $data['shop_id'];
		$pageId = $data['page_id'];
		$warenkorb = tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
		if (!empty($warenkorb[$data['article_id']])) {
			unset($warenkorb[$data['article_id']]);
		}
		tx_femanagement_lib_util::storeSessionData($pageId,$warenkorb,$shopId . ',cart');
		print count($warenkorb);
	}
	
	static public function getCartCount($pageId,$shopId) {
		$warenkorb = tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
		return count($warenkorb);
	}
	
	static public function gibWarenkorb($pageId) {
  	$pid = tx_femanagement_lib_util::getPageConfig('pid');
  	$shopId = tx_femanagement_lib_util::getPageConfig('config.,shopId');
		$cartFields = tx_femanagement_lib_util::getPageConfig('config.,cartFields.');
 		$feldListe = tx_femanagement_lib_util::getFieldList($cartFields);
  	$warenkorb = tx_femanagement_lib_util::getSessionData($pageId,$shopId . ',cart');
  	$model = t3lib_div::makeInstance('tx_femanagement_model_shop_article',NULL,$pid);
  	if (empty($warenkorb))	{
  		$out = '<h3>Keine Artikel im Warenkorb</h3>';
  	} else {
  		$out = '';
  		$out .= '<table cellpadding="3" cellspacing="0" border="1" bgcolor="#e5eef1" bordercolor="#004666">';
  		$out .= '<thead><tr bgcolor="#e5eef1" color="#fff">';
  		foreach ($feldListe as $feld=>$feldBezeichnung) {
  			$out .= '<th><strong>' . $feldBezeichnung . '</strong></th>';
  		}
  		$out .= '</tr></thead>';
  		$out .= '<tbody>';
  		$summe = 0;
  		foreach ($warenkorb as $id=>$eintrag) {
  			$out .= '<tr>';
  			foreach ($feldListe as $feld=>$feldBezeichnung) {
  				$cssClass = '';
  				switch ($feld) {
  					case 'preis':
  						$wert = sprintf("%01.2f &euro;", $eintrag['price']);
  						$align = ' align="right" ';
  						break;
  					case 'summe':
  						$summe += $eintrag['summe'];
  						$wert = sprintf("%01.2f &euro;", $eintrag['summe']);
  						$align = ' align="right" ';
  						break;
  					case 'anzahl':
  						$wert = $eintrag['count'];
  						$align = ' align="right" ';
  						break;
  					case 'hersteller_bezeichnung':
							$wert = $model->gibFeldwert($id,'hersteller_bezeichnung');
							if ($feldBezeichnung=='Ebene') {
								$align = ' align="right" ';
							} else {
								$align = '';
							}
  						default:
  						$wert = $model->gibFeldwert($id,$feld);
  						$align = '';
  				}
  				$out .= '<td' . $align . '>' . $wert . '</td>';
  			}
  			$out .= '</tr>';
  		}
  		$summe = sprintf("%01.2f &euro;", $summe);
  		$anzSpalten = count($feldListe);
  		$out .= '<tr>
  		<td align="right" colspan="'. $anzSpalten . '"><strong>Gesamtsumme: ' . $summe . '</strong></td>
  		</tr>';
  		$out .= '</tbody>';
  		$out .= '</table>';
  	}
  	return $out;
  }
  
}


?>
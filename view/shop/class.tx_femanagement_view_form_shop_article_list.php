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
class tx_femanagement_view_form_shop_article_list extends tx_femanagement_view_form_list {
var $shopId;
var $shoppingCart;
var $cartPageId;
var $herstellerPageId;
var $lieferantenPageId;

	
	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function createModel($pid='',$pibase='') {
		return t3lib_div::makeInstance('tx_femanagement_model_shop_article',$this->piBase,$pid);
	}
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$imgWidth = 20;
		$out = '<tr class="' . $rowClass . '">';
//t3lib_div::debug($feUserdata,'feuserdata');
//t3lib_div::debug($elem,'$elem');
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'produktname':
					$out2 .= '<td class="title">' .
									$elem[$key] . 
									'</td>';
					$out .= '<td class="title">';
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
						$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['produktname'],'textLink',$title,'_blank',TRUE,$this->singlePageConfig['pageId']);
					} else {
						$out .= $title;
					}
						unset($permissions[$viewIndex]);
					$out .= '</td>';
					break;
				case 'hauptkategorie':
				case 'hersteller':
				case 'hersteller_bezeichnung':
					$out .= '<td class="' . $key . '">' .
									$elem[$key] . 
									'</td>';
					break;
				case 'artikelnummer':
					$out .= '<td class="' . $key . '">' .
									$elem[$key] . 
									'</td>';
					break;
				case 'preis':
					$out .= '<td class="' . $key . '">' .
									$elem[$key] . ' &euro;' .
									'</td>';
					break;
				case 'bild':
					$out .= '<td class="img">';
					if (!empty($elem[$key])) {
						$pfad = 'uploads/tx_hebest/' . $elem[$key];
						$bildadresse = tx_femanagement_lib_util::createJpgImage($pfad, 45);
						$out .= '<img width="' . $imgWidth . 'px" src="' . $bildadresse . '" />';
					}
					$out .= '</td>';
					break;
			}
		}
		$out .= '<td class="actions">';
		$out .= $this->erzeugeWarenkorbEintrag($elem['uid'],$elem['preis'],'cart');
		$out .= '</td>';
		$out .= '</tr>';		
		return $out;
	}
	
	function showTemplateListItem(&$templateCode,$elem,$fieldList,$permissions,$rowClass='') {
		$out .= '<div class="row_elem ' . $rowClass . '">';
		$rowContent = $templateCode;
		
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
				case 'produktname':
					$title = $this->getTitleCreateLinkSingleView();		
					$viewIndex = array_search('view',$permissions);
					if ($viewIndex!==FALSE) {
						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['produktname'],'textLink',$title,'_blank',TRUE,$this->singlePageConfig['pageId']);
					} else {
						$elemVal = $title;
					}
					unset($permissions[$viewIndex]);
					break;
				case 'produktname_nolink':
					$elemVal = $elem['produktname'];
					break;
				case 'hauptkategorie':
				case 'unterkategorie':
				case 'eigenschaft1':
				case 'eigenschaft2':
				case 'hersteller_bezeichnung':
					$elemVal = $elem[$key];
					break;
				case 'hersteller':
					$elemVal = $this->createLinkText($elem['hersteller_uid'],'&tx_femanagement[mode]=view',$elem['hersteller'],'fancyBoxLink',$title,'_blank',TRUE,$this->herstellerPageId);
					break;
				case 'lieferanten':
					$elemVal = $this->createLinkText($elem['lieferanten_uid'],'&tx_femanagement[mode]=view',$elem['lieferanten'],'fancyBoxLink',$title,'_blank',TRUE,$this->lieferantenPageId);
					break;
				case 'bemerkung':
					$elemVal = $elem[$key];
					break;
				case 'artikelnummer':
					$elemVal = $elem[$key];
					break;
				case 'preis':
					$elemVal = $elem[$key] . ' &euro;';
					break;
				case 'bild':
					if (!empty($elem[$key])) {
						$pfad = 'uploads/tx_hebest/' . $elem[$key];
						$bildadresse = tx_femanagement_lib_util::createJpgImage($pfad, 45);
						$img = '<img src="' . $bildadresse . '" />';
						$elemVal = $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$img,'textLink',$elem['produktname'],'_blank',TRUE,$this->singlePageConfig['pageId']);
					}
					break;
				case 'bild_nolink':
					if (!empty($elem['bild'])) {
						$elemVal = '<img src="/uploads/tx_hebest/' . $elem['bild'] . '" />';
					}
					break;
				case 'cart':
					$elemVal = $this->erzeugeWarenkorbEintrag($elem['uid'],$elem['preis'],'cart');
					break;
			}
			$rowContent = str_replace('###' . strtoupper($key) . '###',$elemVal,$rowContent);
		}
		$out .= $rowContent;
		$out .= '</div>';
		return $out;
	}
	
	function erzeugeWarenkorbEintrag($uid,$preis,$cssClass) {
		if (array_key_exists($uid,$this->shoppingCart)) {
			$count = $this->shoppingCart[$uid]['count'];
			$currentClass = 'remove';
			$title = 'Aus dem Warenkorb entfernen';
		} else {
			$count = '';
			$currentClass = 'add';
			$title = 'Zum Warenkorb hinzufügen';
		}
		return '<span class="' . $cssClass . '">' . 
						'<input type="hidden" value="' . $preis . '" id="price_' . $uid . '" />' .
						'<input class="count" type="text" size="5" value="' . $count . '" id="count_' . $uid . '" />' .
						'<span class="icon ' . $currentClass . '" title="' . $title . '" id="icon_' . $uid . '"></span>' .
						'</span>';
	}
	
	function createDataListJs($shopId='') {
		return $this->erzeugeWarenkorbEintragJquery('cart',$shopId);
	}
	
	function showColTitleActions() {
		return 'Menge';
	}
	
	function erzeugeWarenkorbEintragJquery($cssClass,$shopId='') {
		if (!empty($shopId)) {
			$this->shopId = $shopId;
		}
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName;
		return '<script type="text/javascript">
		var blur = 0; 
		function addToCart(elemId) {
			var count = $("#count_" + elemId).val();
			if (isNaN(count) || count==0) {
				$("#count_" + elemId).val("");
			} else {
				var price = encodeURI($("#price_" + elemId).val());
				var url = "' . $eidUrl . '&methode=addToCart&article_id=" + elemId + "&count=" + count + "&price=" + price + "&shop_id=' . $this->shopId . '&page_id=' . $this->pageId . '";
				var res = executeAjax(url,false);
				return res;
			}
		}
		
		function updateArtikelZahl(anzArtikel) {
			$("#shopping_cart .cart_info").html(anzArtikel);
		}
		
		function removeFromCart(elemId) {
			$("#count_" + elemId).val("");
			var url = "' . $eidUrl . '&methode=removeFromCart&article_id=" + elemId + "&shop_id=' . $this->shopId . '&page_id=' . $this->pageId . '";
			var res = executeAjax(url,false);
			return res;
		}
		
		$("#data_container").delegate(".' . $cssClass . ' .icon","click", function(){
			if (blur==0) {
				var id = this.id;
				var trenner = id.indexOf("_");
				var elemId = id.substring(trenner+1);
				processingAnimation("start","bitte warten");
				if ($("#icon_" + elemId).hasClass("remove")) {
					var anzArtikel = removeFromCart(elemId);
					$(this).addClass("add");
					$(this).removeClass("remove");
					$(this).attr("title","Zum Warenkorb hinzufügen");
					updateArtikelZahl(anzArtikel);
				} else {
					var count = $("#count_" + elemId).val();
					if (count=="" || count>0) {
						if (count=="") {
							$("#count_" + elemId).val("1");
						} 
						var anzArtikel = addToCart(elemId);
						blur = elemId;
						if ( $("#icon_" + elemId).hasClass("add")) {
							$("#icon_" + elemId).addClass("remove");
							$("#icon_" + elemId).removeClass("add");
							$("#icon_" + elemId).attr("title","Aus dem Warenkorb entfernen");
							updateArtikelZahl(anzArtikel);
						}
					} else {
						$("#count_" + elemId).val("");
					}
				}
				processingAnimation("stop");
			}
			blur = 0;
		});
		
		$("#data_container").delegate(".' . $cssClass . ' .count","click", function(){
		processingAnimation("start","bitte warten");
			var id = this.id;
			var trenner = id.indexOf("_");
			var elemId = id.substring(trenner+1);
			var count = $(this).val();
			var anzArtikel;
			if (isNaN(count) || count==0) {
				$("#count_" + elemId).val("");
				if ( $("#icon_" + elemId).hasClass("remove")) {
					anzArtikel = removeFromCart(elemId);
					blur = elemId;
					$("#icon_" + elemId).addClass("add");
					$("#icon_" + elemId).removeClass("remove");
					$("#icon_" + elemId).attr("title","Zum Warenkorb hinzufügen");
				}
			} else {
				anzArtikel = addToCart(elemId);
				blur = elemId;
				if ( $("#icon_" + elemId).hasClass("add")) {
					$("#icon_" + elemId).addClass("remove");
					$("#icon_" + elemId).removeClass("add");
					$("#icon_" + elemId).attr("title","Aus dem Warenkorb entfernen");
				}
			}
			updateArtikelZahl(anzArtikel);
			processingAnimation("stop");
		});
		</script>
	';
	}
	
	function getDataExportFieldTitle($field) {
			switch ($field) {
			case 'title':
				return 'Titel';
			case 'short':
				return 'Untertitel';
			case 'bodytext':
				return 'Text';
			case 'datetime':
				return 'Datum';
			case 'starttime':
				return 'Startdatum';
			case 'endtime':
				return 'Stopdatum';
			case 'archivedate':
				return 'Archivdatum';
		}
		return '';
	}	
	
	function formatExportItem($field,$value) {
		switch ($field) {
			case 'datetime':
			case 'starttime':
			case 'archivedate':
				if (!empty($value)) {
					$out = date('d.m.Y', $value);
				} else {
					$out = ' ';
				}
				break;
			case 'cruser_id':
			case 'fe_cruser_id':
			case 'email':
				$out = '';
				break;
				
			default:
				if (empty($value)) {
					$out = ' ';
				} else {
					$out = $value;
				}
				break;
		}
		return $out;
	}
		
	function createPreviewLinkUrl($uid) {
		$configArray['show_hidden'] = 1;
		$configArray['all_pids'] = 1;
		$linkUrl = $this->controller->createPreviewLink($uid,$this->previewPid);
		return $linkUrl;
	}
		
	function createPreview($uid,$text,$linkClass,$title,$target='_blank') {
		$linkUrl = $this->createPreviewLinkUrl($uid);
		$link = '<a class="' . $linkClass . '" target="' . $target . '" class="' . $linkClass . 
					  '" title="' . $title . '" href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}

	function getNewelemButtonTitle() {
		return 'Neuen Artikel anlegen'; 
	}
	
	function gibHauptkategorien() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_hauptkategorie',$this->piBase,$this->pid);
		return $model->getList('',$this->pid);
	}
	
	function gibUnterkategorien() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_unterkategorie',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function gibHersteller() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_hersteller',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function gibLieferanten() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_lieferanten',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function gibEigenschaft1() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_eigenschaft1',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function gibEigenschaft2() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_eigenschaft2',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function addToCart(&$data) {
		tx_femanagement_lib_shop::addToCart($data);
	}
	
	function removeFromCart(&$data) {
		tx_femanagement_lib_shop::removeFromCart($data);
	}
	
	function emptyCart(&$data) {
		tx_femanagement_lib_shop::clearCart($data['page_id'],$data['shop_id']);
	}
	
	function showShoppingCartInfo() {
		$showCartInfo = $this->controller->displayShoppingCart();
		$out = '';
		if ($showCartInfo) {
			$seiteWarenkorb = 'index.php?id=' . $this->cartPageId;
			$anzahl = tx_femanagement_lib_shop::getCartCount($this->pageId,$this->shopId);
			$out .= '<div id="shopping_cart">';
			$out .= '<a class="cart_page" href="' . $seiteWarenkorb . '" title="Bestellung absenden"></a>';
			$out .= '<span class="cart_info">' .  $anzahl . '</span>';
			$out .= '</div>';
		}
		return $out;
	}
		
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$this->shopId = $data['args']['shop_id'];
		$this->pageId = $data['args']['page_id'];
		$currentSessionData = tx_femanagement_lib_util::getSessionData($this->pageId);
		if (!empty($currentSessionData[$this->shopId]['cart']))	{
			$this->shoppingCart = $currentSessionData[$this->shopId]['cart'];
		} else {
			$this->shoppingCart = array();
		}
		$this->cartPageId = $currentSessionData['config']['cartPageId'];
		$configArray['where'] = ' WHERE tx_hebest_artikel.pid=' . $this->pid;
		$anzeigeFelder = $this->controller->getListViewFields();
		
		$this->herstellerPageId =  $this->controller->getLinkPageId('hersteller');
		$this->lieferantenPageId =  $this->controller->getLinkPageId('lieferanten');
		$dbFelder = array_keys($anzeigeFelder);
		$suchFelder = $this->controller->getSearchFields();
		$suchFelderListe = explode(',',$suchFelder);
		$dbFelderSearch = array();
		foreach ($suchFelderListe as $feld) {
			$feld = trim($feld);
			if (!in_array($feld,$dbFelderSearch)) {
				$dbFelderSearch[] = $feld;
			}
		}
		$configArray['joins'] = array();
		foreach ($dbFelderSearch as $feld) {
			switch ($feld) {
				case 'hauptkategorie':
					$configArray['joins'][] = array('table'=>'tx_hebest_hauptkategorie',
																			'fields'=>'title as hauptkategorie',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'hauptkategorie',
																			'mode'=>'LEFT JOIN',
																			);
					break;
				case 'unterkategorie':
					$configArray['joins'][] = array('table'=>'tx_hebest_unterkategorie',
																			'fields'=>'title as unterkategorie',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'unterkategorie',
																			'mode'=>'LEFT JOIN',
																			);
					break;
				case 'hersteller':
					$configArray['joins'][] = array('table'=>'tx_hebest_hersteller',
																			'fields'=>'title as hersteller,uid as hersteller_uid',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'hersteller',
																			'mode'=>'LEFT JOIN',
																		);
					break;
				case 'lieferanten':
					$configArray['joins'][] = array('table'=>'tx_hebest_lieferanten',
																	'fields'=>'title as lieferanten,uid as lieferanten_uid',
																	'joinFieldLocal'=>'uid',
																	'joinFieldMain'=>'lieferant',
																	'mode'=>'LEFT JOIN',
																	);
						break;
				case 'eigenschaft1':
					$configArray['joins'][] = array('table'=>'tx_hebest_eigenschaft1',
																			'fields'=>'title as eigenschaft1',
																			'joinFieldLocal'=>'uid',
																			'joinFieldMain'=>'eigenschaft1',
																			'mode'=>'LEFT JOIN',
																		);
					break;
				case 'eigenschaft2':
					$configArray['joins'][] = array('table'=>'tx_hebest_eigenschaft2',
					'fields'=>'title as eigenschaft2',
					'joinFieldLocal'=>'uid',
					'joinFieldMain'=>'eigenschaft2',
					'mode'=>'LEFT JOIN',
					);
					break;
					
			}
		}
		if (isset($this->args['volltextsuche'])) {
			if (!empty($suchFelderListe)) {
				$configArray['where'] .= ' AND (FALSE';
				foreach($suchFelderListe as $feld) {
					$feld = trim($feld);
					switch ($feld) {
						case 'bemerkung':
						case 'produktname':
						case 'hersteller_bezeichnung':
							$configArray['where'] .= ' OR tx_hebest_artikel.' . $feld . ' LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
						case 'hersteller':
							$configArray['where'] .= ' OR tx_hebest_hersteller.title LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
						case 'lieferanten':
							$configArray['where'] .= ' OR tx_hebest_lieferanten.title LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
						case 'hauptkategorie':
							$configArray['where'] .= ' OR tx_hebest_hauptkategorie.title LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
						case 'unterkategorie':
							$configArray['where'] .= ' OR tx_hebest_unterkategorie.title LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
					}
				}
				$configArray['where'] .= ') ';
			} else {
				$configArray['where'] .= ' AND (tx_hebest_artikel.produktname LIKE "%' . $this->args['volltextsuche'] . '%")';
			}
		}
		if (isset($this->args['hauptkategorie'])) {
			if ($this->args['hauptkategorie']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.hauptkategorie = (' . $this->args['hauptkategorie'] . '))';
			}
		}
		if (isset($this->args['unterkategorie'])) {
			if ($this->args['unterkategorie']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.unterkategorie = (' . $this->args['unterkategorie'] . '))';
			}
		}
		if (isset($this->args['hersteller'])) {
			if ($this->args['hersteller']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.hersteller = (' . $this->args['hersteller'] . '))';
			}
		}
		
		if (isset($this->args['lieferanten'])) {
			if ($this->args['lieferanten']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.lieferant = (' . $this->args['lieferanten'] . '))';
			}
		}
		
		if (isset($this->args['eigenschaft1'])) {
			if ($this->args['eigenschaft1']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.eigenschaft1 = (' . $this->args['eigenschaft1'] . '))';
			}
		}
		
		if (isset($this->args['eigenschaft2'])) {
			if ($this->args['eigenschaft2']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_artikel.eigenschaft2 = (' . $this->args['eigenschaft2'] . '))';
			}
		}
		
		if (isset($this->args['hidden'])) {
			if ( $this->args['hidden']!='all') {
				$configArray['where'] .= ' AND tx_hebest_artikel.hidden=' . $this->args['hidden'];
			}
		}
		if (isset($this->args['deleted'])) {
			if ($this->args['deleted']!='all') {
				$configArray['where'] .= ' AND tx_hebest_artikel.deleted=' . $this->args['deleted'];
			}
		}
		if (isset($this->args['az']) AND $this->args['az']!='all') {
			$whereAz = ' AND (tx_hebest_artikel.produktname LIKE "' . $this->args['az'] . '%")';
		} else {
			$whereAz = '';
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
				case 'hauptkategorie':
					$sortField = 'tx_hebest_hauptkategorie.title';
					break;
				case 'preis':
					$sortField = 'ABS(tx_hebest_artikel.preis)';
					break;
				default:
					$sortField = 'tx_hebest_artikel.' . $this->args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'tx_hebest_artikel.produktname ASC';
		}
		if (!empty($this->args['export'])) {
			$configArray['where'] .= $whereAz;
			$configArray['fields'] = 'produktname,artikelnummer';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'shopartikel');
			exit();
		} else {
/*
			$fieldList = $dbFelder;
			$fieldList = explode(',',$suchFelder);
*/			
			$configArray['fields'] = 'uid,produktname,artikelnummer,bemerkung,hersteller_bezeichnung,bild,preis,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
			
			if ($this->az>=0) {
				$daten = $this->model->selectSqlData($sqlQuery);
				$out .= $this->createAzList($daten,'produktname');
			}
			if (!empty($whereAz)) {
				$configArray['where'] .= $whereAz;
				$sqlQuery = $this->model->buildJoinQuery($configArray);
			}
			$out .= $this->showShoppingCartInfo();
			$out .= $this->exitSqlAjaxFilter($sqlQuery,$limit);
		
			if (!empty($limit)) {
				$sqlQuery .= $limit;
			}

			$daten = $this->model->selectSqlData($sqlQuery);
			
			if (isset($data['urlArgs']['page'])) {
				$page = $data['urlArgs']['page'];
			} else {
				$page = '';
			}
			foreach ($daten as $index=>$elem) {
				$daten[$index]['permissions'] = $this->getPermissions($elem,$page);
			}
			if (count($daten)<1) {
				$out .= $this->emptyDataList();
				
			} else {
				$out .= $this->createDataList($daten);
				$out .= $this->createScrollTopLink();
				if ($this->singlePageConfig['mode']=='fancybox') {
					$out .= '<script type="text/javascript">
					$(".fancyBoxLink").fancybox({
					maxWidth	: 400,
					maxHeight	: 600,
					fitToView	: false,
					width		: "400px",
					height		: "300px",
					autoSize	: false,
					closeClick	: true,
					openEffect	: "none",
					closeEffect	: "none"
				});
				</script>';
				}
			}
			return $out;
		}
	}

	function emptyDataList() {
		return '<div class="info"><h3>Für Ihre Eingabe wurden keine Artikel gefunden!</h3>
						Löschen Sie ggf. Ihre Filtereinstellungen (Sucheingabe, A-Z Kategorien). 
						<br / > Oder <input type="button" onClick="window.location.reload(true)" value="laden Sie die Seite neu" />.
						</div>' ;
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
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_article_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_article_list.php']);
}

?>
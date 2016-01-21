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
class tx_femanagement_view_form_shop_hersteller_list  extends tx_femanagement_view_form_list {

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}		
		
	function showTemplateListItem(&$templateCode,$elem,$fieldList,$permissions,$rowClass='') {
		$out .= '<div class="row_elem ' . $rowClass . '">';
		$rowContent = $templateCode;
		
		foreach ($fieldList as $key=>$field) {
			$elemVal = '';
			switch ($key) {
				case 'title':
					$elemVal = $elem['title'];
					break;
				case 'adresse':
					if (!empty($elem['anschrift']) || !empty($elem['plz']) || !empty($elem['stadt']) ) {
						$elemVal = '<div class="adresse">';
						$elemVal .= $elem['title'] . '</br>';
						if (!empty($elem['anschrift']) ) {
							$elemVal .= $elem['anschrift'] . '</br>';
						}
						if (!empty($elem['plz']) ) {
							$elemVal .= $elem['plz'] . ' ';
						}
						if (!empty($elem['stadt']) ) {
							$elemVal .= $elem['stadt'];
						}
						$elemVal .= '</div>';
					}
					break;
				case 'bemerkung':
					$elemVal = '<div class="adresse">' . $elem[$key] . '</div>';
					break;
				case 'tel':
					if (!empty($elem[$key])) {
						$elemVal = '<div class="tel"><strong>Tel.:</strong> ' . $elem[$key] . '</div>';
					}
					break;
				case 'fax':
					if (!empty($elem[$key])) {
						$elemVal = '<div class="tel"><strong>Fax.:</strong> ' . $elem[$key] . '</div>';
					}
					break;
				case 'www':
					if (!empty($elem[$key])) {
						$elemVal = '<div class="www"><strong>www:</strong> <a href="' . $elem[$key] . '">' . $elem[$key] . '</a></div>';
					}
					break;
				case 'email':
					if (!empty($elem[$key])) {
						$emailListe = explode('+',$elem[$key]);
						if (count($emailListe)==1) {
							$elemVal = '<div class="email"><strong>E-Mail:</strong> <a href="mailto:' . $elem[$key] . '">' . $elem[$key] . '</a></div>';
						} else {
							$elemVal = '<div class="email"><strong>E-Mail:</strong> ';
							$emails = array();
							foreach ($emailListe as $email) {
								$emails[] = '<a href="mailto:' . trim($email) . '">' . trim($email) . '</a>';								
							}
							$elemVal .= implode(', ',$emails);
							$elemVal .= '</div>';
						}
					}
					break;
				case 'bild':
					if (!empty($elem[$key])) {
						$img = '<img src="/uploads/tx_hebest/' . $elem[$key] . '" />';
						$elemVal = '<div class="image_90">' . $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$img,'textLink',$title,'_blank',TRUE,$this->singlePageConfig['pageId']) . '</div>';
					}
					break;
			}
			$rowContent = str_replace('###' . strtoupper($key) . '###',$elemVal,$rowContent);
		}
		$out .= $rowContent;
		$out .= '</div>';
		return $out;
	}

	function emptyDataList() {
		return '<div class="info"><h3>Für Ihre Eingabe wurden keine Hersteller gefunden!</h3>
						Löschen Sie ggf. Ihre Filtereinstellungen (Sucheingabe, A-Z Kategorien). 
						<br / > Oder <input type="button" onClick="window.location.reload(true)" value="laden Sie die Seite neu" />.
						</div>' ;
	}

	function gibKeywords1() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_keyword1',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function gibKeywords2() {
		$model = t3lib_div::makeInstance('tx_femanagement_model_shop_keyword2',$this->piBase,$this->pid);
		$liste = $model->getList('',$this->pid);
		return $liste;
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$this->shopId = $data['args']['shop_id'];
		$this->pageId = $data['args']['page_id'];
		$currentSessionData = tx_femanagement_lib_util::getSessionData($this->pageId);
		$configArray['where'] = ' WHERE tx_hebest_hersteller.pid=' . $this->pid;
		$anzeigeFelder = $this->controller->getListViewFields();

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
				case 'stadt':
					$configArray['joins'][] = array('table'=>'tx_hebest_stadt',
					'fields'=>'title as stadt',
					'joinFieldLocal'=>'uid',
					'joinFieldMain'=>'stadt',
					'mode'=>'LEFT JOIN',
					);
					break;	
				case 'keyword1':
					$configArray['joins'][] = array('table'=>'tx_hebest_keyword1',
					'fields'=>'title as keyword1',
					'joinFieldLocal'=>'uid',
					'joinFieldMain'=>'keyword1',
					'mode'=>'LEFT JOIN',
					);
					break;	
				case 'keyword2':
					$configArray['joins'][] = array('table'=>'tx_hebest_keyword2',
					'fields'=>'title as keyword2',
					'joinFieldLocal'=>'uid',
					'joinFieldMain'=>'keyword2',
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
						case 'beschreibung':
						case 'title':
							$configArray['where'] .= ' OR tx_hebest_hersteller.' . $feld . ' LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
						case 'stadt':
							$configArray['where'] .= ' OR tx_hebest_stadt.title LIKE "%' . $this->args['volltextsuche'] . '%"';
							break;
					}
				}
				$configArray['where'] .= ') ';
			} else {
				$configArray['where'] .= ' AND (tx_hebest_hersteller.title LIKE "%' . $this->args['volltextsuche'] . '%")';
			}
		}
			if (isset($this->args['stadt'])) {
			if ($this->args['stadt']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_hersteller.stadt = (' . $this->args['stadt'] . '))';
			}
		}
	
		if (isset($this->args['keyword1'])) {
			if ($this->args['keyword1']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_hersteller.keyword1 = (' . $this->args['keyword1'] . '))';
			}
		}
	
		if (isset($this->args['keyword2'])) {
			if ($this->args['keyword2']!='all') {
				$configArray['where'] .= ' AND (tx_hebest_hersteller.keyword2 = (' . $this->args['keyword2'] . '))';
			}
		}
	
		if (isset($this->args['hidden'])) {
			if ( $this->args['hidden']!='all') {
				$configArray['where'] .= ' AND tx_hebest_hersteller.hidden=' . $this->args['hidden'];
			}
		}
		if (isset($this->args['deleted'])) {
			if ($this->args['deleted']!='all') {
				$configArray['where'] .= ' AND tx_hebest_hersteller.deleted=' . $this->args['deleted'];
			}
		}
		if (isset($this->args['az']) AND $this->args['az']!='all') {
			$whereAz = ' AND (tx_hebest_hersteller.title LIKE "' . $this->args['az'] . '%")';
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
					$sortField = 'tx_hebest_stadt.title';
					break;
				default:
					$sortField = 'tx_hebest_hersteller.' . $this->args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'tx_hebest_hersteller.title ASC';
		}
		if (!empty($this->args['export'])) {
			$configArray['where'] .= $whereAz;
			$configArray['fields'] = 'title,bemerkung';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$daten = $this->model->selectSqlData($sqlQuery);
			$this->createDataExport($daten,$this->args['export'],'hersteller');
			exit();
		} else {
			$configArray['fields'] = 'uid,title,anschrift,bemerkung,plz,stadt,tel,fax,www,email,keyword1,keyword2,deleted,hidden';
			$sqlQuery = $this->model->buildJoinQuery($configArray);
			$out = '';
			if ($this->az>=0) {
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
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_hersteller_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/shop/class.tx_femanagement_view_form_shop_hersteller_list.php']);
}

?>
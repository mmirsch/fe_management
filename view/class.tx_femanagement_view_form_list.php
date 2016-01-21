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
 */

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */

class tx_femanagement_view_form_list  extends tx_femanagement_view_form {
protected $listItemWrap = '<div class="item">|</div>';

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}

	function setEidUrl($eidUrl)	{
		$this->eidUrl = $eidUrl;
	}
	
	function showListView(&$buttonList,&$filterList,$aktuelleSeite='',$config='') {
		if (!empty($config)) {
			if (is_array($config)) {
				foreach($config as $key=>$val) {
					$this->$key = $val;
				}
			}
		}
		$out = $this->showMenu($aktuelleSeite); 
		$out .= $this->initElemCodeList($buttonList,$filterList);
		$out .= $this->exitElemCodeList($filterList,'data_container');
		return $out;
	}
	
	function initElemCodeList(&$buttonList,&$filterList)	{
		if (!empty($this->wrapClass)) {
			$classCode = ' class="' . $this->wrapClass . '"';
		} else {
			$classCode = '';
		}
		$out = '<div' . $classCode . '>';
		$out .= $this->initButtons($buttonList);
		$out .= $this->initFilter($filterList);
		return $out;
	}
	
	function exitElemCodeList(&$filterList,$divId,$autoLoad=TRUE,$limit=25)	{
		$out = '<div id="' . $divId . '">
						</div>';
		$pageId = $GLOBALS['TSFE']->id;
		$ajaxReplaceContentUrl = $this->eidUrl . '&methode=ajaxFilter&id=' . $pageId . '&pid=' . $this->pid;
		if (strpos($ajaxReplaceContentUrl,'&ctrl')===FALSE) {
			$ajaxReplaceContentUrl .= '&ctrl=' . $this->controllerName;
		}
		if (strpos($ajaxReplaceContentUrl,'&model')===FALSE) {
			$ajaxReplaceContentUrl .= '&model=' . $this->modelName;
		}
		if (!empty($this->page)) {
			$ajaxReplaceContentUrl .= '&tx_femanagement[page]=' . $this->page;
		}
		$ajaxSessionUrl = $this->eidUrl . '&methode=session_data&ctrl=' . $this->controllerName;
		$out .= tx_femanagement_view_filter::createFilterJqueryAjaxRequest($filterList,$ajaxReplaceContentUrl,$ajaxSessionUrl,$divId,$autoLoad,$limit);
		$out .= $this->createDataListJs();
		$out .= '</div>';
		return $out;
	}

	function initAjaxFilter(&$data) {
		$this->args = $data['args'];
		$this->uid = $data['uid'];
		$this->pid = $data['pid'];
		$this->page = $data['page'];
		$this->az = $data['az'];
		$this->previewPid = $data['previewPid'];
		$this->setPageId($data['id']);
		$this->setPage($this->page);
		$this->model = t3lib_div::makeInstance($data['model'],'',$this->pid);
		$this->controller = t3lib_div::makeInstance($data['ctrl']);
		$this->setModelName($data['model']);
		$this->setControllerName($data['ctrl']);
		$this->singlePageConfig = $this->controller->getSinglePageConfig();
		if (isset($data['tx_femanagement'])) {
			$this->urlArgs = $data['tx_femanagement'];
		}
	}

	function exitAjaxFilter(&$configArray) {
		$this->limit = '0';
		$this->page = 0;
		if (isset($this->args['limit']) || isset($this->args['num_entries'])) {
			$this->anzahl = $this->model->getCount($configArray);
			if (isset($this->args['num_entries'])) {
				if ($this->args['num_entries']!='all') {
					$this->limit = $this->args['num_entries'];
				}
			} else {
				$this->limit = $this->args['limit'];
			}
			if (isset($this->args['page'])  && $this->limit>0) {
				$this->page = $this->args['page'];
				/* Überlauf prüfen */
				if ($this->page*$this->limit>$this->anzahl) {
					$this->page = floor($this->anzahl/$this->limit);
				}
				$configArray['start'] = $this->page*$this->limit;
			}
		}
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$configArray['orderBy'] = $this->args['sortField'] . ' ' . 
																	$this->args['sortMode'];
			} else {
				$configArray['orderBy'] = $this->args['sortField'] . ' ' . 
																	'ASC';
			}
		}
		if ($this->limit>0) {
			$configArray['limit'] = $this->limit;
		}
		$out = '';
		if ($this->limit>0 && $this->anzahl>$this->limit) {
			$out .= $this->createPageBrowser($this->anzahl,$this->limit,$this->page);
		}
		return $out;
	}
	
	function exitSqlAjaxFilter(&$select,&$limit) {
		$this->limit = '0';
		$this->page = 0;
		$limitStart = 0;
		if (isset($this->args['limit']) || isset($this->args['num_entries'])) {
			$this->anzahl = $this->model->getSqlCount($select);
			if (isset($this->args['num_entries'])) {
				if ($this->args['num_entries']!='all') {
					$this->limit = $this->args['num_entries'];
				}
			} else {
				$this->limit = $this->args['limit'];
			}
			if (isset($this->args['page'])  && $this->limit>0) {
				$this->page = $this->args['page'];
				/* Überlauf prüfen */
				if ($this->page*$this->limit>$this->anzahl) {
					$this->page = floor($this->anzahl/$this->limit);
				}
				$limitStart = $this->page*$this->limit;
			}
		}
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$configArray['orderBy'] = $this->args['sortField'] . ' ' . 
																	$this->args['sortMode'];
			} else {
				$configArray['orderBy'] = $this->args['sortField'] . ' ' . 
																	'ASC';
			}
		}
		if ($this->limit>0) {
			$limit = ' LIMIT ' . $limitStart . ',' . $this->limit;
		}
		$out = '';
		if ($this->limit>0 && $this->anzahl>$this->limit) {
			$out .= $this->createPageBrowser($this->anzahl,$this->limit,$this->page);
		}
		return $out;
	}
	
	function createAzList(&$daten,$feldname) {
		$vorhandeneBuchstaben = array();
		foreach($daten as $elem) {
			$c = strtoupper($elem[$feldname][0]);
			if (!in_array($c,$vorhandeneBuchstaben)) {
				$vorhandeneBuchstaben[] = $c;
			}
		}
		$buchstabenListe = array('A','B','C','D','E','F','G',
				'H','I','J','K','L','M','N',
				'O','P','Q','R','S','T','U',
				'V','W','X','Y','Z');
		$azList = '';
		foreach ($buchstabenListe as $anfangsBuchstabe) {
			if (in_array($anfangsBuchstabe,$vorhandeneBuchstaben)) {
				if ($this->args['az']==strtolower($anfangsBuchstabe)) {
					$cssClass = ' class="cur" ';
				} else {
					$cssClass = ' class="act" ';
				}
			} else {
				$cssClass = ' class="no" ';
			}
			$azList .= '<a id="#char_'. strtolower($anfangsBuchstabe) . '" ' . $cssClass . '>' . $anfangsBuchstabe . '</a>';
		}
		$azList .= '<a class="showAllChars">Alle anzeigen</a>';
		return '<div class="a-z-filter">' . $azList . '</div>
						<script type="text/javascript">
							$(".a-z-filter .act").click(function(e) {
								var id = this.id;
								var trenner = id.indexOf("_");
								var ch = id.substring(trenner+1);
								filterAz(ch);
							});
							$(".showAllChars").click(function(e) {
								filterAz("all");
							});
							</script>
							';
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		$configArray = array();
		$configArray['sqlFilter'] = 'TRUE';
		$configArray['fields'] = 'uid,title,deleted,hidden';
		if (isset($this->args['suche'])) {
			$configArray['sqlFilter'] .= ' AND title LIKE "%' . $this->args['suche'] . '%"';
		}
		if (isset($this->args['hidden'])) {
			$configArray['hidden'] = $this->args['hidden'];
		}
		if (isset($this->args['deleted'])) {
			$configArray['deleted'] = $this->args['deleted'];
		}
		if (isset($this->args['orderBy'])) {
			$configArray['orderBy'] = $this->args['orderBy'];
		}
		$out = $this->exitAjaxFilter($configArray);
		$daten = $this->model->selectData($configArray);
		if (isset($data['urlArgs']['page'])) {
			$page = $data['urlArgs']['page'];
		} else {
			$page = '';
		}
		foreach ($daten as $index=>$elem) {
			$daten[$index]['permissions'] = $this->getPermissions($elem,$page);
		}
		$out .= $this->createDataList($daten);
		return $out;
	}
	
	function createScrollTopLink($text='Zum Seitenanfang') {
		$self = 'index.php?id=' . $GLOBALS['TSFE']->id;
		$out = '<div><a href="' . $self . '" class="scrollTop">' . $text . '</a></div>
		<script type="text/javascript">
		$(".scrollTop").click(function(){
		$("html, body").animate({scrollTop:0}, 500);
		return false;
		});
		</script>';
		return $out;
	}
	
	function createColTitle($key,$title) {
			return '<th title="Nach ' . $title . ' sortieren" class="sortTitle" id="' . $key . '">' . 
							$title . '</th>';
	}
	
	function showColTitleActions() {
		return 'Aktionen';
	}
	
	function showColTitles($fieldList,$actions=TRUE) {
		$out = '<tr class="title">';
		foreach ($fieldList as $key=>$title) {
			$out .= $this->createColTitle($key,$title);
		}
		if ($actions) {
			$out .= '<th class="action">';
			$out .= $this->showColTitleActions();
			$out .= '</th>';
		}
		$out .= '</tr>';
		return $out;
	}
		
	function showColTitlesTemplate($fieldList,$templateCode) {
		$out = '<div class="row_title">';
		$rowContent = $templateCode;
		foreach ($fieldList as $key=>$title) {
			$rowContent = str_replace('###' . strtoupper($key) . '###',$title,$rowContent);
		}
		$out .= $rowContent;
		$out .= '</div>';
		return $out;
	}

	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			$out .= '<td class="' .  $key . '">';
			switch ($key) {
			case 'title':
				$title = $this->getTitleCreateLinkSingleView();		
				$viewIndex = array_search('view',$permissions);
				if ($viewIndex!==FALSE) {
					$out .= $this->createLinkText($elem['uid'],'&tx_femanagement[mode]=view',$elem['title'],'textLink',$title,'_blank',TRUE);
					unset($permissions[$viewIndex]);
				} else {
					$out .= $title;
				}
				break;
			}
			$out .= '</td>';
		}
		$out .= '<td class="actions">';
		$out .= $this->showActions($elem,$permissions);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
	
	function showSingleView($elem) {
		$singleViewConfig = $this->controller->getSingleViewConfig();
	}
	
	function zeileExtrahieren($startString,$endString,&$content) {
		if (!$endString) {
			$endString = '$';
		}
		$searchString = '@' . $startString . '(?:</b>)?[: ]+(.*)(?:<b>)?[ ]?' . $endString . '@siU';
		$searchString = '@' . $startString . '(.*)' . $endString . '@siU';
		preg_match($searchString,$content,$results);
		if (count($results)>=1) {
			$brPos = strrpos ($results[1] , '<br');
			if ($brPos>strlen($results[1])-8) {
				$res = substr($results[1],0,$brPos);
			} else {
				$res = $results[1];
			}
		} else {
			$res = '';
		}
		return $res;
	}
	
	function createPreviewLinkUrl($uid) {
		$configArray['show_hidden'] = 1;
		$configArray['all_pids'] = 1;
		$linkUrl = $this->controller->createPreviewLink($uid,$this->previewPid);
		return $linkUrl;
	}
		
	function createPageBrowser($anzahl,$limit,$page) {
		$pages = 0;
		$numPages = floor($anzahl/$limit);
		$out = '<div id="pagebrowser">
						<div id="pagebrowserTitle">
								Seite ' . ($page+1) . ' von ' . ($numPages+1) . '
						</div>
						<div id="pagebrowserContent">
						';
		while ($pages*$limit<$anzahl) {
			if ($pages==$page) {
				$cssClass = ' class="pagenumber active" ';
			} else {
				$cssClass = ' class="pagenumber" ';
			}
			$out .= '<input type="button" id="button_' . ($pages+1) . '" ' . 
								$cssClass .  
								'value="' . ($pages+1) . '" ' . 
								'title="Seite ' . ($pages+1) . ' anzeigen" />';
			$pages++;
		}
		$out .= '	</div>';
		$out .= '<script type="text/javascript">
							$(".pagenumber").click(function() {
								currentPage = $(this).val()-1;
								pageReload(currentPage);
							});
							</script>
							';
		$out .= '</div>';
		return $out;
	}
	
	function initButtons($buttonList) {
		$out = '';
		if (is_array($buttonList) && count($buttonList)>0) {
			$out .= '<div id="buttons_top">';	
			foreach ($buttonList as $button) {
				$out .= $button->show();
			}
			$out .= '</div>';				
		}
		return $out;
	}
	
	function initFilter(&$filterListe) {
		$out = '';
		if (is_array($filterListe) && count($filterListe)>0) {
			$out .= '<div id="filter_top">';	
			$out .= '<div class="ajaxfilter">';
			$out .= $this->showFilterListe($filterListe);		
			$out .= '</div>';				
			$out .= '</div>';				
		}
		return $out;
	}
	
	function showFilterListe(&$filterListe) {
    $out = '';
		foreach ($filterListe as $filter) {
			$out .= $filter->showFilter();
		}
		return $out;
	}
	
	function showSortierListe(&$sortLists) {
		$out = '';
		if (count($sortLists)>0) {
			$out .= '<div class="filter sort">';
			foreach ($sortLists as $sortList) {
				$out .= '<label for="sortfilter">' . $sortList['title'] . '</label>';
				$out .= '<select id="' . $sortList['name'] . '" class="ajaxsort">';
				foreach ($sortList['data'] as $sort) {
					if ($sortList['selected']==$sort['val']) {
						$selected= ' selected="selected" ';
					} else {
						$selected= '';
					}
					$out .= '<option ' . $selected . 'value="' . $sort['val'] . '">' . $sort['title'] . '</option>';
				}
				$out .= '</select>';	
			}			
			$out .= '</div>';				
		}
		return $out;
	}
	
	function getNewelemButtonTitle() {
		return 'Neuen Datensatz erstellen';
	}
	
	function getPermissions($elem,$page='') {
		return $this->controller->getPermissions($elem,$page,$this->model);
	}
	
	function createDataExport(&$dataList,$mode,$title,$fieldList='') {
		switch ($mode) {
		case 'csv':
      /** @var tx_femanagement_view_export_csv $exportObj */
			$exportObj = t3lib_div::makeInstance('tx_femanagement_view_export_csv',$this);
			$exportObj->createDataExport($dataList,$title,$fieldList);
			exit();
		case 'xls':
      $exportMode = $this->controller->getExportConfig();
      if ($exportMode == 'phpexcel') {
        /** @var tx_femanagement_view_export_excel $exportObj */
        $exportObj = t3lib_div::makeInstance('tx_femanagement_view_export_excel_xls',$this);
        $exportObj->createDataExport($dataList,$title,$fieldList);
      } else {
        /** @var tx_femanagement_view_export_excel $exportObj */
        $exportObj = t3lib_div::makeInstance('tx_femanagement_view_export_excel',$this);
        $exportObj->createDataExport($dataList,$title,$fieldList);
      }
			exit();
    case 'doc':
      /** @var tx_femanagement_view_export_word $exportObj */
      $exportObj = t3lib_div::makeInstance('tx_femanagement_view_export_word',$this);
      $exportObj->createDataExport($dataList,$title,$fieldList);
      exit();
		}
	}
	
	function createDataListJs() {
		return '';
	}
	
	function createDataList(&$dataList) {
		$templateCode = $this->controller->getTemplateCodeListView();
		$fieldList = $this->controller->getListViewFields();
		if (isset($this->urlArgs['page'])) {
			$page = $this->urlArgs['page'];
		} else {
			$page = '';
		}
    $out = '';
		if (!empty($templateCode) && $templateCode!='none') {
			$rowClass = 'odd';
			if ($this->controller->showTitlesListView()) {
				$out .=  $this->showColTitlesTemplate($fieldList,$templateCode);
			}
			foreach ($dataList as $elem) {
				if (is_array($elem['permissions']) && count($elem['permissions'])>0) {
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
					$out .=  $this->showTemplateListItem($templateCode,$elem,$fieldList,$elem['permissions'],$rowClass);
				}
			}
			$out .= '</table>';
		} else {
			$out .= '<table id="fe_management_view_data_list">';
			$rowClass = 'odd';
			$out .=  $this->showColTitles($fieldList);
			foreach ($dataList as $elem) {
				if (is_array($elem['permissions']) && count($elem['permissions'])>0) {
					$out .=  $this->showListItem($elem,$fieldList,$elem['permissions'],$rowClass);
					if ($rowClass == 'odd') {
						$rowClass = 'even';
					} else {
						$rowClass = 'odd';
					}
				}
			}
			$out .= '</table>';
		}
		$out .= '<script type="text/javascript">
					$(".sortTitle").click(function(event) {
						var field = $(this).attr("id");
						sortReload(field);
					});
					</script>
					';
		return $out;
	}

	function showActions($elem,$permissions,$previewLinkUrl='') {
		$out = '';
		foreach($permissions as $action) {
			switch ($action) {
				case 'view': 
					$out .= $this->createLinkSingleView($elem['uid'],$previewLinkUrl);
					break;
				case 'edit': 
					$out .= $this->createLinkSingleEdit($elem['uid']);
					break;
				case 'copy': 
					$out .= $this->createLinkSingleCopy($elem['uid']);
					break;
				case 'delete': 
					if (!$elem['deleted']) {
						$out .= $this->createLinkSingleDelete($elem['uid']);
					} 
					break;
				case 'undelete': 
					if ($elem['deleted']) {
						$out .= $this->createLinkSingleUndelete($elem['uid']);
					}
					break;
				case 'destroy': 
					if ($elem['deleted']) {
						$out .= $this->createLinkSingleDestroy($elem['uid']);
					} 
					break;
				case 'hide': 
					if (!$elem['deleted'] && !$elem['hidden']) {
						$out .= $this->createLinkSingleHide($elem['uid']);
					} else {
					if (!$elem['deleted'] && $elem['hidden']) {
						$out .= $this->createLinkSingleUnhide($elem['uid']);
					}
					break;
				}
			}
		}
		return $out;
	}
	
}
	
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php']);
}

?>
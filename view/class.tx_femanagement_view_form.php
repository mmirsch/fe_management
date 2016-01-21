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

class tx_femanagement_view_form {
protected $wrapClass;
protected $listItemWrap = '<div class="item">|</div>';
protected $piBase;
protected $pid;
protected $args;
protected $title;
protected $eidUrl;
protected $pageId;
protected $previewPid;
protected $singlePageConfig;
protected $page;
protected $az;
protected $menu = array();
protected $controllerName;
protected $controller;
protected $modelName;
protected $model;
protected $urlArgs;
static $init = FALSE;

	public function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		$this->piBase = &$piBase;
		$this->pid = $pid;
		$this->title = $title;
		$this->eidUrl = $eidUrl;
		$this->wrapClass = $wrapClass;
		$this->pageId = $GLOBALS['TSFE']->id;
		if (!self::$init) {
			self::initJs();
			self::$init = TRUE;
		}
		$get = $piBase->get;
		if (isset($get['tx_femanagement']['page'])) {
			$this->page = $get['tx_femanagement']['page'];
		} else {
			$this->page = '';
		}
	}
	
	function setPageId($pageId)	{
		$this->pageId = $pageId;
	}
	
	function setPage($page)	{
		$this->page = $page;
	}
	
	function setControllerName($controllerName)	{
		$this->controllerName = $controllerName;
	}
	
	function setModelName($modelName)	{
		$this->modelName = $modelName;
		if (strpos($this->eidUrl,'&model')===FALSE) {
			$this->eidUrl .= '&model=' . $this->modelName;
		}
	}
	
	function setEidUrl($eidUrl)	{
		$this->eidUrl = $eidUrl;
	}
	
	public static function getOptionList($daten,$value='') {
		$optionData = '';
		foreach ($daten as $uid=>$wert) {
			$selected = '';
			if (!empty($value)) {
				if (is_array($value)) {
					if (in_array($uid,$value)) {
						$selected = ' selected="selected" ';
					}
				} else {
					if ($uid==$value) {
						$selected = ' selected="selected" ';
					}
				}
			}
			$optionData .= '<option ' . $selected . 'value="'. $uid . '">' . 
											$wert . 
											'</option>' . "\n";
		}
		return $optionData;
	}
	
	function setMenu(&$menu,$cssClass) {
		$this->menu = array('data'=>$menu,'cssClass'=>$cssClass,);
	}
	
	function showMenu($aktuelleSeite='') {
		$out = '';
		if (count($this->menu)>0) {
			$out .= '<div id="' . $this->menu['cssClass'] . '">';	
			foreach ($this->menu['data'] as $button) {
				if (empty($button)) {
					$out .= '<hr />';
				} else {
					$out .= $button->show($aktuelleSeite);
				}
			}
			$out .= '</div>';				
		}
		return $out;
	}
	
	function createMenuEntry($title, $page) {
		$button = '';
		$linkConf = array();
		$name = $page;
		if ($page=='newElem') {
			$linkConf['parameter'] = $GLOBALS['TSFE']->id;
//			$linkConf['additionalParams'] .= '&tx_femanagement[mode]=new&tx_femanagement[page]=' . $page;
			$linkUrl = $this->piBase->cObj->typoLink_URL($linkConf) . '?tx_femanagement[mode]=new&tx_femanagement[page]=' . $page;
			$button = t3lib_div::makeInstance('tx_femanagement_view_actions',
					$name,
					$title,
					$linkUrl
			);
			
		} else {
			$linkConf['parameter'] = $GLOBALS['TSFE']->id;
//			$linkConf['additionalParams'] = '&tx_femanagement[page]=' . $page;
			$linkUrl = $this->piBase->cObj->typoLink_URL($linkConf) . '?tx_femanagement[page]=' . $page;
			$button = t3lib_div::makeInstance('tx_femanagement_view_actions',
					$name,
					$title,
					$linkUrl
			);
			
		}
		return $button;
	}
			
	function createButton($type,$params,$popup=FALSE) {
		$button = '';
		$linkConf = array();
		$linkConf['parameter'] = $GLOBALS['TSFE']->id;
		if (isset($params['tx_femanagement']['page'])) {
			$linkConf['additionalParams'] = '&tx_femanagement[page]=' .
																			$params['tx_femanagement']['page'];
		} else {
			$linkConf['additionalParams'] = '';
		}
		switch ($type) {
		case 'newElem':
			$title = $this->getNewelemButtonTitle();
			$name = 'newElem';
			$linkConf['additionalParams'] .= '&tx_femanagement[mode]=new';
			$event = '';
			if ($popup) {
				$event = t3lib_div::makeInstance('tx_femanagement_view_actions_popup');
				$linkConf['additionalParams'] .= '&popup=1';
			}
			$linkUrl = $this->piBase->cObj->typoLink_URL($linkConf);
			$button = t3lib_div::makeInstance('tx_femanagement_view_actions',
																				$name,
																				$title,
																				$linkUrl,
																				$event
								);
			break;
		}
		return $button;
	}
			
	function createFilter($type,$name,$title,$sessionDaten,$data='',$defaultValue='',$toggle=FALSE,$additionalCssClass='',$options='') {
		$button = '';
		if (isset($sessionDaten[$name])) {
			$value = $sessionDaten[$name];
		} else {
			$value = $defaultValue;
		}
		switch ($type) {
		case 'check':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_check',
																				$name,
																				$title,
																				$value,
																				$data,
																				$toggle,
																				$additionalCssClass
								);
			break;
		case 'date':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_date',
																				$name,
																				$title,
																				$value,
																				$data,
																				$toggle,
																				$additionalCssClass
								);
			break;
		case 'hidden':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_hidden',
																				$name,
																				$title,
																				$value,
																				$data,
																				$toggle,
																				$additionalCssClass
								);
			break;
		case 'search':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_search',
																				$name,
																				$title,
																				rawurldecode($value),
																				$data,
																				$toggle,
																				$additionalCssClass
								);
			break;
		case 'select':
			if (isset($options['hideOptionSelectAll'])) {
				$hideOptionSelectAll = TRUE;
			} else {
				$hideOptionSelectAll = FALSE;
			}
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_select',
																				$name,
																				$title,
																				rawurldecode($value),
																				$data,
																				$toggle,
																				$additionalCssClass,
																				$defaultValue,
																				$hideOptionSelectAll
								);
			break;
    case 'text':
      $filter = t3lib_div::makeInstance('tx_femanagement_view_filter_text',
        $name,
        $title,
        rawurldecode($value),
        $data,
        $toggle,
        $additionalCssClass
      );
      break;
		case 'export':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_export',
																				$name,
																				$title
								);
			break;
		case 'toggle':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_toggle',
																				$name,
																				$title,
																				$value
								);
			break;
		case 'reset':
			$filter = t3lib_div::makeInstance('tx_femanagement_view_filter_reset',
																				$name,
																				$sessionDaten,
																				$title
								);
			break;
		}
		return $filter;
	}
			
	static function initJs() {
	}
	
	function session_data(&$args,$app='') {
		if (empty($app)) {
			$app = 'default';
		}
		if (is_array($args) && count($args)>0) {
			if (isset($args['method'])) {
				if ($args['method']=='get') {
					return $this->getSessionData($app);
				} else if ($args['method']=='set' &&
									 is_array($args['data'])) {
					return $this->setSessionData($args['data'],$app);
				} else if ($args['method']=='clear') {
					return $this->clearSessionData($app);
				}
			}
		}
	}
	
	function setSessionData($sessionData,$app) {
		if (!is_null($GLOBALS['TSFE']->fe_user)) {
			$currentSessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
			$currentSessionData[$app] = $sessionData;
//t3lib_div::devLog('set: ' . $app, 'session_data', 0, $currentSessionData);		
			$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($currentSessionData));
			$GLOBALS['TSFE']->fe_user->storeSessionData();
		}
	}
	
	function getSessionData($app) {
		$data = array();
		if (!is_null($GLOBALS['TSFE']->fe_user)) {
			$data = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
//t3lib_div::devLog('get: ' . $app, 'session_data', 0, $data);		
			return $data[$app];
		}
		return '';
	}

	function clearSessionData($app) {
		if (!is_null($GLOBALS['TSFE']->fe_user)) {
			$currentSessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
			$currentSessionData[$app] = array();
//t3lib_div::devLog('clear: ' . $app, 'session_data', 0, $currentSessionData);		
			$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($currentSessionData));
			$GLOBALS['TSFE']->fe_user->storeSessionData();
		}
	}

	function testPermissions(&$data,$action) {
		$controller = t3lib_div::makeInstance($data['ctrl']);
//		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
//		$owner = $this->model->isOwner($data['args'],$userId);
		$hiddenField = 'hidden';
		$permissions = $controller->getPermissions($data,'',$this->model,$hiddenField);
		return in_array($action,$permissions);
	}

	function deleteEntry($data) {
		$this->model = t3lib_div::makeInstance($data['model']);
		if ($this->testPermissions($data,'delete')) {
			return $this->model->deleteElem($data['uid']);			
		}
	}
	
	function destroyEntry($data) {
		$this->model = t3lib_div::makeInstance($data['model']);
		if ($this->testPermissions($data,'delete')) {
			return $this->model->destroyElem($data['uid']);			
		}
	}
	
	function undeleteEntry($data) {		
		$this->model = t3lib_div::makeInstance($data['model']);
		if ($this->testPermissions($data,'undelete')) {
			return $this->model->undeleteElem($data['uid']);			
		}
	}
	
	function hideEntry($data) {
		$this->model = t3lib_div::makeInstance($data['model']);
		if ($this->testPermissions($data,'hide')) {
			return $this->model->hideElem($data['uid']);			
		}
	}
	
	function unhideEntry($data) {
		$this->model = t3lib_div::makeInstance($data['model']);
		if ($this->testPermissions($data,'hide')) {
			return $this->model->unhideElem($data['uid']);			
		}
	}

	function createLinkText($uid,$additionalParams,$text,$linkClass,$title,$target='_blank',$popup=FALSE,$id='',$norefresh=FALSE) {
		if (empty($id)) {
			$id = $this->pageId;
		}
/* 
 * Seitenmodus mitübertragen
 */		
		$get = t3lib_div::_GET();
		if (isset($get['tx_femanagement']['page'])) {
			$additionalParams .= '&tx_femanagement[page]=' .
														$get['tx_femanagement']['page'];
		}
		$hash = md5($additionalParams . $uid);
		if ($popup) {
			$popupArg = '&popup=1';
		} else {
			$popupArg = '';
		}
		if ($norefresh) {
			$popupArg .= '&norefresh=1';
		}
		$idParam = '&tx_femanagement[uid]=' . $uid;
		$linkUrl = 'index.php?id=' . $id . $additionalParams . $idParam . $popupArg;
		$additionalCode = '';
		if ($this->singlePageConfig['mode']=='fancybox') {
			$additionalCode .= ' data-fancybox-type="iframe" ';
			if (empty($linkClass)) {
				$linkClass = 'fancyBoxLink';
			} else {
				$linkClass .= ' fancyBoxLink';
			}
			$popup = FALSE;
		}
		if ($popup) {
			$linkClass .= ' popup_window';
		}
		$link = '<a data-linkurl="' . $linkUrl . '" class="' . $linkClass . '" id="link_' . $hash . '" target="' . $target . '" class="' . $class . '" title="' . $title . '" ' . 
					  $additionalCode . 'href="' . $linkUrl . '">' . $text . '</a>
					  ';
		return $link;
	}
	
	function createLinkUrlIcon($linkUrl,$linkClass,$title,$target='_blank') {
		$link = '<a class="' . $linkClass . '" target="' . $target . '" title="' . $title . '" ' . 
					  'target="blank" href="' . $linkUrl . '"></a>
					  ';
		return $link;
	}
	
	function createLinkIcon($uid,$additionalParams,$linkClass,$title,$target='_blank',$popup=FALSE,$id='',$norefresh=FALSE) {
		if (empty($id)) {
			$id = $this->pageId;
		}
/* 
 * Seitenmodus mitübertragen
 */		
		$get = t3lib_div::_GET();
		if (isset($get['tx_femanagement']['page'])) {
			$additionalParams .= '&tx_femanagement[page]=' .
														$get['tx_femanagement']['page'];
		}
		$hash = md5($additionalParams . $uid);
		if ($popup) {
			$popupArg = '&popup=1';
		} else {
			$popupArg = '';
		}
		if ($norefresh) {
			$popupArg .= '&norefresh=1';
		}
		$idParam = '&tx_femanagement[uid]=' . $uid;
		$linkUrl = 'index.php?id=' . $id . $additionalParams . $idParam . $popupArg;
		$link = '<a class="' . $linkClass . '" id="link_' . $hash . '" target="' . $target . '" title="' . $title . '" ' . 
					  'target="blank" href="' . $linkUrl . '"></a>
					  ';
		if ($popup) {
			$link .= '<script type="text/javascript">
								 $("#link_' . $hash . '").click(function () {
								 		return popup("' . $linkUrl . '","' . $title . '",50,50,600,600);
								 		});
								 </script>
								 ';
		}
		return $link;
	}
	
	function getTitleCreateLinkSingleView() {
		return 'Detailansicht';
	}
	
	function getTitleCreateLinkSingleEdit() {
		return 'Bearbeiten';
	}
	
	function getTitleCreateLinkSingleCopy() {
		return 'Kopieren - Erstellt einen neuen Eintrag als Kopie des vorhandenen';
	}
	
	function getTitleCreateLinkSingleHide() {
		return 'Verbergen - Verborgene Elemente werden nicht mehr angezeigt';
	}
	
	function getTitleCreateLinkSingleUnhide() {
		return 'Aktivieren - Verborgenen Einträge werden wieder aktiviert';
	}
	
	function getTitleCreateLinkSingleDelete() {
		return 'Löschen - Gelöschte Einträge können nur von einem Administrator wiederhergestellt werden';
	}
	
	function getTitleCreateLinkSingleDestroy() {
		return 'Endgültig Löschen - Eintrag unwiderruflich aus der Datenbank löschen';
	}
	
	function getTitleCreateLinkSingleUndelete() {
		return 'Löschen rückgängig machen - Aktiviert einen gelöschten Eintrag und zeigt Ihn wieder an';
	}
	
	function createLinkSingleView($uid,$linkUrl='') {
		$iconClass = 'icon-actions t3-icon-version-workspace-preview';
		$title = $this->getTitleCreateLinkSingleView();
		if (empty($linkUrl)) {
			$additionalParams = '&tx_femanagement[mode]=view';
			return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_blank',TRUE,'',TRUE);
		}	else {
			return $this->createLinkUrlIcon($linkUrl,$iconClass,$title,'_blank');
		}
	}
		
	function createLinkSingleEdit($uid) {
		$additionalParams = '&tx_femanagement[mode]=edit';
		$iconClass = 'icon-actions t3-icon-document-open';
		$title = $this->getTitleCreateLinkSingleEdit();
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
			
	function createLinkSingleCopy($uid) {
		$additionalParams = '&tx_femanagement[mode]=copy';
		$iconClass = 'icon-actions t3-icon-edit-copy';
		$title = $this->getTitleCreateLinkSingleCopy();
		return $this->createLinkIcon($uid,$additionalParams,$iconClass,$title,'_self',TRUE);
	}
			
	function createLinkSingleHide($uid) {
		$id = md5('unhide' . $uid);
		$title = $this->getTitleCreateLinkSingleHide();
		$icon = '<span class="icon-actions t3-icon-edit-hide" title="' . $title . '" id="id_' . $id . '"></span>';
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName . '&methode=hideEntry&uid=' . $uid;
		$jquery = '<script type="text/javascript">
			$("#id_' . $id . '").click(function(e) {
				executeAjax("' . $eidUrl . '",true);
			});
			</script>
			';
		return $icon . $jquery;
	}
			
	function createLinkSingleUnhide($uid) {
		$id = md5('unhide' . $uid);
		$title = $this->getTitleCreateLinkSingleUnhide();
		$icon = '<span class="icon-actions t3-icon-edit-unhide" title="' . $title . '" id="id_' . $id . '"></span>';
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName . '&methode=unhideEntry&uid=' . $uid;
		$jquery = '<script type="text/javascript">
			$("#id_' . $id . '").click(function(e) {
				executeAjax("' . $eidUrl . '",true);
			});
			</script>
			';
		return $icon . $jquery;
	}
			
	function createLinkSingleDelete($uid) {
		$id = md5('delete' . $uid);
		$title = $this->getTitleCreateLinkSingleDelete();
		$icon = '<span class="icon-actions t3-icon-edit-delete" title="' . $title . '" id="id_' . $id . '"></span>';
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName . '&methode=deleteEntry';
		$jquery = '<script type="text/javascript">
			$("#id_' . $id . '").click(function(e) {
				loeschabfrage(e.pageX,e.pageY,"' . $uid . '","' . $eidUrl . '");
			});
			</script>
			';
		return $icon . $jquery;
	}

	function createLinkSingleUndelete($uid) {
		$id = md5('undelete' . $uid);
		$title = $this->getTitleCreateLinkSingleUndelete();
		$icon = '<span class="icon-actions t3-icon-edit-restore" title="' . $title . '" id="id_' . $id . '"></span>';
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName . '&methode=undeleteEntry&uid=' . $uid;
		$jquery = '<script type="text/javascript">
			$("#id_' . $id . '").click(function(e) {
				executeAjax("' . $eidUrl . '",true);
			});
			</script>
			';
		return $icon . $jquery;
	}
	
	function createLinkSingleDestroy($uid) {
		$id = md5('destroy' . $uid);
		$title = $this->getTitleCreateLinkSingleDestroy();
		$icon = '<span class="icon-actions icon-destroy" title="' . $title . '" id="id_' . $id . '"></span>';
		$eidUrl = $this->eidUrl . '&ctrl=' . $this->controllerName . '&methode=destroyEntry';
		$jquery = '<script type="text/javascript">
		$("#id_' . $id . '").click(function(e) {
		loeschabfrage(e.pageX,e.pageY,"' . $uid . '","' . $eidUrl . '","Soll dieses Element wirklich endgültig gelöscht werden?");
	});
	</script>
	';
		return $icon . $jquery;
	}
	
	function getAjaxSelectDataList($data) {
		$model = t3lib_div::makeInstance($data['model']);
		$daten = $model->getList($data['args'],$data['pid'],$data['limit']);
		$jsonDaten = array();
		foreach ($daten as $uid=>$titel) {
			$jsonDaten[] = array('value'=>$uid, 'title'=>$titel);
		}
		return $jsonDaten;
	}
	
}
	
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php']);
}

?>
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

#require_once(t3lib_extMgm::extPath('fe_management').'model/class.tx_femanagement_cal_model.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */

class tx_femanagement_controller {
protected $piBase;
protected $params;
protected $eidUrl;
protected $mode = 'ajax';
protected $eidViewHandler;
protected $formView;
protected $model;
protected $validationDependencies = array();
protected $config;
protected $pageId;
protected $templateCodeListView;
public $feUser;

	function __construct(&$piBase='',&$params='') {		
		$this->piBase = &$piBase;
		$this->params = &$params;
		$previewPid = $piBase->settings['PREVIEW_PID'];
		$this->eidUrl = 'index.php?eID=fe_management' .
										'&previewPid=' . $previewPid;
		$this->feUser = $GLOBALS['TSFE']->fe_user->user['username'];
		$this->config = tx_femanagement_lib_util::getPageConfig('config.');
		$currentSessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
		$this->pageId = tx_femanagement_lib_util::getPageConfig('pageId');
		if (empty($this->pageId)) {
			$this->pageId = $GLOBALS['TSFE']->id;
			if (empty($this->pageId)) {
				$get = t3lib_div::_GET();
				$this->pageId = $get['args']['page_id'];
				if (empty($this->pageId)) {
					$this->pageId = $get['id'];
				}
			}
		}
		if (!empty($this->config)) {
			$currentSessionData[$this->pageId]['config'] = $this->config;
			$this->templateCode = '';
			$this->templateCodeListView = '';
			if ($this->config['no_list_template']!='true') {
				if (!empty($this->piBase)) {
					$templateFileName = '';
					if (!empty($this->config['template'])) {
						$templateFileName = $this->config['template'];
						if (!empty($templateFileName)) {
							$templateFileName = 'typo3conf/ext/fe_management/' . $templateFileName;
							if (!empty($templateFileName)) {
								$templateCode = $piBase->cObj->fileResource($templateFileName);
								$this->templateCodeListView = $this->piBase->cObj->getSubpart($templateCode,'###TEMPLATE_LIST###');
								$currentSessionData[$this->pageId]['templateCodeListView'] = $this->templateCodeListView;
							}
						}
					}
				}
			} else {
				$currentSessionData[$this->pageId]['templateCodeListView'] = $this->templateCodeListView;
			}
			$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($currentSessionData));
			$GLOBALS['TSFE']->fe_user->storeSessionData();
		} else {
			$this->config = $currentSessionData[$this->pageId]['config'];
			$this->templateCodeListView = $currentSessionData[$this->pageId]['templateCodeListView'];
		}
	}
		
	function isAdmin($application='',$domain=0) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		if (empty($application)) {
			$application = get_class($this);
		}
		return $permissionHandler->isAdmin($application,$domain);
	}
		
	function isDomainAdmin($domain= -1) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		return $permissionHandler->isAdmin(get_class($this),$domain);
	}
		
	function isApplicationAdmin($application='',$domain= -1) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		if (empty($application)) {
			$application = get_class($this);
		}
		return $permissionHandler->isApplicationAdmin($application,$domain);
	}
		
	function isEditor($application='',$domain=0) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		if (empty($application)) {
			$application = get_class($this);
		}
		return $permissionHandler->isEditor($application,$domain);
	}
	
	function isDomainEditor($domain) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		return $permissionHandler->isEditor(get_class($this),$domain);
	}
		
	function isReviser($application='',$domain=0) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		if (empty($application)) {
			$application = get_class($this);
		}
		return $permissionHandler->isReviser($application,$domain);
	}
		
	function isDomainReviser($domain) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		return $permissionHandler->isReviser(get_class($this),$domain);
	}
		
	function getOwnerFieldname() {
		return 'cr_uid';
	}
		
	function getUserData($field) {
		if (!empty($GLOBALS['TSFE']->fe_user->user[$field])) {
			return $GLOBALS['TSFE']->fe_user->user[$field];
		} else {
			return '';
		}
	}

	function getUserid($field) {
		return $this->getUserData('uid');
	}
	
	function getUsername($field) {
		return $this->getUserData('username');
	}
	
	function getUserFullname($field) {
		return $this->getUserData('name');
	}
	
	function getUserEmail($field) {
		return $this->getUserData('email');
	}
	
	function getPermissions(&$elem,$page='',&$model='',$hiddenField = 'hidden') {
		// Neuer Datensatz?
		if (empty($elem['uid'])) {
			$permissions = array('edit',
					'copy',
					'view',
			);
			return $permissions;
		}
		if (empty($model)) {
			$model = $this->model;
		}
		
		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
		$owner = $model->isOwner($elem['uid'],$userId);
		if ($this->isAdmin()) {
			$permissions = array('edit',
													 'copy',
													 'delete',
													 'undelete',
													 'destroy',
													 'hide',
													 'view',
			);
		} else if ($owner) {
			if ($this->isReviser()) {
			$permissions = array('edit',
													 'view',
													 'copy',
					
			);
		} else {
			$permissions = array('view',
													 'copy',
													 
				);
			}
		} else if ($this->isEditor()) {
			$permissions = array('view',
													 
			);
		} else if ($elem['deleted']==0 && $elem[$hiddenField]==0) {
			$permissions = array('view',
			);
		} else {
			$permissions = array();
		}
		return $permissions;
	}
	
	function getAdminList($domain=0) {
		$permissionHandler = t3lib_div::makeInstance('tx_femanagement_controller_permissions_main');
		return $permissionHandler->getAdmins(get_class($this),$domain);
	}
	
	/*
	 * ############## Alle registrieten Controller zurückgeben ####################
	 */

	public static function getAllApplications() {
		return array(
		// permissions
		'tx_femanagement_controller_permissions_domains' => 'Rechtemanagement Bereiche',
		'tx_femanagement_controller_permissions_groups' => 'Rechtemanagement Gruppen',
		'tx_femanagement_controller_permissions_roles' => 'Rechtemanagement Rollen',

		// cal
		'tx_femanagement_controller_calendar_event' => 'Kalender Termine',
		'tx_femanagement_controller_calendar_location' => 'Kalender Orte',
		'tx_femanagement_controller_calendar_organizer' => 'Kalender Veranstalter',

		// qsm
		'tx_femanagement_controller_qsm_main' => 'Qualitätssicherungsmittel',
		'tx_femanagement_controller_qsm_antraege' => 'QSM Anträge',
		'tx_femanagement_controller_qsm_einrichtungen' => 'QSM Einrichtungen',
		'tx_femanagement_controller_qsm_gremien' => 'QSM Gremien',
		'tx_femanagement_controller_qsm_zeitraeume' => 'QSM Zeiträume',
				
		// tt_news
		'tx_femanagement_controller_news' => 'News',
		
		// forschung
		'tx_femanagement_controller_forschung' => 'Forschung',
		
		// modules_en
		'tx_femanagement_controller_modules_en' => 'Englischsprachige Module',
		
		// events
		'tx_femanagement_controller_events' => 'Veranstaltungen',
		
		);
	}
	
	/*
	 * ############## Handler für Single-View ####################
	 */
	
	function handle_edit(&$parameter,$post,$uid,$aktuelleSeite='') {
		$this->initSingleView();
		if (isset($parameter['abort'])) {
			return $this->specialRedirect('abort');
		}	
		if (isset($parameter['saved'])) {
			return $this->specialRedirect('formSaved');
		}	
		$formData = array();
		$this->initFormSingle($formData,'edit');
		if (!$post) {
			$parameter = $this->getFormDataDbSingle($formData,$uid);			
		}
		$this->initFormData($formData,$parameter,$post);
		
		$permissions = $this->getPermissions($parameter,$aktuelleSeite,$this->model);
		if (!in_array('edit',$permissions)) {
			return 'Kein Zugriff (edit)';
		}
		if (isset($parameter['save'])) {
			return $this->saveForm($formData,$parameter,$uid,'','edit');
		}	else if (isset($parameter['publish'])) {
			$hidden = 0;
			return $this->saveForm($formData,$parameter,$uid,$hidden,'publish');
		} else {
			return $this->showDataFormSingle($formData,$parameter,'edit',$aktuelleSeite);
		}
	}
	
	function handle_copy(&$parameter,$post,$uid,$aktuelleSeite='') {
		$this->initSingleView();
		if (isset($parameter['abort'])) {
			return $this->specialRedirect('abort');
		}	
		if (isset($parameter['saved'])) {
			return $this->specialRedirect('formSaved');
		}	
		$formData = array();
		$this->initFormSingle($formData,'copy');
		if (!$post) {
			$parameter = $this->getFormDataDbSingle($formData,$uid);			
		}
		$this->initFormData($formData,$parameter,$post);
		$permissions = $this->getPermissions($parameter,$aktuelleSeite,$this->model);
		if (!in_array('copy',$permissions)) {
			return 'Kein Zugriff (copy)';
		}
		if (isset($parameter['save'])) {
			// Kopien immer verbergen
			$hidden = 1;
/*
			if ($this->isAdmin()) {
				$hidden = 0;
			} else {
				$hidden = 1;
			}
*/			
			return $this->saveForm($formData,$parameter,'',$hidden,'copy');
		} else {
			return $this->showDataFormSingle($formData,$parameter,'copy',$aktuelleSeite);
		}
	}
	
	function handle_new(&$parameter,$post,$get='',$aktuelleSeite='') {
		if (isset($get['abort'])) {
			return $this->specialRedirect('abort');
		}	
		if (isset($get['saved'])) {
			return $this->specialRedirect('formSaved');
		}	
		$this->initSingleView();
		$formData = array();
		$this->initFormSingle($formData,'new');		
		$this->initFormData($formData,$parameter,$post);

		if (isset($parameter['save'])) {
			$admin = $this->isAdmin();
			if ($admin) {
				if (get_class($this)=='tx_femanagement_controller_news' ||
					get_class($this)=='tx_femanagement_controller_forschung' ||
					get_class($this)=='tx_femanagement_controller_promotionen') {
					$hidden = 1;
				} else {
					$hidden = 0;
				}
			} else {
				$hidden = 1;
			}

			return $this->saveForm($formData,$parameter,'',$hidden,'new');
		}	else  {
			return $this->showDataFormSingle($formData,$parameter,'new',$aktuelleSeite);
		}
	}
	
	function handle_view($uid) {
		$this->initSingleView();
		$formData = array();
		$this->initFormSingle($formData,'show');
		$formParameter = $this->getFormDataDbSingle($formData,$uid);
		$this->initFormData($formData,$formParameter);
		$permissions = $this->getPermissions($parameter);
		if (!in_array('view',$permissions)) {
			return 'Kein Zugriff (view)';
		}
		return $this->showDataViewSingle($formData,$formParameter);
	}
	
	/*
	 * ############## Methoden ####################
	 */
	
	function saveForm(&$formData,&$parameter,$uid='',$hidden,$mode) {
		if ($this->validationOk($formData)) {
			$erg = $this->saveFormData($formData,$uid,$hidden);
			$linkConf = array();
			$linkConf['parameter'] = $GLOBALS['TSFE']->id;
			if (isset($this->params['tx_femanagement']['page'])) {
				$linkConf['additionalParams'] = '&tx_femanagement[page]=' .
																				$this->params['tx_femanagement']['page'] . 
																				'&tx_femanagement[saved]=1';
			} else {
				$linkConf['additionalParams'] = '&tx_femanagement[saved]=1';
			}
			$linkUrl = $this->piBase->cObj->typoLink_URL($linkConf);
			if ($erg) {
				$this->postProcessingSaveForm($erg,$formData,$mode);
			} else {
				$linkConf['additionalParams'] = '&error';					
			}
			$this->redirect($linkUrl);
		} else {
			return $this->errorFormData($formData,$parameter,$mode);
		} 
	}

	function postProcessingSaveForm($uid,&$formData,$mode='save') {
		// Kindklassen müssen hier die entsprechenden Befehle implementieren 
	}
	
	function saveFormData(&$formData,$uid,$hidden) {
		if (!empty($uid)) {
			$res = $this->model->updateDbEntry($formData,$uid,'hidden',$hidden);
		} else {
			$res = $this->model->insertDbEntry($formData,'hidden',$hidden);
		}
		return $res;
	}
	
	function pageAfterAbort() {
		return "<h3>Bitte klicken Sie einen Menüpunkt an.</h3>";
	}
	
	function pageAfterSavedForm() {
		return '<h3>Vielen Dank für Ihren Eintrag.</h3>
					 Sie werden per E-Mail über den weiteren Verlauf informiert.';
	}
	
	function specialRedirect($mode) {
		if ($this->testParam('popup')) {
			if ($this->testParam('norefresh')) {
								echo '<script>
								window.close();
								</script>
				';
			} else {
				echo '<script>
								window.opener.location.reload();
								window.close();
								</script>
				';
				
			}
			exit;
		}
		if ($mode=='abort') {
			return $this->pageAfterAbort();
		} else if ($mode=='formSaved') {
			return $this->pageAfterSavedForm();
		}
		$linkConf = array();
		$linkConf['parameter'] = $GLOBALS['TSFE']->id;
		if (isset($this->params['tx_femanagement']['page'])) {
			$linkConf['additionalParams'] = '&tx_femanagement[page]=' .
																			$this->params['tx_femanagement']['page'];
		}
		$linkUrl = $this->piBase->cObj->typoLink_URL($linkConf);
		return $this->redirect($linkUrl);
	}

	function testParam($param) {
		return t3lib_div::_GP($param);
	}
	
	function redirect($linkUrl) {
		if ($this->testParam('popup')) {
			if ($this->testParam('norefresh')) {
								echo '<script>
								window.close();
								</script>
				';
			} else {
				echo '<script>
							window.opener.location.reload();
							window.close();
							</script>
				';
				
			}
			exit;
		}	 else {
			t3lib_utility_Http::redirect($linkUrl);
		}
	}
	
	function errorFormData(&$formData,&$parameter,$mode) {
		return $this->showDataFormSingle($formData,$parameter,$mode);
	}
	
	function getValidationDependencies() {
		return $this->validationDependencies;
	}
	
	function checkValidationDependencies($invalidList,$testKey) {
		if (count($this->validationDependencies)>0) {
			foreach ($this->validationDependencies as $key=>$dependencies) {
				foreach ($dependencies as $event=>$valueActions) {
					foreach ($valueActions as $actions) {
						if (in_array($testKey,$actions['actions']['valid'])) {
							if (!in_array($key,$invalidList)) {
								return TRUE;
							}
						}
					}
/*
						foreach ($actions['actions'] as $action=>$fields) {
							switch($action) {
								case 'valid':
		
		
		if (count($this->validationDependencies)>0) {
			foreach ($this->validationDependencies as $key=>$dependencies) {
				if (in_array($testKey,$dependencies['valid'])) {
					if (!in_array($key,$invalidList)) {
						return TRUE;
					}
*/					
				}
			}
		}
		return FALSE;
	}
	
	function validationOk(&$formData) {
		$valid = TRUE;
		$invalidList = array();
		foreach ($formData as $key=>$feld) {
			if (!$feld->validate()) {
				$invalidList[] = $key;
//				$formData[$key] = $feld;
			}
		}
		if (count($invalidList)>0) {
			$valid = TRUE;
			foreach ($invalidList as $key) {
				if (!$this->checkValidationDependencies($invalidList,$key)) {
					$valid = FALSE;
				}
			}
		}
		return $valid;
	}
	
	function getAjaxEventUrl($event) {
		$url = $this->eidUrl . 
					 '&class=' . $this->eidHandler . 
					 '&event=' . $event;
	}
	
	function getPid($pid='') {
		$erg = tx_femanagement_lib_util::getPageConfig('pid');
		if (empty($erg)) {
			$erg = $this->piBase->settings['STORAGE_PID'];
		}
		if (empty($erg)) {
			$erg = $pid;
		}
		return $erg;
	}
	
	function showDataViewSingle(&$formData,&$parameter) {
		return $this->showDataSingle($formData,$parameter,'view');
	}
	
	function showDataFormSingle(&$formData,&$parameter,$mode,$aktuelleSeite='') {
		return $this->showDataSingle($formData,$parameter,$mode,$aktuelleSeite);
	}
	
	function showDataSingle(&$formData,&$parameter,$mode,$aktuelleSeite='') {
		$this->createFormSingle($formData,$parameter,$mode);
//		$this->formView->createFormSingle($formData);
		$out = $this->formView->show($mode,$aktuelleSeite);
		if ($this->testParam('popup')) {
			if ($this->testParam('norefresh')) {
				$out .= '	<div id="popup_code">
		<input type="button" value="Fenster schliessen" 
		 onclick="javascript:window.close();" />
		</div>
				';
			} else {
				$out .= '	<div id="popup_code">
		<input type="button" value="Fenster schliessen" 
		 onclick="javascript:window.opener.location.reload();window.close();" />
		</div>
				';
			}
		}
		return $out;
	}
	
	function getFormDataDbSingle(&$formData,$uid) {
		$formParameter = array();
		$dbDataList = $this->model->selectData(
			array('show_deleted'=>1,
						'show_hidden'=>1,
						'all_pids'=>TRUE,
						'sqlFilter'=> 'uid=' . $uid,
			));
		if (count($dbDataList)==1) {
			$dbData = $dbDataList[0];
			$formParameter = $this->model->createFormData($formData,$dbData,$uid);
		}
		return $formParameter;
	}

	function handle_list_view($aktuelleSeite) {
		$this->initListView();
		return $this->showListView($aktuelleSeite);
	}

	function initGlobalFilters(&$sessionDaten,&$filterListe) {
		$filterListe[100] = $this->formView->createFilter('hidden','sortField','',$sessionDaten,'title');
		$filterListe[101] = $this->formView->createFilter('hidden','sortMode','',$sessionDaten,'ASC');
		$filterListe[102] = $this->formView->createFilter('hidden','page','',$sessionDaten,'0');
		$filterListe[103] = $this->formView->createFilter('hidden','page_id','','','',$this->pageId);
	}
	
	function showListView($aktuelleSeite) {
		$filterListe = array();
		$sessionDaten = $this->formView->getSessionData(get_class($this));
		self::initGlobalFilters($sessionDaten,$filterListe);
		$buttonListe = array($this->formView->createButton('newElem',$this->params));
		$filterListe[10] = $this->formView->createFilter('search','volltextsuche','Volltextsuche',$sessionDaten);
		$anzSelect = array('10'=>'10','25'=>'25','50'=>'50','100'=>'100');
		$filterListe[20] = $this->formView->createFilter('select','num_entries','Anzahl/Seite',$sessionDaten,$anzSelect,25,TRUE);
		return $this->formView->showListView($buttonListe,$filterListe,$aktuelleSeite);
	}

	function initFormData(&$formData,&$parameter,$post=FALSE) {
		if (is_array($parameter) && count($parameter)>1) {
			foreach ($parameter as $key=>$value) {
				if (isset($formData[$key])) {
					if ($post) {
						$value = $formData[$key]->convertPostParameter($value);
					}
					$formData[$key]->setValue($value);
					$type = $formData[$key]->getFieldType();
					if ($type=='file') {
						$uploadFilename = '';
						$fieldname = $formData[$key]->getName();
						$uploadDir = $formData[$key]->getUploadDir();
						if (tx_femanagement_controller_lib_upload::handleFileUpload($fieldname,$uploadDir,$uploadFilename)) {
							$formData[$key]->setValue($uploadFilename);
						}
					} else if ($type=='dyn_table') {
						if (is_array($value)) {
							$dynTableWerte = $value;
						} else {
							$dynTableWerte = unserialize($value);
						}
						$colTypes = $formData[$key]->getColTypes();
						foreach($colTypes as $field=>$colType) {
							if ($colType=='image' || $colType=='file') {
								$fieldname = $formData[$key]->getName();
								$uploadDir = $formData[$key]->getUploadDir();
								if (is_array($value) && count($value)>0) {
									$uploadFilename = '';
									foreach($value as $index=>$werte) {
										if (tx_femanagement_controller_lib_upload::handleFileUpload($fieldname,$uploadDir,$uploadFilename,array('index'=>$index,'field'=>$field))) {
											$value[$index][$field] = $uploadFilename;
										}
									}
								}
								$formData[$key]->setValue($value);
							}
						}
					} else if ($type=='ajax_select' || $type=='feuser_select') {		
						if (!$formData[$key]->isDynList()) {
							if (!empty($parameter['field_' . $key])) {
								$formData[$key]->setSelectValue($parameter['field_' . $key]);
							} else if (!empty($value)) {
								$selectValue = $this->getSelectDataSingle($formData[$key]->getModel(),$value);
								$formData[$key]->setSelectValue($selectValue);
							}
						}
					}
				}
			}
		}	
	}
	
	function initFormSingle(&$formData,$mode='new') {
		$fieldSettings['title'] = array(
												'title'=>'Titel',
												'type'=>'text',
												'validate'=>'string',
												);
		$formData = $this->createFormFields($fieldSettings,$mode);
	}
	
	function createFormSingle(&$formData,&$parameter,$mode) {
		$allgemeineFelder = array('title');										
		$container = $this->createContainer($allgemeineFelder,$formData);
		$containerList = array($container);
		$this->formView->addFieldset($containerList);		#Create new Fieldset with all container
		$this->formView->addFormSingleButtons(array('speichern'=>'Maßnahme speichern'));
	}	
	
	function createContainer(&$elemList,&$dataArray,$fieldOnly=FALSE,$wrapClass='') {
		$containerFields = array();
		foreach ($elemList as $name) {
			$containerFields[$name] = &$dataArray[$name];
		}
		return array($containerFields,$fieldOnly,$wrapClass);
	}
	
	function createFormFields(&$dataArray,$mode='new') {
		$formFields = array();
		foreach ($dataArray as $name=>$data) {
			$data['name'] = $name;
			$data['pid'] = $this->getPid();
			$formFields[$name] = $this->createViewFormElement($data);
		}
		return $formFields;
	}
	
	function createViewFormElement($data) {
		switch ($data['type']) {
			case 'button': $viewClass = 'tx_femanagement_view_field_button'; break;
			case 'checkbox': $viewClass = 'tx_femanagement_view_field_checkbox'; break;
			case 'date': $viewClass = 'tx_femanagement_view_field_date'; break;
			case 'dyn_table': $viewClass = 'tx_femanagement_view_field_dyn_table'; break;
			case 'file': $viewClass = 'tx_femanagement_view_field_file'; break;
			case 'hidden': $viewClass = 'tx_femanagement_view_field_hidden'; break;
			case 'info': $viewClass = 'tx_femanagement_view_field_info'; break;
			case 'input': $viewClass = 'tx_femanagement_view_field_input'; break;
			case 'multiselect': $viewClass = 'tx_femanagement_view_field_multiselect'; break;
			case 'password': $viewClass = 'tx_femanagement_view_field_password'; break;
			case 'radio': $viewClass = 'tx_femanagement_view_field_radio'; break;
			case 'readonly': $viewClass = 'tx_femanagement_view_field_readonly'; break;
			case 'rte': $viewClass = 'tx_femanagement_view_field_textarea'; break;
			case 'select': $viewClass = 'tx_femanagement_view_field_select'; break;
			case 'ajax_select': $viewClass = 'tx_femanagement_view_field_ajax_select'; break;
			case 'feuser_select': $viewClass = 'tx_femanagement_view_field_ajax_feuser_select'; break;
			case 'text': $viewClass = 'tx_femanagement_view_field_text'; break;
			case 'time': $viewClass = 'tx_femanagement_view_field_time'; break;
			default: $viewClass = 'tx_femanagement_view_field_input'; break;
		}
		return t3lib_div::makeInstance($viewClass,$data);
	}
	
	function getListViewFields() {
		return array('title'=>'Titel');
	}
	
	function getSelectDataSingle($modelClassName,$uid) {
		$model = t3lib_div::makeInstance($modelClassName);
		return $model->getSingle($uid);
	}
	
	function getSinglePageConfig() {
		return '';
	}
	
	function getTemplateCodeListView() {
		return $this->templateCodeListView;
	}

	function showTitlesListView() {
		return ($this->config['listview.']['showTitles']!='false');
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller.php']);
}

?>
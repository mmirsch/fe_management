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
require_once(t3lib_extMgm::extPath('fe_management') . 'view/class.tx_femanagement_view_form.php');
require_once(t3lib_extMgm::extPath('fe_management').'view/form_fields/class.tx_femanagement_view_fieldset.php');
require_once(t3lib_extMgm::extPath('fe_management').'view/form_fields/class.tx_femanagement_view_field.php');
require_once(t3lib_extMgm::extPath('fe_management').'view/form_fields/class.tx_femanagement_view_container.php');

/**
 * Plugin 'Frontend Management' for the 'fe_management' extension.
 *
 * @author	HS-Esslingen>
 * @package	TYPO3
 * @subpackage	tx_femanagement
 */

class tx_femanagement_view_form_single extends tx_femanagement_view_form {
protected $fieldsetList;
protected $validationDependencies;

	public function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
		$this->fieldsetList = array();
	}
	
	function setEidUrl($eidUrl)	{
		$this->eidUrl = $eidUrl;
	}
	
	function addFieldset($containerList,$title='',$wrapClass='')	{
		$fieldset = t3lib_div::makeInstance('tx_femanagement_view_fieldset',
																						 $title,
																						 $wrapClass);
		if (is_array($containerList) && count($containerList)>0) {
			foreach ($containerList as $containerDaten) {
				$fieldset->addContainer($containerDaten[0],$containerDaten[1],$containerDaten[2]);
			}
		}
		$this->fieldsetList[] = $fieldset;																				 
	}
	
	function setValidationDependencies(&$validationDependencies)	{
		$this->validationDependencies = $validationDependencies;
	}

	function createFormSingle($formularDaten) {
		return;
	}
	
	function addFormSingleButtons($buttonTitles=array()) {
		if (empty($buttonTitles)) {
			$buttonTitle = 'Eintrag Speichern';
			$formData['speichern'] = t3lib_div::makeInstance('tx_femanagement_view_field_button',
											array('title'=>$buttonTitle,
												 'name'=>'save',
												 'buttonType'=>'submit',
												 'value'=>$buttonTitle,
											));
		} else {
			foreach ($buttonTitles as $key=>$title) {
				$formData[$key] = t3lib_div::makeInstance('tx_femanagement_view_field_button',
						array('title'=>$title,
								'name'=>$key,
								'buttonType'=>'submit',
								'value'=>$title,
						));
			}
		}

		if (!isset($buttonTitles['abbrechen'])) {
			$buttonTitle = 'Abbrechen';
			$formData['abbrechen'] = t3lib_div::makeInstance('tx_femanagement_view_field_button',
								array('title'=>$buttonTitle,
										 'name'=>'abort',
										 'buttonType'=>'abort',
										 'value'=>$buttonTitle,
			));
		} 
		$buttonList = array();
		foreach($formData as $key=>$button) {
			$buttonList[$key] = $button;
		}

		$buttonContainer = array(
												$buttonList,
												FALSE,
												'buttons'
												);			
		$containerList = array($buttonContainer);						
		$this->addFieldset($containerList,'','');						
	}
	
	function show($mode,$aktuelleSeite)	{
		$out = $this->showMenu($aktuelleSeite); 
		$out .= $this->initElemCodeSingle($mode);
		foreach ($this->fieldsetList as $fieldset) {
			$out .= $fieldset->show($mode);
		}
		$out .= $this->exitElemCodeSingle($mode);
		return $out;
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
			foreach ($data as $field=>$value) {
				$wert = '';
				if (!empty($value)) {
					switch ($field) {
						case 'bild':
							$pfad = 'uploads/tx_hebest/' . $value;
							$bildadresse = tx_femanagement_lib_util::createJpgImage($pfad, 200);
							$wert = '<div class="image"><img width="200px" src="' . $bildadresse . '" /></div>';
							break;
						case 'produktname':
							$wert = '<h2>' . $fields[$field] . $value . '</h2>';
							break;
						case 'preis':
							$value = str_replace(',','.',$value);
							$preis = sprintf("%01.2f &euro;", $value);
							$wert = '<div class="preis"><label>' . $fields[$field] . '</label>' . $preis . '</div>';
							break;
						case 'bemerkung':
							$wert = '<div class="description"><label>' . $fields[$field] . '</label>' . $value . '</div>';
							break;
						case 'artikelnummer':
							$wert = '<div class="artikelnummer"><label>' . $fields[$field] . '</label>' . $value . '</div>';
							break;
						case 'hersteller_bezeichnung':
							$wert = '<div class="hersteller_bezeichnung"><label>' . $fields[$field] . '</label>' . $value . '</div>';
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
	
	function initElemCodeSingle($mode)	{
		if (!empty($this->wrapClass)) {
			$classCode = ' class="' . $this->wrapClass . '"';
		} else {
			$classCode = '';
		}
    $out = '';
		if (!empty($this->title)) {
			$out .= '<h2>' . $this->title . '</h2>';
		}
		if ($mode=='view') {
			$out .= '<div id="fe_management_viewdata"' . $classCode . '>';				
		} else {
			$out .= '<form autocomplete="off" id="fe_management" action="" enctype="multipart/form-data" method="POST" ' . $classCode . '>';
		}
		return $out;
	}
	
	function exitElemCodeSingle($mode)	{
		if ($mode=='view') {
			$out = '</div>';
		} else {
			$out = '</form>';
			$jqueryCode = '';
			$out .= $this->getValidationJs($this->validationDependencies);
		}
		return $out;
	}
	
	function getValidationJs($validationDependencies)	{
		$jqueryCode = '';
		if (count($validationDependencies)>0) {
			foreach ($validationDependencies as $key=>$dependencies) {
				foreach ($dependencies as $event=>$valueActions) {
					$jqueryCode .= 'function ' . $key . '_' . $event . '(elem){
						';
					foreach ($valueActions as $actions) {
						$jqueryCode .= 'if (' . $actions['condition'] . ') {
						';
						foreach ($actions['actions'] as $action=>$fields) {
							switch($action) {
								case 'valid':
									foreach ($fields as $field) {
										$jqueryCode .= 'if ($("#' . $field . '").attr("required")=="required") {
											$("#' . $field . '").removeAttr("required");
										}
										';
									}
									break;
								case 'required':
									foreach ($fields as $field) {
										$jqueryCode .= 'if ($("#' . $field . '").attr("required")!="required") {
											$("#' . $field . '").attr("required","required");
										}
										';
									}
									break;
								case 'hide':
									foreach ($fields as $field) {
										$jqueryCode .= 'if ($("#' . $field . '").css("display")!="none") {
											$("#' . $field . '").css("display","none");
										}
										';
									}
									break;
								case 'show':
									foreach ($fields as $field) {
										$jqueryCode .= 'if ($("#' . $field . '").css("display")=="none") {
											$("#' . $field . '").css("display","inherit");
										}
										';
									}
									break;
							}
						}
						$jqueryCode .= '}
						';
					}
					$jqueryCode .= '}
					';
					$jqueryCode .= '$("#' . $key . '").' . $event . '(function(){
						' . $key . '_' . $event . '(this);
						});
						' . $key . '_' . $event . '($("#' . $key . '"));
					';
				}
			}
		}
			
		$out .= '
		<div id="errors">
		</div>
		<script type="text/javascript">
					  ';
		$out .= $jqueryCode;
		$out .= 'var formInput =  $("#fe_management").validator({ 
		 						effect: "individualPosition",
		 						grouped: true,
								lang: "de" 
							});
							
							$("#fe_management").submit(function(e) {
								if ($(".cancel").attr("clicked")) {
									return true;
							  }
							});			
								
							$.tools.validator.addEffect("individualPosition", function(errors, event) {		
								if ($(".cancel").attr("clicked")) {
									return true;
							  }						 
								var firstError = true;
								var firstElem;
								$("label.error").detach();
								$.each(errors, function(index, error) {
									var elemId = error.input.attr("id");
									var labelId = "error_" + elemId;
									var anzMeldungen = error.messages.length;
									if (firstError) {
										firstElem = elemId;
										firstError = false;
									}
									$("<label class=\'error\' id=\'" + labelId + "\'>" + error.messages[anzMeldungen-1] + "</label>").appendTo($("#" + elemId).parent());
								});
								$("#" + firstElem).focus();
							}, function(inputs)  {
								$.each(inputs, function(index, elem) {
								  var elemId = $(elem).attr("id");
									var errorLabelId = "error_" + elemId;
									$("#" + errorLabelId).detach();
								});
							
							});
					  ';
		$out .= '</script>
					  ';
		return $out;
	}
}
	
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_form.php']);
}

?>
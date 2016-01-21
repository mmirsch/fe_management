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
class tx_femanagement_view_field_ajax_select extends tx_femanagement_view_field {

	var $ajaxHandler;
	var $urlNew;
	var $limit;
	var $coords;
	var $minLength=0;
	var $newRowTitle = 'Eintrag hinzufügen';
	static protected $jsIncluded = FALSE;
	
	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['ajaxHandler'])) {
			$this->ajaxHandler = $elements['ajaxHandler'];
		} else {
			$this->ajaxHandler = 'index.php?eID=fe_management&view=tx_femanagement_view_form&methode=getAjaxSelectDataList';
		}
		if (!empty($elements['urlNew'])) {
			$this->urlNew = $elements['urlNew'];
		}
		if (!empty($elements['limit'])) {
			$this->limit = $elements['limit'];
		}
		if (!empty($elements['minLength'])) {
			$this->minLength = $elements['minLength'];
		}
		if (!empty($elements['coords'])) {
			$this->coords = $elements['coords'];
		}
		if (!empty($elements['prefill'])) {
			$this->value = $elements['prefill']['value'];
			$this->valueSelect = $elements['prefill']['valueSelect'];
		}
	}
	
	function viewData()	{
		return '<span class="value">' . $this->valueSelect . '</span>';
	}
	
	function viewDynData()	{
		$elemList = array();
		if (empty($this->value)) {
			$out = '<span class="label">' . $this->title . ': </span>' .
						 '<span class="value"></span>';
		} else if (!is_array($this->value)) {
			$out = '<span class="label">' . $this->title . ': </span>' .
						 '<span class="value">' . $this->valueSelect . '</span>';
		} else {
			$out = '';
			if (count($this->value)==1) {
				foreach ($this->value as $elem) {
					$zeile = '<span class="label">' . $this->title . ': </span>' .
								 	'<span class="value">' . $elem['valueSelect'] . '</span>';
					$out .= $this->dataWrap($zeile);
				}
			} else {
				$count = 1;
				foreach ($this->value as $elem) {
					$zeile = '<span class="label">' . $this->title . ' ' . $count . ': </span>' .
							'<span class="value">' . $elem['valueSelect'] . '</span>';
					$out .= $this->dataWrap($zeile);
					$count++;
				}
			}
		}
		return $out;
	}
	
	function csvData()	{
		return $this->valueSelect;
	}
	
	function editData()	{
		$validateClass = $this->createValidation('select');
		$out = '';

		if (empty($this->limit)) {
			$limit = 50;
		} else {
			$limit = $this->limit;
		}
		$eidUrl = $this->ajaxHandler . 
							'&limit=' . $limit . 
							'&type=json' . 
							'&pid=' . $this->pid .
							'&model=' . $this->model;
		$encodedEidUrl = urlencode($eidUrl);
		$elemId = 'select_' . $this->name;
		$out .= '<div id="select_' . $this->name . '" class="selectElem">';
		$hidden = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '" id="uid_' . $this->name . '" />';
		$input = '<input data-fieldname="' . $this->name . '" data-eidUrl="' . $encodedEidUrl . '" readonly="readonly" type="text" ' . $validateClass . ' name="field_' . $this->name . '" value="' . $this->valueSelect . '" id="' . $this->name . '" />';
		$hiddenQuoted = str_replace('"','\"',$hidden);
		$inputQuoted = str_replace('"','\"',$input);
		$out .= $hidden . $input;
		if (!self::$jsIncluded) {
			if ($this->minLength>0) {
				$title = '"Zur Auswahl bitte mindestens ' . $this->minLength . ' Zeichen eingeben. "';
			} else {
				$title = '"Zum Eingrenzen der Ergebnisliste, geben Sie bitte einen Begriff ein. "';
			}
			if (!empty($this->urlNew)) {
				$title .= ' + "Falls Sie einen neuen Eintrag erstellen möchten, klicken Sie bitte auf das Plus-Icon rechts."';
			}
			$out .= '<script type="text/javascript">
							$(document).ready(function() {
								var eidUrl;
								$(".selectElem input.select").autocomplete({

								source: function( request, response ) {
									
									$.ajax({
										url: eidUrl + "&args=" + request.term,
										dataType: "json",
										async: false,
										success: function( data ) {
											response( $.map( data, function( item ) {
												return {
													wert: item.value,
													value: item.title,
													label: item.title
												}
											}));
										}
									});
								},
								minLength: ' . $this->minLength . ',
								select: function( event, ui ) {
									var name = $(this).attr("data-fieldname");
									$("#uid_" + name).val(ui.item.wert);
									$(this).attr("readonly","readonly");
								}
						});
						
						$("input.select" ).click(function() {
							$(this).val("");
							$(this).attr("title", ' . $title . ');
							$(this).removeAttr("readonly");
							eidUrl = $(this).attr("data-eidUrl");
							$(this).autocomplete("option","eidUrl",eidUrl);
							$(this).autocomplete("search","");
						});
					});
					</script>
			';
			self::$jsIncluded = TRUE;
		} 
		$out .= $this->getDataElemActions();
		$out .= '</div>';
		return $out;
	}
	
	function getDataElemActions()	{
		$elemActions = '';
/*
 * ggf. Linkicon "Neuen Eintrag anlegen"
 */		
		if (!empty($this->urlNew)) {
			$icon = '<img src="typo3/sysext/t3skin/icons/gfx/new_el.gif" />';
			$link = '<a class="iconLink" id="new_' . $this->name . '" title="' . $this->linkTitle . '" target="blank" href="' . $this->urlNew . '">' . $icon . '</a>';
			$elemActions .= $link;
			if (!empty($this->coords)) {
				$coord = ',' . implode(',',$this->coords);
			} else {
				$coord = '';
			}
			$elemActions .= '<script type="text/javascript">
								$("#new_' . $this->name . '").click(
									function() {
								 		return popup("' . $this->urlNew . '","' . $this->linkTitle . '"' . $coord . ');
									}
								);
								</script>
			';
		}
		return $elemActions;
	}
	
	function getDynDataWrap()	{
		$wrap[0] = '<div id="row_select_' . $this->name . '###numRows###" class="selectElem ' . $this->name . '">';
		$wrap[1] = '</div>';
		return $wrap;
	}
	
	function getDynDataLabel()	{
		$title = $this->title . ' ###numRows###';
		$label = $this->createLabel('',$title);
		return $label;
	}
	
	function getDynDataElemActions()	{
		$elemActions = '';
/*
 * ggf. Linkicon "Neuen Eintrag anlegen"
 */		
		if (!empty($this->urlNew)) {
			$icon = '<img src="typo3/sysext/t3skin/icons/gfx/new_el.gif" />';
			$link = '<a class="iconLink new_' . $this->name . '" title="' . $this->linkTitle . '" target="blank" href="' . $this->urlNew . '">' . $icon . '</a>';
			$elemActions .= $link;
		}
		return $elemActions;
	}
	
	function getDynDataElemCode()	{
		$elemCode = '<input class="dynSelectValue" ' . 
						 		' type="hidden" name="' . $this->name . '[###numRows###][value]"  id="uid_' . $this->name .  '###numRows###" value="###value###" />' .
						 		' <input class="dynSelectLabel" readonly="readonly" ' . $validateClass . 
						 		' type="text"   data-row="###numRows###" name="' . $this->name . '[###numRows###][valueSelect]" id="' . $this->name . '###numRows###" value="###selectValue###" />';
		$elemCode .= $this->getDynDataElemActions();
		return $elemCode;
	}
	
	function getDynDataEmptyRow(&$label,&$elemCode,&$wrap)	{
		$label = $this->getDynDataLabel();
		$elemCode = $this->getDynDataElemCode();
		$wrap = $this->getDynDataWrap();
	}
	
	function getAutoCompleteCode() {
		if (empty($this->limit)) {
			$limit = 50;
		} else {
			$limit = $this->limit;
		}
		$eidUrl = $this->ajaxHandler . 
							'&limit=' . $limit . 
							'&type=json' . 
							'&pid=' . $this->pid .
							'&model=' . $this->model;
		$autoCompleteCode = '
					function autoComplete_' . $this->name . '(elem) {
						$(elem).autocomplete({
							source: function( request, response ) {
								$.ajax({
									url: "' . $eidUrl . '",
									dataType: "json",
									data: {
										args: request.term,
										type: "json"
									},
									async: false,
									success: function( data ) {
										response( $.map( data, function( item ) {
											return {
												wert: item.value,
												value: item.title,
												label: item.title
											}
										}));
									}
								});
							},
							minLength: ' . $this->minLength . ',
							select: function( event, ui ) {
								var index = $(this).attr("data-row");
								$("#uid_' . $this->name . '" + index).val(ui.item.wert);
								$("#' . $this->name . '" + index).attr("readonly","readonly");
							}
					});
				}
				';
		return $autoCompleteCode;
	}
	
	function getDynJsCode($elemCode) {
		if (empty($this->limit)) {
			$limit = 50;
		} else {
			$limit = $this->limit;
		}
		$eidUrl = $this->ajaxHandler . 
							'&limit=' . $limit . 
							'&type=json' . 
							'&pid=' . $this->pid .
							'&model=' . $this->model;
		$autoCompleteCode = $this->getAutoCompleteCode();
		$jsCode = $autoCompleteCode;
		$jsCode .= '
				$("div.' . $this->name . ' .dynSelectLabel").each(function() {
					autoComplete_' . $this->name . '(this);
				});
				function addListElem_' . $this->name . '(button){
	        var newIndex = $(".selectElem.' . $this->name . '").length+1;
        	var newElem = \'' . $elemCode . '\';
        	newElem = newElem.replace(/###numRows###/g,newIndex);
          newElem = newElem.replace(/###value###/g,"");
          newElem = newElem.replace(/###selectValue###/g,"");
          $($(button).parent()).before(newElem);
          autoComplete_' . $this->name . '($("#' . $this->name . '" + newIndex));
				}
				function	renumberList_' . $this->name . '() {
					$(".selectElem.' . $this->name . '").each(function (row) {
						var index = row+1;
						var selectElem = $(".dynSelectLabel",this);
					  selectElem.attr("name","' . $this->name . '[" + index + "][valueSelect]");
					  selectElem.attr("id","' . $this->name . '" + index);
					  selectElem.attr("data-row",index);
					  var valueElem = $(".dynSelectValue",this);
					  valueElem.attr("name","' . $this->name . '[" + index + "][value]");
					  valueElem.attr("id","uid_' . $this->name . '" + index);
					});
				}
				$("#addElem_' . $this->name . '" ).click(function(){
						addListElem_' . $this->name . '(this);
						renumberList_' . $this->name . '();
					});
				$("#field_' . $this->name . '").delegate(".dynSelectLabel","click", function(){
					var index = $(this).attr("data-row");
					$("#uid_' . $this->name . '" + index).val("");
					$(this).val("");
					';
		
		if ($this->minLength>0) {
			$title = '"Zur Auswahl bitte mindestens ' . $this->minLength . ' Zeichen eingeben. "';
		} else {
			$title = '"Zum Eingrenzen der Ergebnisliste, geben Sie bitte einen Begriff ein. "';
		}
		if (!empty($this->urlNew)) {
			$title .= ' + "Falls Sie einen neuen Eintrag erstellen möchten, klicken Sie bitte auf das Plus-Icon rechts."';
		}	
		$jsCode .= '$(this).attr("title", ' . $title . ');
						 $(this).removeAttr("readonly");
						 $(this).autocomplete("search","");
						}
					);
		';
		if (!empty($this->coords)) {
			$coord = ',' . implode(',',$this->coords);
		} else {
			$coord = '';
		}
		$jsCode .= '
						$(".new_' . $this->name . '").click(
							function() {
								return popup("' . $this->urlNew . '","' . $this->linkTitle . '"' . $coord . ');
							}
						);
		';
		return $jsCode;
	}
	
	function editDynData()	{
		$elemList = array();
		if (empty($this->value)) {
			$elemList[] = array('value'=>'','valueSelect'=>'');
		} else if (!is_array($this->value)) {
			$elemList[] = array('value'=>$this->value,'valueSelect'=>$this->valueSelect);
		} else {
			$elemList = $this->value;
		}
		$count = 1;
		$label = '';
		$elemCode = '';
		$wrap = array();
		$this->getDynDataEmptyRow($label,$elemCode,$wrap);
		$elemCode = $wrap[0] . $label . $elemCode . $wrap[1];
		foreach ($elemList as $elem) {
			$rowCode = str_replace('###numRows###',$count,$elemCode);
			$rowCode = str_replace('###value###',$elem['value'],$rowCode);
			$rowCode = str_replace('###selectValue###',$elem['valueSelect'],$rowCode);
			$count++;
			$out .= $rowCode;
		}
		$out .= '<div class="field">';
		$out .= '<input id="addElem_' . $this->name . '" type="button" value="' . $this->newRowTitle . '">';
		$out .= '</div>';
		
		$out .= '<script type="text/javascript">
						' . $this->getDynJsCode($elemCode) . '
						</script>';
		return $out;
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_ajax_select.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_tx_femanagement_view_field_ajax_select.php']);
}

?>
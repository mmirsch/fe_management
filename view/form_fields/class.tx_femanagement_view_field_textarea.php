<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Hochschule Esslingen
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
class tx_femanagement_view_field_textarea extends tx_femanagement_view_field {

protected $cols = 50;
protected $rows = 10;
protected $maxEditorWidth = 900;
protected $minChars = false;
protected $maxChars = false;
protected $admin = FALSE;
static protected  $JS_INCLUDED = FALSE;

	public function __construct($elements) {
		if (isset($elements['CONST_TEXTAREA_COLS'])) {
			$this->cols = $elements['CONST_TEXTAREA_COLS'];
		}
			if (isset($elements['CONST_TEXTAREA_ROWS'])) {
			$this->rows = $elements['CONST_TEXTAREA_ROWS'];
		}
		if (isset($elements['admin'])) {
			$this->admin = $elements['admin'];
		}
		parent::__construct($elements);
		if (self::$JS_INCLUDED == FALSE) {
			if ($this->admin) {
				$adminCode = ',code';
			} else {
				$adminCode = '';
			}
			$extraCode = '';
			if (isset($elements['configEditor']['maxEditorWidth'])) {
				$this->maxEditorWidth = $elements['configEditor']['maxEditorWidth'];
			}
			if ($this->validate=='required') {
				$this->minChars = 1;
			} else {
				$this->minChars = 0;
			}
			if (isset($elements['configEditor']['maxChars'])) {
				$this->maxChars = $elements['configEditor']['maxChars'];
			} else {
				$this->maxChars = 99999;
			}
			if ($this->maxChars<99999 || $this->minChars>0) {
					$extraCode = ',
		setup : function(ed) { //set up a new editor function
			var maxChars = ' . $this->maxChars . ';
			var minChars = ' . $this->minChars . ';
			var error = true;

	    ed.onKeyUp.add(function(ed, e) { 
	    var length, htmlcount;
	    length = $(ed.getContent({"format":"raw"})).text().length;
    	htmlcount = "Sie können noch: " + (maxChars-length) + " Zeichen eingeben";
	    if (length>maxChars){
	    	var fehlerMeldung = "Sie haben zu viele Zeichen (" + length + ") eingegeben!";
				if (error) {
    			$("#error_' . $this->name . '").detach();
    		}
        $("<label id=\'error_' . $this->name . '\' class=\'error htmlarea\'>" + fehlerMeldung + "</label>").appendTo($("#' . $this->name . '").parent());
        error = true;
      } else if (length<minChars) {
	    	var fehlerMeldung = "Dies Feld ist ein Pflichtfeld!";
				if (error) {
    			$("#error_' . $this->name . '").detach();
    		}
        $("<label id=\'error_' . $this->name . '\' class=\'error htmlarea\'>" + fehlerMeldung + "</label>").appendTo($("#' . $this->name . '").parent());
        error = true;
			} else {
				if (error) {
					error = false;
					$("#error_' . $this->name . '").detach();
				}
			}
    	}); 
		}
		';
			}
			
			$jsCode = '
				<script src="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/tiny_mce/jquery.tinymce.js" type="text/javascript"></script>
						<script type="text/javascript">
	$().ready(function() {
	
		$("textarea.tinymce").tinymce({
			// Location of TinyMCE script
			script_url : "/' . t3lib_extMgm::siteRelPath('fe_management') . 'res/tiny_mce/tiny_mce.js",

			// General options
			theme : "advanced",
			language : "de",
			plugins : "autolink,lists,paste,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,|,link,unlink,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,bullist,numlist,|,outdent,indent' . $adminCode . '",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_resizing_max_width : 900,
			theme_advanced_styles : "rote Schrift=rot;Überschrift 3=header3;Überschrift 4=header4;Absatz=paragraph",
			theme_advanced_blockformats : "p,h2,h3,h4,h5,h6,blockquote,code",
			content_css : "/fileadmin/css/eigenes_css/rte.css"
			' . $extraCode . '
		});
	});
</script>
						';
			$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= $jsCode;
			self::$JS_INCLUDED = TRUE;
		}
	}
	
	function setValue($value)	{
		$this->value = $value;
	}
	
	function getValue()	{
		return $this->value;
		return $this->strip_word_html($this->value);
	}
	
  function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br><br/><br /><div><span><p><h2><h3><h4><li><ol><ul><a><table><thead><tbody><tr><th><td>') { 
if ($GLOBALS['TSFE']->fe_user->user['username']=='mmirsch') {	
 t3lib_div::devLog('strip_word_html - vorher: ' . $text, 'fe_managment', 0);
}
  	mb_regex_encoding('UTF-8'); 
		//replace MS special characters first 
		$search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u'); 
		$replace = array('\'', '\'', '"', '"', '-'); 
		$text = preg_replace($search, $replace, $text); 
		//make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
		//in some MS headers, some html entities are encoded and some aren't 
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); 
		//try to strip out any C style comments first, since these, embedded in html comments, seem to 
		//prevent strip_tags from removing html comments (MS Word introduced combination) 
		if(mb_stripos($text, '/*') !== FALSE){ 
		    $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm'); 
		} 
		//introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
		//'<1' becomes '< 1'(note: somewhat application specific) 
		$text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text); 
		$text = strip_tags($text, $allowed_tags); 
		//eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
		$text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text); 
		//strip out inline css and simplify style tags 
		$search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu', '#<br[^>]*>#isu'); 
		$replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>', '<br />'); 
		$text = preg_replace($search, $replace, $text); 
		//on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
		//that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
		//some MS Style Definitions - this last bit gets rid of any leftover comments */ 
		$num_matches = preg_match_all("/\<!--/u", $text, $matches); 
		if($num_matches){ 
			$text = preg_replace('/\<!--(.)*--\>/isu', '', $text); 
		} 
		
		$search = array('#(<.*) class="[^"]*"#isuU', 
										'#(<.*) align="[^"]*"#isuU', 
										'#(<.*) clear="[^"]*"#isuU', 
										'#(<.*) [a-z]*=""#isuU', 
										'#<([a-z]*)></\1>#isuU',
										'#(<.*) style="text-align: ([^;"]*);"#isuU',
										'#(<.*) style="[^"]*"#isuU', 
										); 
		$replace = array('$1', '$1', '$1', '$1', '', '$1 class="align_$2"', '$1'); 
		$text = preg_replace($search, $replace, $text); 
if ($GLOBALS['TSFE']->fe_user->user['username']=='mmirsch') {	
	t3lib_div::devLog('strip_word_html - nachher: ' . $text, 'fe_managment', 0);
}
		return $text; 
	} 

	function editData()	{
		$validateClass = $this->createValidation('tinymce');
		if (!empty($this->value)) {
			$value = $this->value;
		} else {
			$value = '';
		}
		
		$out = '<textarea id="' . $this->name . '"' . 
						' name="' . $this->name . '"' . 
						' cols="' . $this->cols . '"' . 
						' rows="' . $this->rows . '" ' . $validateClass . '>' . $value . '</textarea>
						<script type="text/javascript">
						';
		if (!empty($this->validate) || !empty($this->maxChars)) {
			if (empty($this->maxChars)) {
				$this->maxChars = 99999;
			}
			$out .= '$.tools.validator.fn("#' . $this->name . '", function(element, value) {
								var fehlerMeldung = "";
								var text = $("#' . $this->name . '").html();
								var empty = text.match(/\S/g)==null;
								if (empty) {
									fehlerMeldung = "Dieses Feld ist ein Pflichtfeld.";
								} else {
		   						var length = $(text).text().length;
		   					 	if (length>' . $this->maxChars . ') {
		   					 		fehlerMeldung = "Sie haben zu viele Zeichen (" + length + ") eingegeben!";
		   					 	}
								}
								if (fehlerMeldung!="") {
									return fehlerMeldung;
								} else {
									return true;
								}
						});
							';
		} else {
			$out .= '
						$("form").submit(function() {
							var htmlCode = $("#' . $this->name . '").html();
							$("#' . $this->name . '").val(htmlCode);
						});
						';
		}
		if (empty($this->maxChars)) {
			$this->maxChars = 99999;
		}
		$out .= '$("#' . $this->name . '").blur(function() {
					var text = $("#' . $this->name . '").html();
					var empty = text.match(/\S/g)==null;
					if (!empty) {
						var length = $(text).text().length;
		   			if (length<' . $this->maxChars . ') {
							var errorLabelId = "error_' . $this->name . '";
							$("#" + errorLabelId).detach();
						}
					}
				});
				';
		$out .= '</script>
					 ';	
		return $out;
  } 
  
	function editDataOld()	{
if ($GLOBALS['TSFE']->fe_user->user['username']=='mmirsch') {
			return $this->editTinyMce();
}		
		$label = $this->createLabel();
		$validateClass = $this->createValidation();
		if (!empty($this->value)) {
			$value = $this->value;
		} else {
			$value = '';
		}

		$out = $label . 
			 '<textarea id="' . $this->name . '"' . 
						' name="' . $this->name . '"' . 
						' cols="' . $this->cols . '"' . 
						' rows="' . $this->rows . '" ' . $validateClass . '>' . $value . '</textarea>
						<script type="text/javascript">
						';
		$cssFile = '/' . t3lib_extMgm::siteRelPath('fe_management') . 'res/femanagement_jhtmlarea.css';
		
		$out .= '
					$("#' . $this->name . '").htmlarea({
						css: "' . $cssFile . '",
						toolbar: [
					        ["bold", "italic", "underline"],
					        ["link", "unlink"],
					        ["p", "h2", "h3", "h4"],
					        ["orderedList", "unorderedList"],
					        ["justifyleft","justifycenter","justifyright"]
						]';
		if (!empty($this->validate)) {
			$out .= ',
							events: {
                keyup: function() {
									var val = $(this).text();
									var empty = val.match(/\S/g)==null;
									$("#error_' . $this->name . '").detach();
									$("#error_' . $this->name . '").remove();
									if (empty) {
										$(\'<span id="error_' . $this->name . '" class="error htmlarea">Dieses Feld ist ein Pflichtfeld.</span>\').insertAfter($("#' . $this->name . '").parent());
									}
                },
                blur: function() {
									var val = $(this).text();
									var empty = val.match(/\S/g)==null;
									$("#error_' . $this->name . '").detach();
									$("#error_' . $this->name . '").remove();
									if (empty) {
										$(\'<span id="error_' . $this->name . '" class="error htmlarea">Dieses Feld ist ein Pflichtfeld.</span>\').insertAfter($("#' . $this->name . '").parent());
									}
                }
              }
						});
						$("form").submit(function() {
							if (!$(".cancel").attr("clicked")) {
								var text = $("#' . $this->name . '").htmlarea("toString");
								var empty = text.match(/\S/g)==null;
								$("#error_' . $this->name . '").detach();
								if (empty) {
									$(\'<span id="error_' . $this->name . '" class="error htmlarea">Dieses Feld ist ein Pflichtfeld.</span>\').insertAfter($("#' . $this->name . '").parent());
									return false;
								} else {						
									return true;
								}
							} else {
								return true;
							}
						});
							';
		} else {
			$out .= '
						});
						$("form").submit(function() {
							var htmlCode = $("#' . $this->name . '").htmlarea("toHtmlString");
							$("#' . $this->name . '").val(htmlCode);
						});
						';
		}
		$out .= '</script>
					 ';	
		return $out;
	}

	public static function getDyntableCode($key, $row, $name, $val) {
		return '<textarea class="' . $key . '" type="text" ' .
						'name="' . $name . '[' . $row . '][' . $key . ']">' . $val . '</textarea>';		
	}
	
	public static function getDyntableCodeNewElem($key, $name, $val) {
		return '<textarea type="text" class="' . $key . '" '.
           'name="' . $name . '[###numRows###][' . $key . ']">' . $val . '</textarea>';		
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_textarea.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_textarea.php']);
}

?>
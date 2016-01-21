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

class tx_femanagement_view_filter {
protected $type;
protected $name;
protected $title;
protected $data;
protected $value;
protected $toggle;
protected $additionalCssClass;
protected $defaultValue;

	public function __construct($type='',$name='',$title='',$value='',$data='',$toggle=FALSE,$additionalCssClass='',$defaultValue='') {
		$this->type = $type;
		$this->name = $name;
		$this->title = $title;
		$this->value = $value;
		$this->data = $data;
		$this->toggle = $toggle;
		$this->additionalCssClass = $additionalCssClass;
		$this->defaultValue = $defaultValue;
	}
	
	function getId() {
		return 'fe_management_filter_' . $this->name;
	}

	function getName() {
		return $this->name;
	}

	function getType() {
		return $this->type;
	}

	function showFilter()	{
		if ($this->toggle) {
			$toggle = ' toggle ';
		} else {
			$toggle = '';
		}
		if (!empty($this->additionalCssClass)) {
			$additionalCssClass = ' ' . $this->additionalCssClass;
		} else {
			$additionalCssClass = '';
		}
		$out = '<div class="filter ' . $toggle . $this->type . $additionalCssClass . '">';
		$out .= $this->show();	
		$out .= '</div>';	
		return $out;
	}
	
	static function createFilterJqueryAjaxRequest(

			$filterList,
			$eidReloadContentUrl,
			$eidSessionUrl,
			$htmlElem,
			$autoShow=FALSE,
			$limit='') {
				
		if (!empty($GLOBALS["TSFE"]->fe_user->user['username'])) {
			$session = TRUE;
			$sessionData = 'var sessionParam = {' . "\n";
		} else {
			$session = FALSE;
			$getSessionData = '';
		}
		
		$ajaxUrlVars = '';
		$sessionVars = '';
		$globals = 'var limit;' . "\n";
		if ($limit>0) {
			$globals .= 'limit = ' . $limit . ";\n";
		}
		$resetForm = 'function clearFormData() {' . "\n" . 
								 'var defaultValue;' . "\n";
		$getAjaxUrls = '
			function getReloadAjaxUrl() {
				var elemval;
				var ajaxUrl = "' . $eidReloadContentUrl . '";
		';
		$actions = '';

/*
 * Dynamische Filterliste
 */

		$sessionDataList = array();
		$ajaxUrlAppend = '';
		foreach($filterList as $filter) {
			$name = $filter->getName();
			$id = $filter->getId();
			$jsCode = TRUE;
			switch ($filter->type) {
			case 'search':
				$resetForm .= '$(\'#' . $id . '\').val(\'\');' . "\n";
				$elemVal = 'elemval = encodeURI($(\'#' . $id . '\').val());' . "\n";
				$actions .= '$(\'#' . $id . '\').bindWithDelay("keyup",function(event) {
						$(".a-z-filter .showAllChars").click();
						reloadContent();
					},500);
					';
				break;
			case 'select':
				$resetForm .= 'defaultValue = $(\'#' . $id . '\').attr(\'data-default\');' . "\n" . 
											'	if (defaultValue==undefined) defaultValue=\'\';' . "\n" . 
											'	$(\'#' . $id . '\').val(defaultValue);' . "\n";
				$elemVal = 'elemval = $(\'#' . $id . '\').val();' . "\n";
				$actions .= '$(\'#' . $id . '\').change(function(event) {
						reloadContent();
					});
					';
				break;
			case 'check':
				$resetForm .= '$(\'#' . $id . '\').val(\'\');' . "\n";
				$elemVal = 'if ($(\'#' . $id . '\').is(":checked")) {
											elemval = 1;
										} else {
											elemval = 0;
										}' . "\n";
				$actions .= '$(\'#' . $id . '\').click(function(event) {
						reloadContent();
					});
					';
				break;
			case 'date':
				$resetForm .= '$(\'#' . $id . '\').val(\'\');' . "\n";
				$elemVal = 'elemval = $(\'#' . $id . '\').val();' . "\n";
				$actions .= '$(\'#' . $id . '\').change(function(event) {
						reloadContent();
					});
					';
				break;
			case 'export':
				$actions .= '$(\'#' . $id . '\').click(function(event) {
						exportContent("&args[export]=' . $name . '");
					});
					';
				break;
			case 'hidden':
				$elemVal = 'elemval = encodeURI($(\'#' . $id . '\').val());' . "\n";
				break;
			case 'reset':
				$actions .= '$(\'#' . $id . '\').click(function(event) {
						resetForm();
					});
					';
				break;
			default:
				$jsCode = FALSE;
				break;
			}

			if ($jsCode) {
				$ajaxUrlVars .= $elemVal . 'var ' . $name . ' = elemval' . ";\n";
				$ajaxUrlAppend .= 'ajaxUrl += "&args[' . $name . ']=" + ' . $name . ';' . "\n";
				if ($session) {
					$sessionVars .= 'var ' . $name . ' = elemval' . ";\n";
					$sessionDataList[] = '"' . $name . '": ' . $name;
				}
			}
		}
		$resetForm .= '}' . "\n";
		$sessionData .= implode(",\n",$sessionDataList);
		$emptySessionData = '';
		if ($session) {
			$sessionData .= '};' . "\n";
			$emptySessionData .= '};' . "\n";
			$getSessionData = 'function getSessiondata() {
			' . $ajaxUrlVars . "\n" . 
			$sessionData . '
			return sessionParam;
			}
		
			';
		}
		if ($limit>0) {
			$ajaxUrlAppend .= 'ajaxUrl += "&args[limit]=' . $limit . '";' . "\n";
		}
		$getAjaxUrls .= $ajaxUrlVars . "\n" .
									 $ajaxUrlAppend . "\n" . '
									  return ajaxUrl;
									 }
		';
		$filterAction = '
			function reloadContent() {
				var url = getReloadAjaxUrl();
				processingAnimation("start","bitte warten");
			';
		if ($session) {
			$filterAction .= '
			storeSessionData();' . "\n";
		}
		$filterAction .= '
				$("#' . $htmlElem . '").load(url , function() {
					processingAnimation("stop");
				});
			}
			function exportContent(mode) {
			var url = getReloadAjaxUrl();
					url += mode;
				document.location.href = url;
			}
			function sortReload(field) {
			var sortField = $("#fe_management_filter_sortField").val();
			var sortMode = $("#fe_management_filter_sortMode").val();
				if (sortField==field) {
					if (sortMode=="DESC") {
						$("#fe_management_filter_sortMode").val("ASC");
					} else {
						$("#fe_management_filter_sortMode").val("DESC");
						sortMode = "DESC";
					}
				} else {
					$("#fe_management_filter_sortField").val(field);
					$("#fe_management_filter_sortMode").val("ASC");
				}
				reloadContent();
			}
			function pageReload(page) {
				$("#fe_management_filter_page").val(page);
				reloadContent();
			}
			
			function filterAz(c) {
				$("#fe_management_filter_az").val(c);
				reloadContent();
			}
			
			function resetForm() {
				clearFormData();
				reloadContent();
			}
			
			function storeSessionData() {
				var url = "' . $eidSessionUrl . '";
				var sessionData = getSessiondata();
				$.ajax({
					url: url,
					type: "POST",
					dataType: "json",
					data: { 
						"args": {
							"method": "set",
							"data": sessionData
						}
					}
				});
			}
						
			';
		$autoShowJs = '';
		if ($autoShow) {
			
			$autoShowJs .= 'reloadContent();' . "\n";
		}
		return '<script type="text/javascript">
					 ' . 
					 $globals .
					 $resetForm .
					 $getSessionData .
					 $getAjaxUrls . 
					 $filterAction . 
					 $actions . 
					 $autoShowJs .'
					 </script>';
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/filter/class.tx_femanagement_view_filter.php']);
}

?>
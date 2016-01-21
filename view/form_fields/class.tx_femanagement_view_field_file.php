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
class tx_femanagement_view_field_file extends tx_femanagement_view_field {
protected $accept;
protected $upload_dir;
protected $filetyp;
	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['accept'])) {
			$this->accept = $elements['accept'];
		}
		else {
			$this->accept = '*';
		}
		if (!empty($elements['upload_dir'])) {
			$this->upload_dir = $elements['upload_dir'];
		} else {
			$this->upload_dir = 'uploads/tx_femanagement/media/';
			t3lib_div::devlog($this->upload_dir,'Kein Upload Pfad angegeben');
		}
		if (!empty($elements['filetyp'])) {
			$this->filetyp = $elements['filetyp'];
		}
	}

	function editData()	{
		$validateClass = $this->createValidation('upload');
		if (!empty($this->value)) {
			$value = ' value="' . $this->value . '" ';
		} else {
			$value = ' value="" ';
		}
		$out = '';
		$out .= '<div class="fileUpload">';
		$fileUpload = '<input id="upload_' . $this->name . '" title="Die Vorhandene Datei ersetzen" type="button" value="Datei auswählen">
									<input class="upload_file" type="text" id="show_' . $this->name . '" value="' . $this->value . '">
									<span class="icon-actions t3-icon-edit-delete" title="Datei löschen" id="empty_' . $this->name . '"></span>
									<input class="hidden" id="' . $this->name . '" name="' . $this->name . '" type="file" ' . $validateClass . ' />';
		if (!empty($this->value)) {
			if ($this->filetyp == 'image') {
				$out .= '<a class="file_link" id="link_' . $this->name . '" href="' . $this->upload_dir . $this->value . '" target="_blank" ><img src="' . $this->upload_dir . $this->value . '" height="30px" alt="' . $this->value . '"></a>';
			} else {
				$out .= '<a class="file_link" id="link_' . $this->name . '" href="' . $this->upload_dir . $this->value . '" target="_blank" >' . $this->value . '</a>';
			}
		}
		$out .= $fileUpload . '<input id="hidden_' . $this->name . '" name="' . $this->name . '" type="hidden" ' . $value . ' /><br/>';
		$out .= '</div>
			<script type="text/javascript">
				$("#upload_' . $this->name . '").click(function(element, value) {
					$("#' . $this->name . '").click();
				});
				$("#show_' . $this->name . '").click(function(element, value) {
					$("#' . $this->name . '").click();
				});
				$("#empty_' . $this->name . '").click(function(element, value) {
					$("#' . $this->name . '").val("");
					$("#show_' . $this->name . '").val("");
					$("#hidden_' . $this->name . '").val("");
					$("#link_' . $this->name . '").detach();
				});
				$("#' . $this->name . '").change(function() {
					var file = $("#' . $this->name . '").val();  
					if (file!="") {
						var fileSizeBytes = this.files[0].size;
						var fileSizeKBytes = Math.round(parseInt(fileSizeBytes)/1024);
           	var fileSizeKBytesExact = Math.round(parseInt(fileSizeBytes)/10.24)/100;
           	var fileSizeMBytes = Math.round(parseInt(fileSizeKBytes)/1024);
           	var fileSizeMBytesExact = Math.round(parseInt(fileSizeKBytes)/10.24)/100;
           	var fileSize;
           	if (fileSizeBytes<1024) {
           		fileSize = fileSizeBytes + " B";
           	} else if (fileSizeKBytes<1024) {
           		fileSize = fileSizeKBytesExact + " KB";
           	} else {
           		fileSize = fileSizeMBytesExact + " MB";
           	}
						var showFilename = this.files[0].name  + " (" + fileSize + ")";
						var errorLabelId = "error_' . $this->name . '";
						$("#" + errorLabelId).detach();
						$("#show_' . $this->name . '").val(showFilename);
					}
				});
				';

		if (!empty($this->validate)) {
			$out .= '
				$.tools.validator.fn("#hidden_' . $this->name . '", function(element, value) {
					return true;
				});
				$.tools.validator.fn("#' . $this->name . '", function(element, value) {
					var file = $("#' . $this->name . '").val();  
					if (file!="") {
						return true;
					} else {
						file = $("#hidden_' . $this->name . '").val();  
						if (file!="") {
							$("#' . $this->name . '").val(file);
							return true;
						} else {
							return "Bitte wählen Sie eine Datei aus";
						}
					}
				});
				';
				}
			$out .= '
				</script>
			';
/*		
						<script type="text/javascript">
						 $("#' . $this->name . '").fileupload({
			        dataType: "json",
			        done: function (e, data) {
			            $.each(data.result, function (index, file) {
			                $("<p/>").text(file.name).appendTo($("file_' . $this->name . '"));
			            });
			        	}
			    		});
						</script>
		';
*/
		return $out;
	}
	
	function viewData()	{
		if (!empty($this->value)) {
			switch ($this->filetyp) {
			case 'img':
				if (!empty($this->width)) {
					$width = ' width="' . $this->width . '" ';
				} else {
					$width = ' width="150px" ';
				}
				$out = '<span class="img"><img src="' . $this->upload_dir . $this->value . '" ' . $width . ' /></span>';
				break;
			default:
				$out = '<span class="value">' . $this->value . '</span>';
				break;
			}
		} else {
			$out = '';
		}
		return $out;
	}

	public static function getDyntableCode($key, $row, $name, $val, $type, $upload_dir) {
		if (!empty($val)) {
			$value = ' value="' . $val . '" ';
			$filePath = $upload_dir . $val;
			if ($type=='image') {
				$preview = '<img src="' . $filePath . '" height="30px" alt="' . $val . '">';
			} else {
				$preview = '';
			}
			$link =  '<a href="' . $filePath . '" title="' . $val . '" target="_blank">' . 
									$val . '</a>';
			$res = $preview . $link;
			$res .= '<input class="' . $key . '" type="hidden" name="' . $name . '[' . $row . '][' . $key . ']" value="' . $val . '" />';
		} else {
			$res = '<input type="hidden" name="' . $name . '[' . $row . '][' . $key . ']" value="" />';
		}
		$res .= '<input class="' . $key . '" type="file" name="' . $name . '[' . $row . '][' . $key . ']"  />
						';
		return $res;
		
	}
	
	public static function getDyntableCodeNewElem($key, $name, $val) {
		return '<input class="' . $key . '" type="hidden" name="' . $name . '[###numRows###][' . $key . ']" value="" />' .
					 '<input class="' . $key . '" type="file" ' . 'name="' . $name . '[###numRows###][' . $key . ']"  />';
	}
		
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_file.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_file.php']);
}

?>
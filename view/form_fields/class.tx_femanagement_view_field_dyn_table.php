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
class tx_femanagement_view_field_dyn_table extends tx_femanagement_view_field {
protected $colTitles;
protected $colTypes;
protected $colData;
protected $value;
protected $colConfig;
protected $numRows;
protected $maxRows;
protected $staticRowNum;
protected $validateScript;

	public function __construct($elements) {
		parent::__construct($elements);
		if (!empty($elements['colTitles'])) {
			$this->colTitles = $elements['colTitles'];
		} else {
			$this->colTitles = array('title'=>'Titel');
		}
		if (!empty($elements['colTypes'])) {
			$this->colTypes = $elements['colTypes'];
		}
		if (!empty($elements['colData'])) {
			$this->colData = $elements['colData'];
		}
		if (!empty($elements['staticRowNum'])) {
			$this->staticRowNum = $elements['staticRowNum'];
			$this->maxRows = $elements['staticRowNum'];
			$this->numRows = $elements['staticRowNum'];
		} else {
			if (!empty($elements['numRows'])) {
				$this->numRows = $elements['numRows'];
			} else {
				$this->numRows = 3;
			}
			if (!empty($elements['maxRows'])) {
				$this->maxRows = $elements['maxRows'];
				if ($this->numRows>$this->maxRows) {
					$this->numRows = $this->maxRows;
				}
			}
		}
		if (!empty($elements['colConfig'])) {
			$this->colConfig = $elements['colConfig'];
		} else {
			$this->colConfig = '';
		}
		if (!empty($elements['validateScript'])) {
			$this->validateScript = $elements['validateScript'];
		} else {
			$this->validateScript = '';
		}
		$this->value = array();
		for ($i=0;$i<$this->numRows;$i++) {
			$row = array();
			foreach ($this->colTitles as $key=>$val) {
				$row[$key] = '';
			}
			$this->value[] = $row;
		}
	}
	
	function setValue($value)	{
		if (!is_array($value) || count($value)<1) {
			$this->value = array();
			for ($i=0;$i<$this->numRows;$i++) {
				$row = array();
				foreach ($this->colTitles as $key=>$val) {
					$row[$key] = '';
				}
				$this->value[] = $row;
			}
		} else {
			$this->value = $value;
		}
	}
	
	function getValue()	{
		return $this->value;
	}
	
	function getColTypes()	{
		return $this->colTypes;
	}
	
	function viewData()	{
		$out = '<div class="value">
					  <table class="dyn_table">
					 ';
		$out .= '<tr>' . "\n";
		foreach($this->colTitles as $title) {
			$out .= '<th>' . $title . '</th>' . "\n";
		}
		$out .= '</tr>' . "\n";
		
		$valuesNew = array();
		foreach($this->value as $rowValues) {
			$rowValueNew = array();
			foreach ($this->colTypes as $key=>$colType) {
				if (!isset($rowValues[$key]) && $colType=='label') {
					$rowValueNew[$key] = ''; 
				} else {
					$rowValueNew[$key] = $rowValues[$key];
				}
			}
			$valuesNew[] = $rowValueNew;
		}
		foreach($valuesNew as $rowIndex=>$rowData) {
			$out .= '<tr id="row_' . $rowIndex . '">' . "\n";
			foreach($rowData as $key=>$val) {
				if (empty($val) && isset($this->colData[$key])) {
					$val = $this->colData[$key];
				}
				if (isset($this->colTypes[$key])) {
					switch ($this->colTypes[$key]) {
						case 'label':
							$out .= '<td></td>' . "\n";
									break;
						case 'input':
							$out .= '<td>' .  $val. '</td>' . "\n";
							break;
						case 'hidden':
//							$out .= '<td>' . $val . '</td>';
							break;
						case 'readonly':
							$out .= '<td>' . $val . '</td>';
							break;
						case 'select':
							$out .= '<td>' . $this->colData[$key][$val] . '</td>';
							break;
						case 'checkbox':
							$out .= '<td>' . $val . '</td>';
							break;
						case 'image':
						case 'file':
							$out .= '<td><a href="' .  $this->upload_dir . $val . '" target="_blank" /></td>';
							break;
					}
				} else {
					$out .= '<td>' . $val . '</td>' . "\n";
				}
			}
			$out .= '</tr>' . "\n";
		}

		$out .= '</table>
							</div>';
		return $out;
	}
	
	function csvData()	{
		$titles = '"' . implode('";"',$this->colTitles) . '"' . "\n";
    $out = '';
		foreach($this->colTitles as $title) {
			$out .= '"' . $titles . '"' . "\n";
		}
		foreach($this->value as $rows) {
			$out .= '"' . implode('";"',$rows) . '"' . "\n";
		}
		return $out;
	}
	
	function editData()	{
		if ($this->readonly) {
			return $this->viewData();
		}
		$out = '<div class="value">
					  <table id="dyn_table_" data-name="' . $this->name . '" class="dyn_table "' . $this->name . '"><thead>
					 ';
		$out .= '<tr>' . "\n";
		foreach($this->colTitles as $key=>$title) {
			if ($this->colTypes[$key]!='hidden') {
				$out .= '<th>' . $title . '</th>' . "\n";
			} else {
				$out .= '<th class="hidden"></th>' . "\n";
			}
		}
		$out .= '<th class="actions"></th>' . "\n";
		$out .= '</tr></thead>
						 <tbody>' . "\n";
		$row = 0;
		$valuesNew = array();
		foreach($this->value as $rowValues) {
			$rowValueNew = array();
			foreach ($this->colTypes as $key=>$colType) {
				if (!isset($rowValues[$key]) && $colType=='label') {
					$rowValueNew[$key] = ''; 
				} else {
					$rowValueNew[$key] = $rowValues[$key];
				}
			}
			$valuesNew[] = $rowValueNew;
		}
		foreach($valuesNew as $rowData) {
			$out .= '<tr id="row_' . $row . '">' . "\n";
			foreach($rowData as $key=>$val) {
				if (empty($val) && isset($this->colData[$key])) {
					$val = $this->colData[$key];
				}
				if (isset($this->colTypes[$key])) {
					switch ($this->colTypes[$key]) {
					case 'label': 
						$out .= '<td><label data-title="' . $this->colTitles[$key] . '"' . 'class="' . $key . '">' . 
	            			 			$this->colTitles[$key] . ' ' . ($row+1) . '</label></td>' . "\n";
						break;
					case 'input': 
						$out .= '<td>' . tx_femanagement_view_field_text::getDyntableCode($key, $row, $this->name, $val). '</td>' . "\n";
						break;
					case 'hidden': 
						$out .= '<td>' . tx_femanagement_view_field_hidden::getDyntableCode($key, $row, $this->name, $val) . '</td>';
						break;
					case 'readonly': 
						$out .= '<td>' . tx_femanagement_view_field_readonly::getDyntableCode($key, $row, $this->name, $val) . '</td>';
						break;
					case 'select':
						$out .= '<td>' . tx_femanagement_view_field_select::getDyntableCode($key, $row, $this->name, $val, $this->colData[$key]) . '</td>';						
						break;
					case 'select_rowdata':
						$out .= '<td>' . tx_femanagement_view_field_ajax_select_rowdata::getDyntableCode($key, $row, $this->name, $val, $this->colData[$key]) . '</td>';
						break;
					case 'checkbox':
						$out .= '<td>' . tx_femanagement_view_field_checkbox::getDyntableCode($key, $row, $this->name, $val) . '</td>';
						break;
					case 'image':
					case 'file':
						$out .= '<td>' . tx_femanagement_view_field_file::getDyntableCode($key, $row, $this->name, $val, $this->colTypes[$key], $this->upload_dir) . '</td>';
						break;
					}				
				} else {
					$out .= '<td><input class="' . $key . '" ' .
									' type="text" class="' . $key . '"name="' . $this->name . 
											'[' . $row . '][' . $key . ']" value="' . $val . '"/>
											</td>' . "\n";
				}
			}
			$out .= '<td class="actions">';
			if (empty($this->staticRowNum)) {
				$out .= '<span class="icon-actions t3-icon-edit-delete delete_row_' . $this->name . '" title="Zeile Löschen" row="' . $row . '"></span>';
			}
			if ($row==(count($this->value)-1)) {
				$out .= '<span class="icon-actions t3-icon-move-down move_down_' . $this->name . ' hidden" title="Zeile nach unten verschieben" row="' . $row . '"></span>';
			} else {
				$out .= '<span class="icon-actions t3-icon-move-down move_down_' . $this->name . '" title="Zeile nach unten verschieben" row="' . $row . '"></span>';
			}
			if ($row==0) {
				$out .= '<span class="icon-actions t3-icon-move-up move_up_' . $this->name . ' hidden" title="Zeile nach oben verschieben" row="' . $row . '"></span>';
			} else {
				$out .= '<span class="icon-actions t3-icon-move-up move_up_' . $this->name . '" title="Zeile nach oben verschieben" row="' . $row . '"></span>';
			}
			
			$out .= '</td></tr>' . "\n";
			$row++;
		}
		$emptyRow = '';
		foreach($this->colTitles as $key=>$title) {
			if (isset($this->colData[$key])) {
				$val = ' value="' . $this->colData[$key] . '" ';
			} else {
				$val = '';
			}
			if (isset($this->colTypes[$key])) {
				switch ($this->colTypes[$key]) {
					case 'label': 
						$emptyRow .= '<td><label data-title="' . $this->colTitles[$key] . '"' . 'class="' . $key . '">' . 
	            			 			$this->colTitles[$key] . ' ' . ($row+1) . '</label></td>';
						break;
					case 'input': 
						$emptyRow .= '<td>' . tx_femanagement_view_field_text::getDyntableCodeNewElem($key, $this->name, $val) . '</td>';
						break;
					case 'hidden': 
						$emptyRow .= '<td>' . tx_femanagement_view_field_hidden::getDyntableCodeNewElem($key, $this->name, $val) . '</td>';
						break;
					case 'hidden': 
						$emptyRow .= '<td>' . tx_femanagement_view_field_readonly::getDyntableCodeNewElem($key, $this->name, $val) . '</td>';
						break;
					case 'select':
						$emptyRow .= '<td>' . tx_femanagement_view_field_select::getDyntableCodeNewElem($key, $this->name, $val, $this->colData[$key]) . '</td>';
						break;
					case 'select_rowdata':
						$out .= '<td>' . tx_femanagement_view_field_ajax_select_rowdata::getDyntableCodeNewElem($key, $row, $this->name, $val, $this->colData[$key]) . '</td>';
						break;
					case 'checkbox':
						$emptyRow .= '<td>' . tx_femanagement_view_field_checkbox::getDyntableCodeNewElem($key, $this->name, $val) . '</td>';
						break;
					case 'image':
					case 'file':
						$emptyRow .= '<td>' . tx_femanagement_view_field_file::getDyntableCodeNewElem($key, $this->name, $val) . '</td>';
						break;
				}				
			} else {
				$emptyRow .= '<td><input type="text" ' . $val .
           			 			' name="' . $this->name . '[###numRows###][' . $key . ']\" /></td>';
			}
		}
		$out .= '</tbody></table>';
		$out .= '<div>';
		if (empty($this->staticRowNum)) {
			$out .= '<input id="addElem_' . $this->name . '" type="button" class="addDyntableRow" value="' . $this->linkTitle . '">';
		}
		$out .= '</div>';
		$out .= '</div>';
		$colNames = implode(',',array_keys($this->colTitles));
		$out .= '	<script type="text/javascript">
				function renumberTable_' . $this->name . '(tableName){
				var table = $(".dyn_table." + tableName);
				var rows = $("tr:gt(0)",table);
				  rows.each(function(indexRows){
				  	$(this).attr("id","row_" + indexRows);
				  	var cols = $("input",this);
				  	cols.each(function(){
				  		var colName = $(this).attr("class");
				  		$(this).attr("name","' . $this->name . '[" + indexRows + "][" + colName + "]");
						});
				  	var label = $("label",this);
				  	label.each(function(){
				  		var title = $(this).attr("data-title");
				  		$(this).html(title + " " + (indexRows+1));
						});
						var actions = $(".t3-icon-move-down",this);
				  	actions.each(function(){
				  		$(this).removeClass("hidden");
				  		$(this).attr("row",indexRows);
				  		if (indexRows==(rows.length-1)) {
				  			$(this).addClass("hidden");
				  		}
						});
						var actions = $(".t3-icon-move-up",this);
				  	actions.each(function(){
				  		$(this).removeClass("hidden");
				  		$(this).attr("row",indexRows);
				  		if (indexRows==0) {
				  			$(this).addClass("hidden");
				  		}
						});
					});
				}
				function addTableRow_' . $this->name . '(table,cols){
				  var colNames = cols.split(",");
	        var numRows = $("tr", table).length-1;
	        var n = colNames.length;
	        var tds = "<tr id=\"row_" + numRows + "\">";
        	var newRow = \'' . $emptyRow . '\';
        	tds += newRow.replace(/###numRows###/g,numRows);
					tds += "<td class=\"action\">";
					';
					if (empty($this->staticRowNum)) {
						$out .= 'tds += "<span class=\"icon-actions t3-icon-edit-delete delete_row_' . $this->name . '\" title=\"Zeile Löschen\" row=\"" + numRows + "\"></span>";
						';
					}
					$out .= 'tds += "<span class=\"icon-actions t3-icon-move-down move_down_' . $this->name . '\" title=\"Zeile nach oben verschieben\" row=\"" + numRows + "\"></span>";
					tds += "<span class=\"icon-actions t3-icon-move-up move_up_' . $this->name . '\" title=\"Zeile nach oben verschieben\" row=\"" + numRows + "\"></span>";
					tds += "</td>";
	        tds += "</tr>";
	        if($("tbody", table).length > 0){
	            $("tbody", table).append(tds);
	        }else {
	            $(table).append(tds);
	        }
				}
				$("#addElem_' . $this->name . '" ).click(function(){
						addTableRow_' . $this->name . '($("#dyn_table_' . $this->name . '"),"' . $colNames . '");
						renumberTable_' . $this->name . '();
					});
				$("table.dyn_table").delegate(".delete_row","click", function(){
						var row = $(this).attr("row");
						$("table#dyn_table_' . $this->name . ' #row_" + row).remove();
						renumberTable_' . $this->name . '();
					});
				$("table#dyn_table_' . $this->name . '").delegate(".move_down_' . $this->name . '","click", function(){
						var rows = $("tr:gt(0)",$("#dyn_table_' . $this->name . '"));
						var row = parseInt($(this).attr("row"));
						if (row<rows.length-1) {
							var first = "table#dyn_table_' . $this->name . ' tr#row_" + (row+1);
							var second = "table#dyn_table_' . $this->name . ' tr#row_" + row;
							$(first).after($(second));
							renumberTable_' . $this->name . '();
						}
					});
				$("table#dyn_table_' . $this->name . '").delegate(".move_up_' . $this->name . '","click", function(){
						var row = parseInt($(this).attr("row"));
						if (row>0) {
							var first = "table#dyn_table_' . $this->name . ' tr#row_" + row;
							var second = "table#dyn_table_' . $this->name . ' tr#row_" + (row-1);
						$(first).after($(second));
							renumberTable_' . $this->name . '();
						}
					});
				</script>
		';
		if (!empty($this->validateScript)) {
			$out .= $this->validateScript;
		}
		return $out;
	}
	
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_dyn_table.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_dyn_table.php']);
}

?>
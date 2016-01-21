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
class tx_femanagement_view_field_time extends tx_femanagement_view_field {

protected $timeFormat = 'G:i';
protected $label_hour='Stunden';
protected $label_minute='Minuten';
protected $label_now='Jetzt';
protected $label_done='Schliessen';
protected $TIME_SELECTOR_TIME='Uhrzeit';
protected $TIME_SELECTOR_INSERT_TIME='Uhrzeit auswählen';
protected $TIMEPICKER_INTERVALL=15;
protected $TIMEPICKER_EARLIEST_HOUR=1;
protected $TIMEPICKER_LATEST_HOUR=23;
protected $TIMEPICKER_SHOW_CLOSE='true';
protected $TIMEPICKER_SHOW_NOW='true';
protected $TIMEPICKER_VERSION='js_timepicker';
static protected  $JS_INCLUDED = FALSE;
	
	public function __construct($elements) {
		if (!empty($elements['timeFormat'])) {
				$this->timeFormat = $elements['timeFormat'];
			}
			if (!empty($elements['label_hour'])) {
				$this->label_hour = $elements['label_hour'];
			}
			if (!empty($elements['label_minute'])) {
				$this->label_minute = $elements['label_minute'];
			}
			if (!empty($elements['label_now'])) {
				$this->label_now = $elements['label_now'];
			}
			if (!empty($elements['label_done'])) {
				$this->label_done = $elements['label_done'];
			}
			if (!empty($elements['TIMEPICKER_INTERVALL'])) {
				$this->TIMEPICKER_INTERVALL = $elements['TIMEPICKER_INTERVALL'];
			}
			if (!empty($elements['TIMEPICKER_SHOW_NOW'])) {
				$this->TIMEPICKER_SHOW_NOW = $elements['TIMEPICKER_SHOW_NOW'];
			}
			if (!empty($elements['TIMEPICKER_SHOW_CLOSE'])) {
				$this->TIMEPICKER_SHOW_CLOSE = $elements['TIMEPICKER_SHOW_CLOSE'];
			}
			if (!empty($elements['TIME_SELECTOR_INSERT_TIME'])) {
				$this->TIME_SELECTOR_INSERT_TIME = $elements['TIME_SELECTOR_INSERT_TIME'];
			}
			if (!empty($elements['TIME_SELECTOR_TIME'])) {
				$this->TIME_SELECTOR_TIME = $elements['TIME_SELECTOR_TIME'];
			}
			if (!empty($elements['TIMEPICKER_VERSION'])) {
				$this->TIMEPICKER_VERSION = $elements['TIMEPICKER_VERSION'];
			}
			if (self::$JS_INCLUDED == FALSE) {
				if ($this->TIMEPICKER_VERSION=='slider') {
					$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
						<script src="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/trentrichardson-Timepicker/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
						<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/trentrichardson-Timepicker/jquery-ui-timepicker-addon.css"/>
						';
				} else {
					$GLOBALS['TSFE']->additionalHeaderData['femanagement'] .= '
						<script src="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/js_timepicker/jquery.ui.timepicker.js" type="text/javascript"></script>
						<link rel="stylesheet" type="text/css" href="' . t3lib_extMgm::siteRelPath('fe_management') . 'res/js_timepicker/jquery.ui.timepicker.css"/>
						';
					
				}
				self::$JS_INCLUDED = TRUE;
			}
		parent::__construct($elements);
	}
	
	function setValue($value) {
		if (!empty($value)) {
			$stunden = $value / 3600;
			$minuten = ($value % 3600) / 60;
			$this->value = sprintf("%02d:%02d",$stunden,$minuten);
		}
	}
	
	function getValue()	{
		if (!empty($this->value)) {
			$zeitDaten = explode(':',$this->value);
			if (count($zeitDaten)==2) {
				return ($zeitDaten[0] * 3600 + $zeitDaten[1] * 60);
			}
		}
		return 0;
	}

	function convertPostParameter($value) {
		if (!empty($value)) {
			$zeitDaten = explode(':',$value);
			if (count($zeitDaten)==2) {
				return ($zeitDaten[0] * 3600 + $zeitDaten[1] * 60);
			}
		}
		return 0;
	}
/*	
	function setValue($value) {
		if (!empty($value)) {
			$valueNew = date($this->timeFormat,$value);
			$this->value = $valueNew;
		} else {
			$value = $this->prefill;
		}
	}
	
	function getValue()	{
		if (!empty($this->value)) {
			$date = DateTime::createFromFormat($this->timeFormat, $this->value);
			return $date->getTimestamp();
		} else {
			return 0;
		}
	}

	function convertPostParameter($value) {
		if (!empty($value)) {
			$date = DateTime::createFromFormat($this->timeFormat, $value);
			return $date->getTimestamp();
		} else {
			return 0;
		}
	}
*/	
	function editData()	{
		$validateClass = $this->createValidation('time');
		if (!empty($this->value)) {
			$value = ' value="' . $this->value . '" ';
		} else {
			$value = '';
		}
		$out = '';
		if (isset($this->icons['delete'])) {
			$out .= '<span class="icon-actions t3-icon-edit-delete empty-field-val" title="Zeitfeld löschen" id="empty_' . $this->name . '"></span>';
		}
		$out .= '<input id="' . $this->name . '" name="' . $this->name . '"' . 
			 ' type="text" ' . $value . $validateClass . '/>
			 ';
		$out .= '<script type="text/javascript">
							$("#empty_' . $this->name . '").click(function() {
								$("#' . $this->name . '").attr("value","");
							}); 
						$("#' . $this->name . '").timepicker({
					  hours: { starts: '. $this->TIMEPICKER_EARLIEST_HOUR .', 
					  ends: '. $this->TIMEPICKER_LATEST_HOUR .' },
					  minutes: { interval: ' . $this->TIMEPICKER_INTERVALL . ' },
					  rows: 2,
					  showPeriodLabels: 0,
					  showNowButton: '. $this->TIMEPICKER_SHOW_NOW .',
					  showCloseButton: '. $this->TIMEPICKER_SHOW_CLOSE .',
					  hourText: "'. $this->label_hour . '",
			      minuteText: "'. $this->label_minute . '",
			      closeButtonText: "' . $this->label_done . '",
			      nowButtonText: "' . $this->label_now . '",
						stepMinute: ' . $this->TIMEPICKER_INTERVALL . ',
						timeFormat: "hh:mm",
						timeOnlyTitle: "' . $this->TIME_SELECTOR_INSERT_TIME . '",
						timeText: "' . $this->TIME_SELECTOR_TIME.'",
						hourText: "' . $this->label_hour . '",
						minuteText: "' . $this->label_minute . '",
						currentText: "' . $this->label_now . '", 
						closeText: "' . $this->label_done . '"
				});
			 </script>
			 ';
	
		return $out;
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_time.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/class.tx_femanagement_view_field_time.php']);
}

?>
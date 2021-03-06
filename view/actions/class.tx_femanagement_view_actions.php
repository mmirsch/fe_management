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

require_once(t3lib_extMgm::extPath('fe_management').'view/actions/class.tx_femanagement_view_actions_onclick.php');

class tx_femanagement_view_actions {
protected $class;
protected $name;
protected $title;
protected $link;
protected $events;

	public function __construct($name='',$title='',$link='',$events='',$class='button') {
		$this->class = $class;
		$this->name = $name;
		$this->title = $title;
		$this->link = $link;
		if (empty($events)) {
//			$event = t3lib_div::makeinstance('tx_femanagement_view_actions_onclick');
//			$this->events = array($event);
		} else {
			if (is_array($events)) {
				$this->events = $events;
			} else {
				$this->events = array($events);
			}
		}
	}
	
	function getName() {
		return 'fe_management_action_' . $this->name;
	}
	
	function show($aktuelleSeite='') {
		$cssClass = $this->class;
		if ($aktuelleSeite==$this->name) {
			$cssClass .=  ' active';
		}
		$out = '<a id="' . $this->getName() . '" class="' . $cssClass . '" ' .
					 'href="' . $this->link . '" >' .
					 $this->title . 
					 '</a>' .
					 $this->getJs();
		return $out;
	}
	
	function getJs() {
		$out = '';
		if (is_array($this->events) && count($this->events)>0) {
			$out = '<script type="text/javascript">
			';
			foreach ($this->events as $event) {
				$out .= $event->getEventJs($this->getName(),$this->link,$this->title);
			}
			$out .= '</script>';
		}
		return $out;
	}
}	

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/actions/class.tx_femanagement_view_action.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/actions/class.tx_femanagement_view_action.php']);
}

?>
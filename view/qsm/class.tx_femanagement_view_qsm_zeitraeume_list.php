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
class tx_femanagement_view_qsm_zeitraeume_list extends tx_femanagement_view_form_list {

	function __construct(&$piBase='',$pid='',$title='',$wrapClass='',$eidUrl='') {
		parent::__construct($piBase,$pid,$title,$wrapClass,$eidUrl);
	}
	
	function showListItem($elem,$fieldList,$permissions,$rowClass='') {
		$out = '<tr class="' . $rowClass . '">';
		foreach ($fieldList as $key=>$field) {
			switch ($key) {
			case 'title':
				$out .= '<td class="title">';
				$out .= $elem['title'];
				$out .= '</td>';
				break;
			case 'start':
				$out .= '<td class="datum">';
				$out .= date('d.m.Y', $elem['start']);
				$out .= '</td>';
				break;
			case 'ende':
				$out .= '<td class="datum">';
				$out .= date('d.m.Y', $elem['ende']);
				$out .= '</td>';
				break;
			}
		}
		$out .= '<td class="actions">';
		$out .= $this->showActions($elem,$permissions);
		$out .= '</td>';
		$out .= '</tr>';
		return $out;
	}
	
	function ajaxFilter($data) {
		$this->initAjaxFilter($data);
		
		if (isset($data['tx_femanagement']['page'])) {
			$page = $data['tx_femanagement']['page'];
		} else {
			$page = '';
		}
		$configArray = array();
		$configArray['sqlFilter'] = 'TRUE';
		$configArray['fields'] = 'uid,title,start,ende,deleted,hidden';
		if (isset($this->args['suche'])) {
			$configArray['sqlFilter'] .= ' AND title LIKE "%' . $this->args['suche'] . '%"';
		}
		if (isset($this->args['hidden'])) {
			$configArray['hidden'] = $this->args['hidden'];
		}
		if (isset($this->args['deleted'])) {
			$configArray['deleted'] = $this->args['deleted'];
		}
		if (!empty($this->args['sortField'])) {
			if (!empty($this->args['sortMode'])) {
				$sortMode = $this->args['sortMode'];
			} else {
				$sortMode = 'ASC';
			}
			switch ($this->args['sortField']) {
				case 'fe_cruser_id':
					$sortField = 'fe_users.last_name';
					break;
				default:
					$sortField = 'tt_news.' . $this->args['sortField'];
				break;
			}
			$configArray['orderBy'] = $sortField . ' ' . $sortMode;
		} else {
			$configArray['orderBy'] = 'start ASC';
		}
		
		$out .= $this->exitAjaxFilter($configArray);
		$daten = $this->model->selectData($configArray);
		foreach ($daten as $index=>$elem) {
			$daten[$index]['permissions'] = $this->getPermissions($elem,$page);
		}
		$out .= $this->createDataList($daten);
		return $out;
	}

	function getNewelemButtonTitle() {
		return 'Neuen Zeitraum anlegen'; 
	}

}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_zeitraeume_list.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/view/qsm/class.tx_femanagement_view_qsm_zeitraeume_list.php']);
}

?>
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

class tx_femanagement_controller_permissions_main extends tx_femanagement_controller {
	function __construct(&$piBase='',&$params='') {		
		parent::__construct($piBase,$params);
	}

	function showListView($aktuelleSeite) {
		$groups = $this->formView->createMenuEntry('Berechtigungen','groups');
		$roles = $this->formView->createMenuEntry('Rollen','roles');
		$domains = $this->formView->createMenuEntry('Bereiche','domains');
		$menu = array($groups,$roles,$domains);
		$this->formView->setMenu($menu,'menu_top');
		return parent::showListView($aktuelleSeite);
	}
	
	public function isMember($application,$role,$domain=0) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups');
		$usergroups = explode(',',$GLOBALS['TSFE']->fe_user->user['usergroup']);		
		$groupsRecursive = array();
		$model->getFeGroupsListRecursive($usergroups,$groupsRecursive);
		return $model->isMember($application,$role,$domain,$groupsRecursive);
	}
	
	public function isSuperAdmin() {
		$user = $GLOBALS['TSFE']->fe_user->user[username];
		return $user=='mmirsch';
	}
	
	public function isAdmin($application,$domain=0) {
		if ($this->isSuperAdmin()) {
			return TRUE;
		}
		return $this->isMember($application,'Admin',$domain);
	}

	function isApplicationAdmin($application,$domain= -1) {
		return $this->isMember($application,'Admin',$domain);
	}
		
	public function isEditor($application,$domain=0) {
		return $this->isMember($application,'Redakteur',$domain);
	}
	
	public function isReviser($application,$domain=0) {
		return $this->isMember($application,'Bearbeiter',$domain);
	}
	
	public function isOwner($application,$elem,$ownerField='fe_cruser_id') {
		$userId = $GLOBALS['TSFE']->fe_user->user['uid'];
		return $elem[$ownerField]==$userId;
	}
	
	public function getAdmins($application,$domain=0) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups');
		return $model->getMembers($application,'Admin',$domain);
	}
	
	public function getMembers($application,$role,$domain=0) {
		$model = t3lib_div::makeInstance('tx_femanagement_model_permissions_groups');
		return $model->getMembers($application,$role,$domain);
	}
	
	function saveFormData(&$formData,$uid,$hidden) {
		if (!empty($uid)) {
			$res = $this->model->updateDbEntry($formData,$uid,'hidden',0);
		} else {
			$res = $this->model->insertDbEntry($formData,'hidden',0);
		}
		return $res;
	}
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/permissions/class.tx_femanagement_controller_permissions_main.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/class.tx_femanagement_controller_permissions_main.php']);
}

?>
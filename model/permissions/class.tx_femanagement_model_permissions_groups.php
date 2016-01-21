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

class tx_femanagement_model_permissions_groups	extends tx_femanagement_model {

	function __construct(&$piBase='',$storagePid=0) {
		parent::__construct($piBase,$storagePid,'tx_fe_management_permissions_groups');
	}

	function initFormFields() {
 		$this->formFields = array(
				'title' => 'title',
 				'description' => 'description',
				'application' => 'application',
				'role' => 'role',
				'domain' => 'domain',
 				'usergroup' => 'usergroup',
			);
	}
	
	
/*
 * ########################## LISTS ##########################
 */	
	
	function getList($eingabe,$pid,$limit='') {
		return parent::getList($eingabe,$pid,$limit,'title');
	}
	
	function getFeGroupsList() {
		return parent::getList('','all','','title','','','fe_groups');
	}

function getFegroupsTitles($groupsList) {
		$titles = array();
		foreach ($groupsList as $groupId) {
			$titles[] = $this->getTitle($groupId);
		}
		return $titles;
	}

	function getTitle($groupId) {
		$configArray = array();
		$configArray['table'] = 'fe_groups';
		$configArray['fields'] = 'title';
		$configArray['sqlFilter'] = 'uid="' . $groupId . '"';
		$configArray['all_pids'] = TRUE;
		$data = parent::selectData($configArray);
		return $data[0]['title'];
	}
	
	function getRoles($application) {
		$configArray['fields'] = 'uid,usergroup,role';
		$configArray['sqlFilter'] = 'application="' . $application . '"';
		$configArray['all_pids'] = TRUE;
		$data = $this->selectData($configArray);		
	}

	function isMember($application,$role,$domain,$usergroups) {
		$roleModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_roles');
		$roleId = $roleModel->getRoleId($role);
		$configArray['fields'] = 'usergroup';
		$configArray['sqlFilter'] = 'application="' . $application . 
															 '" AND role=' . $roleId;
		if ($domain!='-1') {
			$configArray['sqlFilter'] .= ' AND domain=' . $domain;
		}
		$configArray['all_pids'] = TRUE;
		$data = $this->selectData($configArray);	
		$groups = array();
		
/*
t3lib_div::devlog("configArray","testPermissions",0,$configArray);
t3lib_div::devlog("usergroups","testPermissions",0,$usergroups);
t3lib_div::devlog("data","testPermissions",0,$data);
*/
		
		if (count($data)>0) {
			foreach ($data as $usergroup) {
				$grouplist = explode(',',$usergroup['usergroup']);
				$groups = array_merge($groups, $grouplist);
			}
			foreach($usergroups as $group) {
				if (in_array($group,$groups)) {
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	function getGroupMembers($userGroups) {
		$userGroupList = array();
		$this->getFeGroupsListRecursive($userGroups,$userGroupList);
		$memberList = array();
		foreach ($userGroupList as $group) {
			$configArray['table'] = 'fe_users';
			$configArray['fields'] = 'username,email,name';
			$configArray['hiddenFieldName'] = 'disable';
			$configArray['sqlFilter'] = 'FIND_IN_SET(' .  $group . ',usergroup)';
			$configArray['all_pids'] = TRUE;
			$data = $this->selectData($configArray);
			if (count($data)>0) {
				foreach($data as $entry) {
					$memberList[$entry['username']] = array('name'=>$entry['name'],'email'=>$entry['email']);
				}
			}
		}
		return $memberList;
	}
	
	function getMembers($application,$role,$domain) {
		$roleModel = t3lib_div::makeInstance('tx_femanagement_model_permissions_roles');
		$roleId = $roleModel->getRoleId($role);
		$configArray['fields'] = 'usergroup';
		$configArray['sqlFilter'] = 'application="' . $application . 
															 '" AND role=' . $roleId  . 
															 ' AND domain=' . $domain;
		$configArray['all_pids'] = TRUE;
		$data = $this->selectData($configArray);	
		$memberList = array();	
		if (count($data)>0) {
			$userGroups = explode(',',$data[0]['usergroup']);
			$members = $this->getGroupMembers($userGroups);
			$memberList = array_merge($memberList,$members);
		}
		return $memberList;
	}
	
	function getFeGroupsListRecursive($groupsSearch,&$groupsFound,$level=0) {
		$groupsFound = array_merge($groupsFound,$groupsSearch);		
		foreach($groupsSearch as $group) {
			$configArray['table'] = 'fe_groups';
			$configArray['fields'] = 'subgroup';
			$configArray['sqlFilter'] = 'uid=' . $group;
			$configArray['all_pids'] = TRUE;
			$data = $this->selectData($configArray);				
			if (count($data)>0) {
				$subgroups = explode(',',$data[0]['subgroup']);
				foreach($subgroups as $subgroup) {
					if (!empty($subgroup)) {
						if (!in_array($subgroup,$groupsFound)) {
							$groupsFound[] = $subgroup;
							$this->getFeGroupsListRecursive(array($subgroup),$groupsFound,$level+1);
						}
					}
				}
			}
		}
		$groupsFound = array_unique($groupsFound);
	}
	
}
?>

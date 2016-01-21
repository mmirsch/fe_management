<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_femanagement_pi1.php', '_pi1', 'list_type', 0);
$TYPO3_CONF_VARS['FE']['eID_include']['fe_management'] = 'EXT:fe_management/eid/class.tx_femanagement_eid.php';

require_once(t3lib_extMgm::extPath($_EXTKEY).'hooks/class.tx_femanagement_powermail.php');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MainContentHookAfter'][]= 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitBeforeMarkerHook'][] = 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitEmailHook'][] = 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MandatoryHookBefore'][] = 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_MandatoryHook'][] = 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitLastOne'][] = 'EXT:fe_management/hooks/class.tx_femanagement_powermail.php:tx_femanagement_powermail';

?>
<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_femanagement_news_cat_admin' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tt_news_cat.tx_femanagement_news_cat_admin',		
		'config' => array (
			'type' => 'select',
			'size' => 20,
			'maxitems' => 20,
			'items' => Array (
				array('LLL:EXT:lang/locallang_general.php:LGL.hide_at_login', -1),
				array('LLL:EXT:lang/locallang_general.php:LGL.any_login', -2),
				array('LLL:EXT:lang/locallang_general.php:LGL.usergroups', '--div--')
			),
			'exclusiveKeys' => '-1,-2',
			'foreign_table' => 'fe_groups'
		)
	),
);
t3lib_div::loadTCA('tt_news_cat');
t3lib_extMgm::addTCAcolumns('tt_news_cat',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_news_cat','tx_femanagement_news_cat_admin;;;;1-1-1');


$tempColumns = array (
	'tx_femanagement_cal_cat_admin' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_cal_category.tx_femanagement_cal_cat_admin',		
		'config' => array (
			'type' => 'select',
			'size' => 20,
			'maxitems' => 20,
			'items' => Array (
				array('LLL:EXT:lang/locallang_general.php:LGL.hide_at_login', -1),
				array('LLL:EXT:lang/locallang_general.php:LGL.any_login', -2),
				array('LLL:EXT:lang/locallang_general.php:LGL.usergroups', '--div--')
			),
			'exclusiveKeys' => '-1,-2',
			'foreign_table' => 'fe_groups'
		)
	),
);
t3lib_div::loadTCA('tx_cal_category');
t3lib_extMgm::addTCAcolumns('tx_cal_category',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_cal_category','tx_femanagement_cal_cat_admin;;;;1-1-1');


$tempColumns = array (
	'tx_femanagement_news_associated_group' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tt_news_cat.tx_femanagement_news_associated_group',		
		'config' => array (
			'type' => 'select',
			'size' => 20,
			'maxitems' => 20,
			'items' => Array (
				array('LLL:EXT:lang/locallang_general.php:LGL.hide_at_login', -1),
				array('LLL:EXT:lang/locallang_general.php:LGL.any_login', -2),
				array('LLL:EXT:lang/locallang_general.php:LGL.usergroups', '--div--')
			),
			'exclusiveKeys' => '-1,-2',
			'foreign_table' => 'fe_groups'
		)
	),
);
t3lib_div::loadTCA('tt_news_cat');
t3lib_extMgm::addTCAcolumns('tt_news_cat',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_news_cat','tx_femanagement_news_associated_group;;;;1-1-1');


$tempColumns = array (
	'tx_femanagement_cal_associated_group' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_cal_category.tx_femanagement_cal_associated_group',		
		'config' => array (
			'type' => 'select',
			'size' => 20,
			'maxitems' => 20,
			'items' => Array (
				array('LLL:EXT:lang/locallang_general.php:LGL.hide_at_login', -1),
				array('LLL:EXT:lang/locallang_general.php:LGL.any_login', -2),
				array('LLL:EXT:lang/locallang_general.php:LGL.usergroups', '--div--')
			),
			'exclusiveKeys' => '-1,-2',
			'foreign_table' => 'fe_groups'
		)
	),
);
t3lib_div::loadTCA('tx_cal_category');
t3lib_extMgm::addTCAcolumns('tx_cal_category',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_cal_category','tx_femanagement_cal_associated_group;;;;1-1-1');

$tempColumns = array (
	'title' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_femanagement_forschungsprojekte.title',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'required',
		)
	),
	'description_short' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_femanagement_forschungsprojekte.description_short',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'required',
		)
	),
	'description_long' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_femanagement_forschungsprojekte.description_long',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'required',
		)
	),
	'faculty' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_femanagement_forschungsprojekte.faculty',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'required',
		)
	),
	'link_to_fac' => array (		
		'exclude' => 0,
		'label' => 'LLL:EXT:fe_management/locallang_db.xml:tx_femanagement_forschungsprojekte.link_to_fac',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',	
			'eval' => 'required',
		)
	),
);

t3lib_div::loadTCA('tx_femanagement_forschungsprojekte');
t3lib_extMgm::addTCAcolumns('tx_femanagement_forschungsprojekte',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tx_femanagement_forschungsprojekte','title;;;;1-1-1');

t3lib_div::loadTCA('tx_femanagement_forschungsprojekte');
$TCA['tx_femanagement_forschungsprojekte'] = array (
	'ctrl' => array (
		'title'     => 'Titel des Forschungsprojekt',
		'description_short'  => 'kurze Beschreibung',
		'description_long' => 'ausführliche Beschreibung',
		'faculty' => 'Fakultät',
		'link_to_fac' => 'Link zur Fakultät',
	),
);

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:fe_management/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'fe_management.png'
),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,'static//', '');
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] ='pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY . '/flexform.xml');
?>
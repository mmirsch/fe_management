<?php

require_once(PATH_t3lib . 'class.t3lib_page.php');
require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(PATH_tslib . 'class.tslib_content.php');
require_once(PATH_t3lib . 'class.t3lib_stdgraphic.php');
require_once(PATH_tslib . 'class.tslib_gifbuilder.php');


class tx_femanagement_eid extends tslib_pibase {
	
  function main() {
		if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');


	  $GLOBALS['TSFE']->tmpl = t3lib_div::makeInstance('t3lib_tstemplate');
	  $GLOBALS['TSFE']->tmpl->init();
	  $GLOBALS['TSFE']->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
//	  $GLOBALS['TSFE']->getConfigArray();

  	$GLOBALS['TSFE']->fe_user = tslib_eidtools::initFeUser(); 	# Initialize FE user object

    tslib_eidtools::connectDB(); 				# Connect to database
    $username = $GLOBALS['TSFE']->fe_user->user[username];
/*		
    if (empty($username)) {
    	exit ("Zugriff nur für eingeloggte User");
    }
 */
    $view = t3lib_div::_GP('view');
    $methode = t3lib_div::_GP('methode');
    $args = t3lib_div::_GP('args');
    
/* Systemordner der Daten */    
    $pid = t3lib_div::_GP('pid');
    
/* Aktuelle Seiten-Id */
    $pageId = t3lib_div::_GP('id');

/* Return Type */
    $type = t3lib_div::_GP('type');

/* Parameter */
    $get = t3lib_div::_GET();
    $post = t3lib_div::_POST();
   

$post = t3lib_div::_POST();
$get = t3lib_div::_GET();

/*
t3lib_div::devLog('post', 'fe_management', 0,$post);
t3lib_div::devLog('get', 'fe_manaegment', 0,$get);
*/

 	
    if (empty($view)) {
    	exit('Keine View übergeben!');
    }
    if (empty($methode)) {
    	exit('Keine Action übergeben!');
    }
    $viewObject = t3lib_div::makeInstance($view);
    if ($methode=='session_data') {
 	   	if (empty($username)) {
  	  	exit ("Zugriff nur für eingeloggte User");
    	}
    	$erg = $viewObject->$methode($args,$get['ctrl']);
    } else {
    	$data = array();
			$eidUrl = 'index.php?eID=' . t3lib_div::_GP('eID');
			$eidUrl .= '&view=' . t3lib_div::_GP('view');
	    $viewObject->setEidUrl($eidUrl);
			foreach ($get as $key=>$value) {
    		if ($key!='view' &&
    				$key!='methode' &&
    				$key!='type') {
    			$data[$key] = $value;
    		}
    	}
//t3lib_div::devlog("viewObject: $viewObject","fe_management",0);
//t3lib_div::devlog("methode: $methode","fe_management",0);
//t3lib_div::devlog("data","fe_management",0,$data);

    	$erg = $viewObject->$methode($data);
    }
    switch ($type) {
    case 'json': 
    	$this->returnJsonData($erg);
    	break;
    default:
    	$this->returnTextData($erg);
    	break;
    }
  }
  
  function returnJsonData($data) {
		header('Content-type: application/json');
		print json_encode($data);
		exit();  	
  }
  
  function returnTextData($data) {
		header('Content-Type: text/html; charset=utf-8');
		print($data);
		exit();  	
  }
}

$output = t3lib_div::makeInstance('tx_femanagement_eid');
$output->main();

?>
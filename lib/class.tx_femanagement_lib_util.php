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

class tx_femanagement_lib_util  {
	
  public static function clearPageCacheContent_pidList($pidList) {
  	if (TYPO3_UseCachingFramework) {
  		$pageIds = t3lib_div::trimExplode(',', $pidList);
  		$pageCache = t3lib_div::makeInstance('t3lib_cache_Manager');
  		try {
  			$pageCache = $GLOBALS['typo3CacheManager']->getCache(
  					'cache_pages'
  			);
  		} catch(t3lib_cache_exception_NoSuchCache $e) {
  			t3lib_cache::initPageCache();
  		
  			$pageCache = $GLOBALS['typo3CacheManager']->getCache(
  					'cache_pages'
  			);
  		}
  		foreach ($pageIds as $pageId) {
  			$pageCache->flushByTag('pageId_' . (int) $pageId);
  		}
  	} else {
  		$GLOBALS['TYPO3_DB']->exec_DELETEquery('cache_pages', 'page_id IN ('.$GLOBALS['TYPO3_DB']->cleanIntList($pidList).')');
  	}
  	
  }
  
  static public function getSessionData($pageId,$key='') {
  	$sessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
  	if (empty($key)) {
  		return $sessionData[$pageId];
  	} else {
  		$keyList = explode(',',$key);
  		if (count($keyList)==1) {
  			return $sessionData[$pageId][$key];
  		} else {
  			$evalString = '$data = $sessionData['. $pageId . ']["' . implode('"]["',$keyList) . '"];';
  			eval($evalString);
  			return $data;
  		}
  	}
  }
  
  static public function storeSessionData($pageId,&$data,$key='') {
  	$sessionData = unserialize($GLOBALS['TSFE']->fe_user->getKey('user','fe_management'));
  	if (empty($key)) {
  		$sessionData[$pageId] = $data;
  	} else {
  		$keyList = explode(',',$key);
  		if (count($keyList)==1) {
  			$sessionData[$pageId][$key] = $data;
  		} else {
  			$evalString = '$sessionData[$pageId]["' . implode('"]["',$keyList) . '"] = $data;';
  			eval($evalString);
  		}
  	}
  	$GLOBALS['TSFE']->fe_user->setKey('user','fe_management',serialize($sessionData));
  	$GLOBALS['TSFE']->fe_user->storeSessionData();  	
  }
  
  static public function getPageConfig($key='',$debug=FALSE) {
  	if (empty($key)) {
  		$data = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.'];
  	} else {
  		$keyList = explode(',',$key);
  		if (count($keyList)==1) {
  			$data = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.'][$key];
  		} else {
  			$data = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_femanagement_pi1.'];
  			foreach ($keyList as $field) {
  				$data = $data[$field];
  			}
  		}
  	}
  	return $data;
  }

  static public function getFieldList($fieldList) {
  	$fieldConfig = array();
  	foreach($fieldList as $key=>$title) {
  		$fieldConfig[trim($key)] = trim($title);
  	}
	  return $fieldConfig;
  }

  static public function createJpgImage($src, $width, $height=0, $quality=80) {
    $tsfe = t3lib_div::makeInstance('tslib_fe', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
    $tsfe->initTemplate();
    $GLOBALS['TSFE']->tmpl = $tsfe->tmpl;

    if (empty($height)) {
	    $imageSize = getimagesize($src);
	    if ($imageSize !== FALSE) {
		    $height = intval($width*$imageSize[1]/$imageSize[0]);
	    }
    }

    $cObj = new tslib_cObj();
    $conf = array(
      'file' => $src,
      'file.' => array(
       'width' => $width,
         'ext' => 'jpg',
        'params' => ' -quality ' . $quality,
      )
    );
	  if (!empty($height)) {
		  $conf['file.']{'height'} = $height;
	  } else {
		  $conf['file.']{'MaxH'} = 400;
	  }

    $imgResource = $cObj->getImgResource($conf['file'], $conf['file.']);
    return $imgResource[3];

  }


}

?>
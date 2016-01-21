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

class tx_femanagement_controller_lib_email {
protected $from = array();
protected $to = array();
protected $subject;
protected $bodyPlain;
protected $bodyHtml;
protected $attachments = array();
protected $mail;

	public function __construct() {		
		$this->mail = t3lib_div::makeInstance('t3lib_mail_Message');
	}
		
	public function setFrom($email,$name) {
		$this->from = array($email=>$name);
	}

	public function setTo($email,$name) {
		$this->to = array($email=>$name);
	}

	public function addTo($email,$name) {
		if (!array_key_exists($email,$this->to)) {
			$this->to[$email] = $name;
		}
	}

	public function setSubject($subject) {
		$this->subject = $subject;
	}

	public function setBodyHtml($body) {
		$this->bodyHtml = $body;
	}

	public function setBodyPlain($body) {
		$this->bodyPlain = $body;
	}

	public function addAttachment($filename) {
		if (!in_array($filename,$this->attachments)) {
			$this->attachments[] = $filename;
		}
	}

	public function sendEmail() {
		if (count($this->from)<1 ||
				count($this->to)<1 ||
				empty($this->subject) ||
				empty($this->bodyHtml)
				) {
					t3lib_div::devlog('Nicht alle E-Mail-Werte gesetzt','fe_managment',0,$this);
					return 0; 
		}
		if (empty($this->bodyPlain)) {
	    $this->bodyPlain = preg_replace('/(<br>|<br \/>|<br\/>)\s*/i', PHP_EOL, $this->bodyHtml);
			$this->bodyPlain = strip_tags($this->bodyPlain);
		}
		$this->mail->setFrom($this->from);
		$this->mail->setTo($this->to);
		$this->mail->setSubject($this->subject);
		$htmlComplete = $this->initHtml() . 
										$this->bodyHtml . 
										$this->exitHtml();
		$this->mail->setBody($htmlComplete, 'text/html');
		$this->mail->addPart($this->bodyPlain, 'text/plain');
		if (count($this->attachments)>0) {
			foreach($this->attachments as $filename) {
				$this->mail->attach(Swift_Attachment::fromPath($filename));
			}
		}
		$erg = $this->mail->send();
		if (!$erg) {
			$failedRecipients = $this->mail->getFailedRecipients();
			t3lib_div::devlog('E-Mail-Versand fehlgeschlagen!','fe_managment',0,$failedRecipients);
		}
		return $erg;
	}

	function initHtml() {
		$cidLogo = $this->mail->embed(Swift_Image::fromPath('fileadmin/images/banner/logo.png'));
		$logo = '<div id="header" style="padding: 20px;"><img width="200" height="46" src="' . $cidLogo . '"></div>';
		
		return '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Kalendertermine</title>
</head>
<body >
' . $logo . '

<div id="content" 
	style="font-family: verdana, arial, helvetica, sans-serif; padding: 0 20px; font-size:80%;">
		'; 
	}

	function exitHtml() {
		return '
</div>
</body>
</html>
		';
	}
	
}
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/email/class.tx_femanagement_controller_lib_email.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/fe_management/controller/email/class.tx_femanagement_controller_lib_email.php']);
}

?>
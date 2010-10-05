<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Dimitri König <dk@cabag.ch>
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
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_typo3 . 'interfaces/interface.backend_toolbaritem.php');

/**
 * Adds 'Login As' Icon to toolbar menu
 *
 * $Id: $
 *
 * @author	Dimitri König <dk@cabag.ch>
 * @package	TYPO3
 * @subpackage	loginas
 */
class tx_loginas implements backend_toolbarItem {
	/**
	 * Reference to the TYPO3 backend object
	 *
	 * @var TYPO3backend
	 */
	protected $backendReference;

	/**
	 * Email address of current backend user
	 *
	 * @var string
	 */
	protected $currentBackendUserEmailAddress;

	/**
	 * Array of users with the same email address as the current logged in backend user
	 *
	 * @var array
	 */
	protected $users = array();

	/**
	 * Extension key of this extension
	 *
	 * @var string
	 */
	protected $EXTKEY = 'loginas';

	/**
	 * Gets users with the same email address as the current logged in backend user
	 *
	 * @param	string		$backendReference: Reference to the TYPO3 backend object
	 * @return	void
	 */
	public function __construct(TYPO3backend &$backendReference = null) {
		$this->backendReference = $backendReference;

		$this->includeExtensionLanguageFile();
		$this->setCurrentBackendUserEmailAddress();
		$this->findFeUsersWithSameEmailAddress();
	}

	/**
	 * Includes locallang file from this extension
	 *
	 * @return	void
	 */
	protected function includeExtensionLanguageFile() {
		$GLOBALS['LANG']->includeLLFile('EXT:loginas/locallang_db.xml');
	}

	/**
	 * Includes locallang file from this extension
	 *
	 * @return	void
	 */
	protected function setCurrentBackendUserEmailAddress() {
		$this->currentBackendUserEmailAddress = $GLOBALS['BE_USER']->user['email'];
	}

	/**
	 * Finds all FE users with the same email address as the current backend user
	 *
	 * @return	void
	 */
	protected function findFeUsersWithSameEmailAddress() {
		$this->users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'fe_users',
			'email = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($this->currentBackendUserEmailAddress, 'fe_users') . ' AND disable = 0 AND deleted = 0',
			'',
			'',
			'15'
		);
	}

	/**
	 * Checks if the toolbar item shall be displayed
	 *
	 * @return	void
	 */
	public function checkAccess() {
		$conf = $GLOBALS['BE_USER']->getTSConfig('backendToolbarItem.tx_loginas.disabled');
		return ($conf['value'] == 1 ? false : true);
	}

	/**
	 * Renders the output for the toolbar menu
	 *
	 * @return	void
	 */
	public function render() {
		$this->backendReference->addCssFile('loginas', t3lib_extMgm::extRelPath($this->EXTKEY) . 'loginas.css');
		$this->backendReference->addJavascriptFile(t3lib_extMgm::extRelPath($this->EXTKEY).'loginas.js');

		$toolbarMenu = array();

		$title = $GLOBALS['LANG']->getLL('fe_users.tx_loginas_loginas', true);
		$ext_conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['loginas']);
		$defLinkText = trim($ext_conf['defLinkText']);
		if(empty($defLinkText) || strstr($defLinkText, '#') === false || strstr($defLinkText, 'password') !== false) {
			$defLinkText = '[#pid# / #uid#] #username# (#email#)';
		}

		if(count($this->users)) {
			if(count($this->users) == 1) {
				$title .= ' ' . $this->formatLinkText($this->users[0], $defLinkText);
				$toolbarMenu[] = $this->getLoginAsIconInTable($this->users[0]['uid'], $title);
			} else {
				$toolbarMenu[] = '<a href="#" class="toolbar-item"><img'.t3lib_iconWorks::skinImg($this->backPath, 'gfx/su_back.gif', 'width="16" height="16"').' title="'.$title.'" alt="'.$title.'" /></a>';

				$toolbarMenu[] = '<ul class="toolbar-item-menu" style="display: none;">';

				foreach($this->users as $user) {
					$linktext = $this->formatLinkText($user, $defLinkText);
					$link = $this->getHREF($user['uid']);
					$toolbarMenu[] = '<li><a href="' . htmlspecialchars($link) . '" target="_blank"><img'.t3lib_iconWorks::skinImg($this->backPath, 'gfx/i/fe_users.gif', 'width="16" height="16"').' title="'.$title.'" alt="'.$title.'" /> ' . $linktext . '</a></li>';
				}

				$toolbarMenu[] = '</ul>';
			}

			return implode("\n", $toolbarMenu);
		}
	}

	public function formatLinkText($user, $defLinkText) {
		foreach($user as $key => $value) {
			$defLinkText = str_replace('#' . $key . '#', $value, $defLinkText);
		}
		return $defLinkText;
	}

	public function getAdditionalAttributes() {
		if (count($this->users)) {
			return ' id="tx-loginas-menu"';
		} else {
			return '';
		}
	}

	function getHREF($userid) {

		$timeout = time()+3600;
		$ses_id = $GLOBALS['BE_USER']->user['ses_id'];
		$verification = md5($GLOBALS['$TYPO3_CONF_VARS']['SYS']['encryptionKey'].$userid.$timeout.$ses_id);
		$link = '../?tx_loginas[timeout]='.$timeout.'&tx_loginas[userid]='.$userid.'&tx_loginas[verification]='.$verification;
		return $link;
	}
	function getLink($data) {
		$label = $data['label'] . ' ' . $data['row']['username'];
		$link = $this->getHREF($data['row']['uid']);
		$content = '<a href="'.$link.'" target="_blank" style="text-decoration:underline;">'.$label.'</a>';
		return $content;
	}

	function getLoginAsIconInTable($userid, $title = '') {
		$label = t3lib_iconWorks::getSpriteIcon('actions-system-backend-user-emulate', array('title' => $title));
		$link = $this->getHREF($userid);
		$content = '<a class="toolbar-item" href="'.$link.'" target="_blank">'.$label.'</a>';
		return $content;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/loginas/class.tx_loginas.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/loginas/class.tx_loginas.php']);
}

?>

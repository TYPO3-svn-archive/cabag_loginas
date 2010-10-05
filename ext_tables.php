<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

require_once(t3lib_extMgm::extPath('loginas') . 'class.tx_loginas.php');

$GLOBALS['TYPO3_CONF_VARS']['typo3/backend.php']['additionalBackendItems'][] = t3lib_extMgm::extPath('loginas') . 'loginas_toolbar.php';

$tempColumns = array (
	'loginas' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:loginas/locallang_db.xml:fe_users.loginas',		
		'config' => array (
			'type' => 'user',
			'userFunc' => 'tx_loginas->getLink',
		)
	),
);


t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('fe_users','loginas', '', 'after:lastlogin');
?>
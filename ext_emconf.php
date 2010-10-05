<?php

########################################################################
# Extension Manager/Repository config file for ext "loginas".
#
# Auto generated 05-10-2010 18:01
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Login As',
	'description' => 'Within the backend you have a button in the fe_user table and in the upper right corner to quickly login as this fe user in frontend.',
	'category' => 'be',
	'shy' => 0,
	'version' => '1.1.1',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Dimitri Koenig',
	'author_email' => 'dk@cabag.ch',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:12:{s:9:"ChangeLog";s:4:"2234";s:20:"class.tx_loginas.php";s:4:"3545";s:36:"class.tx_loginas_makecontrolhook.php";s:4:"f5c7";s:21:"ext_conf_template.txt";s:4:"5056";s:12:"ext_icon.gif";s:4:"778f";s:17:"ext_localconf.php";s:4:"96dd";s:14:"ext_tables.php";s:4:"47ad";s:16:"locallang_db.xml";s:4:"3932";s:11:"loginas.css";s:4:"f710";s:10:"loginas.js";s:4:"e13d";s:19:"loginas_toolbar.php";s:4:"2072";s:28:"sv1/class.tx_loginas_sv1.php";s:4:"b818";}',
	'suggests' => array(
	),
);

?>
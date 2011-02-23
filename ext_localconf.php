<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (t3lib_div::int_from_ver(TYPO3_version) < 4006000) {
	$TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['typo3/mod/help/about/index.php'] = t3lib_extMgm::extPath($_EXTKEY) . 'xclass/class.tx_mytypo3_xclass_about.php';
}

$TYPO3_CONF_VARS['SC_OPTIONS']['about/index.php']['addSection'][] = 'EXT:mytypo3/hooks/class.tx_mytypo3_hooks_about.php:tx_mytypo3_hooks_about';
?>
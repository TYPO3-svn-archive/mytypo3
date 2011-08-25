<?php
/*
 * Register necessary class names with autoloader
 */
$extensionPath = t3lib_extMgm::extPath('mytypo3');
return array(
	'tx_mytypo3_reports_typo3status' => $extensionPath . 'reports/class.tx_mytypo3_reports_typo3status.php',
);
?>
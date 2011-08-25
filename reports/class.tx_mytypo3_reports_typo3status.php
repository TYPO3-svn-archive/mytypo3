<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Xavier Perseguers <xavier@typo3.org>
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
 * Checks that TYPO3 is up-to-date.
 *
 * @author		Xavier Perseguers <xavier@typo3.org>
 * @package		TYPO3
 * @subpackage	reports
 */
class tx_mytypo3_reports_Typo3Status implements tx_reports_StatusProvider {

	/**
	 * Returns the status for this report
	 *
	 * @return	array	List of statuses
	 * @see typo3/sysext/reports/interfaces/tx_reports_StatusProvider::getStatus()
	 */
	public function getStatus() {
		$statuses = array(
			'Typo3Update' => $this->getTypo3VersionStatus(),
		);

		return $statuses;
	}

	/**
	 * Checks that TYPO3 is up to date.
	 *
	 * @return	tx_reports_reports_status_Status
	 */
	protected function getTypo3VersionStatus() {
		$versions = t3lib_div::getUrl('http://causal.ch/tools/typo3releases.php');
		if (!$versions) {
			$message = 'Cannot retrieve the list of TYPO3 versions';
			$status = tx_reports_reports_status_Status::ERROR;
		} else {
			$versions = json_decode($versions, TRUE);
			$latestVersion = $versions[TYPO3_branch]['latest'];

			if (!isset($versions[TYPO3_branch])) {
				$message = 'You are using a developer version with no official release yet';
				$status = tx_reports_reports_status_Status::INFO;
			} elseif (substr(TYPO3_version, -4) == '-dev') {
				$message = 'You are using a developer version, it\'s up to you to keep it up to date';
				$status = tx_reports_reports_status_Status::INFO;
			} elseif ($latestVersion !== TYPO3_version) {
				$message = 'Version ' . $latestVersion . ' is available since ' . $versions[TYPO3_branch]['releases'][$latestVersion]['date'];
				$status = tx_reports_reports_status_Status::WARNING;
			} else {
				$message = 'Congratulations! You are running the latest available version of TYPO3';
				$status = tx_reports_reports_status_Status::OK;
			}
		}

		return t3lib_div::makeInstance('tx_reports_reports_status_Status',
			'Latest Version',
			$message,
			'',
			$status
		);
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mytypo3/reports/class.tx_mytypo3_reports_typo3status.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/reports/reports/status/class.tx_mytypo3_reports_typo3status.php']);
}

?>
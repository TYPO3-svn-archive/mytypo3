<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Xavier Perseguers <typo3@perseguers.ch>
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

if (t3lib_div::int_from_ver(TYPO3_version) < 4006000) {
	require_once(t3lib_extMgm::extPath('mytypo3') . 'hooks/interface.tx_about_customsections.php');
}

/**
 * Custom section in About module
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mytypo3
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class tx_mytypo3_hooks_about implements tx_about_customsections {

	/**
	 * Manipulates the About sections.
	 *
	 * @param array &$sections
	 * @return void
	 */
	public function addSection(&$sections) {
		$config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3'];
		if (!is_array($config)) {
			$config = array();
		}

		$companyName = isset($config['company']) ? $config['company'] : 'My Own Company';
		$companyLogo = isset($config['logo'])
				? $GLOBALS['BACK_PATH'] . '../' . $config['logo']
				: $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath('mytypo3') . 'res/empty-logo.gif';
		if (isset($config['description'])) {
			$companyDescription = $config['description'];
		} else {
			$companyDescription = "
				These are the default configuration settings for extension mytypo3<br />
				<br />
				Edit file typo3conf/localconf.php and set values for:
				<ul>
					<li>\$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3']['company']</li>
					<li>\$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3']['logo']</li>
					<li>\$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3']['description']</li>
				</ul>
			";
		}
		$sectionKey = isset($config['section']) ? $config['section'] : $companyName;

		$content = '
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $companyName . '</h2>
				<img src="'. $companyLogo . '" alt="' . $companyName . '" style="float:left" />
				<p style="margin-left: 135px;">' . $companyDescription . '</p>
			</div>
		';

		if (t3lib_div::int_from_ver(TYPO3_version) < 4006000 && $sectionKey === 'donation') {
			// There is no donation section before TYPO3 4.6. Place the block right after
			// section 'about'
			$temp = array();
			foreach ($sections as $key => $section) {
				$temp[$key] = $section;
				if ($key === 'about') {
					$temp[$sectionKey] = $content;
				}
			}
			$sections = $temp;
		} else {
			$sections[$sectionKey] = $content;
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mytypo3/hooks/class.tx_mytypo3_hooks_about.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mytypo3/hooks/class.tx_mytypo3_hooks_about.php']);
}

?>
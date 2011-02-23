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

if (t3lib_div::int_from_ver(TYPO3_version) < 4007000) {
	require_once(t3lib_extMgm::extPath('mytypo3') . 'interfaces/interface.tx_about_customsections.php');
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
	 * @var string
	 */
	protected $extKey = 'mytypo3';

	/**
	 * Manipulates the About sections.
	 *
	 * @param array &$sections
	 * @return void
	 */
	public function addSection(array &$sections) {
		$configFile = PATH_site . 'typo3conf/' . $this->extKey . '_conf.php';

			// Create default configuration
		if (!is_file($configFile)) {
			$this->createSampleConfiguration($configFile);
		}

			// Load configuration
		include($configFile);
		$config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3'];

			// Resolve the logo path
		$logo = $config['logo'];
		if (!strcmp(substr($logo, 0, 4), 'EXT:')) {
			$path = substr($logo, 4);	// Remove 'EXT:' at the beginning
			$extension = substr($path, 0, strpos($path, '/'));
			$references = explode(':', substr($path, strlen($extension) + 1));
			$logo = t3lib_extMgm::siteRelPath($extension) . $references[0];
		}

		$documentRoot = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT');
		$logoSrc = substr(PATH_site, strlen($documentRoot)) . $logo;

			// Prepare the new section
		$content = '
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $config['company'] . '</h2>
				<img src="'. $logoSrc . '" alt="' . $config['company'] . '" style="float:left" />
				<p style="margin-left: 135px;">' . $config['description'] . '</p>
			</div>
		';

			// Append the new section
		$sectionKey = isset($config['section']) ? $config['section'] : $config['company'];
		$sections[$sectionKey] = $content;
	}

	/**
	 * Creates a sample configuration file.
	 *
	 * @param string $filename
	 * @return void
	 */
	protected function createSampleConfiguration($filename) {
		$defaultConfig = array(
			'company'     => 'My Own Company',
			'logo'        => 'EXT:' . $this->extKey . '/res/empty-logo.gif',
			'description' => '
				These are the default configuration settings for extension ' . $this->extKey . '.<br />
				<br />
				Please edit file ' . substr($filename, strlen(PATH_site)) . ' to fit your needs.
			',
		);

		$content = '<' . '?php' . LF;
		$content .= "\$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mytypo3'] = ";
		$content .= var_export($defaultConfig, TRUE) . ';' . LF;
		$content .= '?' . '>';

		t3lib_div::writeFile($filename, $content);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mytypo3/hooks/class.tx_mytypo3_hooks_about.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mytypo3/hooks/class.tx_mytypo3_hooks_about.php']);
}

?>
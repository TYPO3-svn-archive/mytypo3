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

/**
 * About module extension to allow custom sections, as in TYPO3 4.6.
 *
 * @category    XCLASS
 * @package     TYPO3
 * @subpackage  tx_mytypo3
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @version     SVN: $Id$
 */
class ux_SC_mod_help_about_index extends SC_mod_help_about_index {

	/**
	 * @var array
	 */
	protected $sections;

	/**
	 * Main function, producing the module output.
	 * In this case, the module output is a very simple screen telling the version of TYPO3 and that's basically it...
	 * The content is set in the internal variable $this->content
	 *
	 * @return	void
	 */
	public function main() {
		$this->MCONF = $GLOBALS['MCONF'];

		// **************************
		// Main
		// **************************

		$GLOBALS['TBE_TEMPLATE']->backPath = $GLOBALS['BACK_PATH'];

		$content = $this->renderSections();

		// Renders the module page
		$this->content = $GLOBALS['TBE_TEMPLATE']->render(
			$GLOBALS['LANG']->getLL('title', TRUE),
			$content
		);
	}

	/**
	 * Render main sections
	 * @return string $content
	 */
	public function renderSections() {
		$this->sections = array();

		$minorText = sprintf($GLOBALS['LANG']->getLL('minor'), 'TYPO3 Ver. ' . htmlspecialchars(TYPO3_version) . ', Copyright &copy; ' . htmlspecialchars(TYPO3_copyright_year), 'Kasper Sk&aring;rh&oslash;j');
		$content = '
			<img' . t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'], 'gfx/typo3logo.gif', 'width="123" height="34"') . ' alt="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:typo3_logo', true) . '" />
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $GLOBALS['LANG']->getLL('welcome', TRUE) . '</h2>
					<p>' . $minorText . '</p>
			</div>';

		$this->sections['about'] = $content;

		$content = '
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:community_credits', TRUE) . '</h2>
				<p>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:information_detail') . '</p>
			</div>';

		$this->sections['community'] = $content;

		$content = '
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:coredevs', TRUE) . '</h2>
				<p>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:coredevs_detail') . '</p>
			</div>';

		$this->sections['coreteam'] = $content;

		$content = '
			<div class="typo3-mod-help-about-index-php-inner">
				<h2>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:extension_authors', TRUE) . '</h2>
				<p>' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_help_about.xml:extension_list_info', TRUE) . '</p>
				<br />' . $this->getExtensionAuthors() . '
			</div>';

		$this->sections['authors'] = $content;

		$this->renderCustomSections();

		// compile content
		$content = '<div id="typo3-mod-help-about-index-php-outer">
            ' . implode('', $this->sections) . '
        </div>';

		return $content;
	}

	/**
	 * Renders custom sections.
	 *
	 * @return void
	 */
	protected function renderCustomSections() {
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['about/index.php']['addSection'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['about/index.php']['addSection'] as $classRef) {
				$hookObject = t3lib_div::getUserObj($classRef);
				if (!($hookObject instanceof tx_about_customSections)) {
					throw new UnexpectedValueException('$hookObject must implement interface tx_about_customSections', 1298121573);
				}
				$hookObject->addSection($this->sections);
			}
		}
	}
}

?>
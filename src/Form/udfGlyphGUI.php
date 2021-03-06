<?php

namespace srag\Plugins\UserDefaults\Form;

use ilGlyphGUI;
use ilUserDefaultsPlugin;
use srag\DIC\UserDefaults\DICTrait;
use srag\Plugins\UserDefaults\Utils\UserDefaultsTrait;

/**
 * Class xlvoGlyphGUI
 *
 * @package srag\Plugins\UserDefaults\Form
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 */
class udfGlyphGUI extends ilGlyphGUI {

	use DICTrait;
	use UserDefaultsTrait;
	const PLUGIN_CLASS_NAME = ilUserDefaultsPlugin::class;


	/**
	 * Get glyph html
	 *
	 * @param string $a_glyph glyph constant
	 * @param string $a_text  text representation
	 *
	 * @return string html
	 */
	static function get($a_glyph, $a_text = "") {
		if ($a_glyph == 'remove') {
			self::$map[$a_glyph]['class'] = 'glyphicon glyphicon-' . $a_glyph;
		}
		if (!isset(self::$map[$a_glyph])) {
			self::$map[$a_glyph]['class'] = 'glyphicon glyphicon-' . $a_glyph;
		}

		return parent::get($a_glyph, $a_text) . ' ';
	}


	/**
	 * @param $a_glyph
	 *
	 * @return string
	 */
	static function gets($a_glyph) {
		self::$map[$a_glyph]['class'] = 'glyphicons glyphicons-' . $a_glyph;

		return parent::get($a_glyph, '') . ' ';
	}
}

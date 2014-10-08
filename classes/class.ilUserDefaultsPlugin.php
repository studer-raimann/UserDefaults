<?php
require_once('./Services/EventHandling/classes/class.ilEventHookPlugin.php');
require_once('./Customizing/global/plugins/Services/EventHandling/EventHook/UserDefaults/classes/UserSetting/class.ilUserSetting.php');

/**
 * Class ilUserDefaultsPlugin
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilUserDefaultsPlugin extends ilEventHookPlugin {

	/**
	 * @var
	 */
	protected static $instance;


	/**
	 * @return ilUserDefaultsPlugin
	 */
	public static function getInstance() {
		if (! isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	const PLUGIN_NAME = 'UserDefaults';


	/**
	 * Handle the event
	 *
	 * @param    string        component, e.g. "Services/User"
	 * @param    event         event, e.g. "afterUpdate"
	 * @param    array         array of event specific parameters
	 */
	public function handleEvent($a_component, $a_event, $a_parameter) {
		if ($a_component == 'Modules/Course' AND $a_event == 'update') {
			global $ilUser;
			/**
			 * @var $ilUserSetting ilUserSetting
			 */
			$ilUser = new ilObjUser(340);

			foreach (ilUserSetting::where(array( 'status' => ilUserSetting::STATUS_ACTIVE ))->get() as $ilUserSetting) {
				$ilUserSetting->doAssignements($ilUser);
			}
		}

		if ($a_component == 'Services/User' AND $a_event == 'saveAsNew') {
			/**
			 * @var $ilUser ilObjUser
			 */

			$ilUser = $a_parameter['user_obj'];

			if ($ilUser instanceof ilObjUser) {
				// Do Stuff
				foreach (ilUserSetting::where(array( 'status' => ilUserSetting::STATUS_ACTIVE ))->get() as $ilUserSetting) {
					$ilUserSetting->doAssignements($ilUser);
				}
			}
		}
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


	public function updateLanguageFiles() {
		ini_set('auto_detect_line_endings', true);
		$path = substr(__FILE__, 0, strpos(__FILE__, 'classes')) . 'lang/';
		if (file_exists($path . 'lang_custom.csv')) {
			$file = $path . 'lang_custom.csv';
		} else {
			$file = $path . 'lang.csv';
		}
		$keys = array();
		$new_lines = array();

		foreach (file($file) as $n => $row) {
			//			$row = utf8_encode($row);
			if ($n == 0) {
				$keys = str_getcsv($row, ";");
				continue;
			}
			$data = str_getcsv($row, ";");;
			foreach ($keys as $i => $k) {
				if ($k != 'var' AND $k != 'part') {
					$new_lines[$k][] = $data[0] . '_' . $data[1] . '#:#' . $data[$i];
				}
			}
		}
		$start = '<!-- language file start -->' . PHP_EOL;
		$status = true;

		foreach ($new_lines as $lng_key => $lang) {
			$status = file_put_contents($path . 'ilias_' . $lng_key . '.lang', $start . implode(PHP_EOL, $lang));
		}

		if (! $status) {
			ilUtil::sendFailure('Language-Files coul\'d not be written');
		}
		$this->updateLanguages();
	}
}

?>
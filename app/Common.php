<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

 /**
  * Custom constants used inside the application.
  *
  * @author Jan Martinek
  */
 defined('WINDOWS_SEPARATOR') || define('WINDOWS_SEPARATOR', '\\');
 defined('UNIX_SEPARATOR')    || define('UNIX_SEPARATOR', '/');

 defined('ASSET_PREFIX') || define('ASSET_PREFIX', 'public' . UNIX_SEPARATOR . 'assets' . UNIX_SEPARATOR);

 defined('SAVE_PREFIX') || define('SAVE_PREFIX', 'public' . UNIX_SEPARATOR . 'uploads' . UNIX_SEPARATOR);
 defined('SAVE_PATH')   || define('SAVE_PATH', ROOTPATH . SAVE_PREFIX);

 defined('TEMP')        || define('TEMP', 'temp' . UNIX_SEPARATOR);
 defined('TEMP_PREFIX') || define('TEMP_PREFIX', 'public' . UNIX_SEPARATOR . TEMP);
 defined('TEMP_PATH')   || define('TEMP_PATH', ROOTPATH . TEMP_PREFIX);

 defined('UNUSED')      || define('UNUSED', 'unused' . UNIX_SEPARATOR);
 defined('UNUSED_PATH') || define('UNUSED_PATH', TEMP_PATH . UNUSED);

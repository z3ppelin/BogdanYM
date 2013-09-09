<?php
/**
 * Bootstrap for unit testing.
 * 
 * @author      Bogdan Constantinescu <bog_con@yahoo.com>
 * @since       2013.08.01
 * @version     1.0
 * @link        GitHub  https://github.com/z3ppelin/BogdanYM.git
 * @licence     The MIT License (http://opensource.org/licenses/MIT); see LICENCE.txt
 */
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'YMException.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'YMEngine.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Logger.php';

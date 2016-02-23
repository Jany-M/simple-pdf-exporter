<?php

$wp_path = explode('wp-content', dirname(__FILE__));
require_once($wp_path[0].'wp-load.php');

/**
 * @package dompdf
 * @link    http://dompdf.github.com/
 * @author  Benj Carson <benjcarson@digitaljunkies.ca>
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

/**
 * Dompdf autoload function
 *
 * If you have an existing autoload function, add a call to this function
 * from your existing __autoload() implementation.
 *
 * @param string $class
 */

require_once PDF_LIBS . 'dompdf/lib/html5lib/Parser.php';
require_once PDF_LIBS . 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once PDF_LIBS . 'dompdf/lib/php-svg-lib/src/autoload.php';

/*
 * New PHP 5.3.0 namespaced autoloader
 */
require_once PDF_LIBS . 'dompdf/src/Autoloader.php';

Dompdf\Autoloader::register();

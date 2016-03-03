<?php
/**
 * Plugin Name: Simple PDF Exporter
 * Plugin URI: https://wordpress.org/plugins/simple-pdf-exporter/
 * Description: Export a single PDF with all posts, or custom post types. <strong>Requires at least 512MB of free RAM on this server.</strong>
 * Version: 1.7
 * Author: Shambix
 * Author URI: http://www.shambix.com
 * License GPLv3
*/

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);*/

define('SIMPLE_PDF_EXPORTER', '1.7');

/*--------------------------------------
|                                      |
|  PATHS & FOLDERS                     |
|                                      |
---------------------------------------*/

    $upload_dir = wp_upload_dir();

    if (!defined('PDF_PROCESS'))
        define('PDF_PROCESS', plugin_dir_path(__FILE__).'process/');
    if (!defined('PDF_LIBS'))
        define('PDF_LIBS', plugin_dir_path(__FILE__).'libs/');
    if (!defined('PDF_ASSETS'))
        define('PDF_ASSETS', plugin_dir_path(__FILE__).'assets/');
    if (!defined('PDF_EXPORT'))
      define('PDF_EXPORT', $upload_dir['basedir'].'/pdf-export/');
    if (!defined('PDF_EXPORT_FILES'))
      define('PDF_EXPORT_FILES', PDF_EXPORT.'pdf/');
    if (!defined('PDF_EXPORT_HTML'))
      define('PDF_EXPORT_HTML', PDF_EXPORT.'html/');

    /* dont edit these here, add them to your wp-config instead */
    /*if (!defined('PDF_EXPORT_CACHE'))
      define('PDF_EXPORT_CACHE', true);*/
    if (!defined('PDF_EXPORT_PAGINATION'))
        define('PDF_EXPORT_PAGINATION', false);
    if (!defined('PDF_EXPORT_HTML_OUTPUT'))
        define('PDF_EXPORT_HTML_OUTPUT', false);
    if (!defined('PDF_EXPORT_CSS_FILE'))
        define('PDF_EXPORT_CSS_FILE', get_stylesheet_directory_uri().'/pdf_export.css');
    /*if (!defined('PDF_EXPORT_LAYOUT_FILE'))
        define('PDF_EXPORT_LAYOUT_FILE', get_stylesheet_directory_uri().'/pdf_export.php');*/
    if (!defined('DOMPDF_PAPER_SIZE'))
        define('DOMPDF_PAPER_SIZE', 'A4');
    if (!defined('DOMPDF_PAPER_ORIENTATION'))
        define('DOMPDF_PAPER_ORIENTATION', 'portrait');
    if (!defined('DOMPDF_DPI'))
        define('DOMPDF_DPI', 72);
    if (!defined('DOMPDF_ENABLE_REMOTE'))
        define('DOMPDF_ENABLE_REMOTE', true);
    if (!defined('DOMPDF_ENABLE_HTML5'))
        define('DOMPDF_ENABLE_HTML5', false);
    if (!defined('DOMPDF_ENABLE_FONTSUBSETTING'))
        define('DOMPDF_ENABLE_FONTSUBSETTING', true);
    if (!defined('DOMPDF_MEDIATYPE'))
        define('DOMPDF_MEDIATYPE', 'print');
    if (!defined('DOMPDF_FONTHEIGHTRATIO'))
        define('DOMPDF_FONTHEIGHTRATIO', 1);

    if (defined('PDF_EXPORT_HTML_OUTPUT') && PDF_EXPORT_HTML_OUTPUT ) {
        if (!is_dir(PDF_EXPORT_HTML) || !file_exists(PDF_EXPORT_HTML)) {
            mkdir(PDF_EXPORT_HTML, 0777, true);
        }
    }

/*--------------------------------------
|                                      |
|  ACTIVATION / DEACTIVATION           |
|                                      |
---------------------------------------*/

    if(!class_exists('SIMPLE_PDF_EXPORT')) {
        class SIMPLE_PDF_EXPORT  {

            public function __construct()  {

                require_once(sprintf("%s/settings.php", dirname(__FILE__)));
                require_once(sprintf("%s/exporter.php", dirname(__FILE__)));
                
                add_action('wp_loaded', 'pdf_export');

                $SIMPLE_PDF_EXPORT_Settings = new SIMPLE_PDF_EXPORT_Settings();
            }

            public static function activate() {

                // Make some Dirs
                if (!is_dir(PDF_EXPORT) || !file_exists(PDF_EXPORT)) {
                    mkdir(PDF_EXPORT, 0777, true);
                }
                if (!is_dir(PDF_EXPORT_FILES) || !file_exists(PDF_EXPORT_FILES)) {
                    mkdir(PDF_EXPORT_FILES, 0777, true);
                }
            }

            public static function deactivate() {

                // Remove directories created by this plugin in wp-content
                function rrmdir($dir) {
                    foreach(glob($dir . '/*') as $file) {
                        if(is_dir($file)) rrmdir($file); else unlink($file);
                    }
                    rmdir($dir);
                }
                rrmdir(PDF_EXPORT);
            }

        }
    }

    if(class_exists('SIMPLE_PDF_EXPORT')) {

        register_activation_hook(__FILE__, array('SIMPLE_PDF_EXPORT', 'activate'));
        register_deactivation_hook(__FILE__, array('SIMPLE_PDF_EXPORT', 'deactivate'));

        // instantiate the plugin class
        $simple_pdf_export = new SIMPLE_PDF_EXPORT();

        if(isset($wp_plugin_template))  {
        }

    }

?>
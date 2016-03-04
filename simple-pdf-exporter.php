<?php
/**
 * Plugin Name: Simple PDF Exporter
 * Plugin URI: https://wordpress.org/plugins/simple-pdf-exporter/
 * Description: Export a single PDF with all posts, or custom post types. <strong>Requires at least 512MB of free RAM on this server.</strong>
 * Version: 1.7.1
 * Author: Shambix
 * Author URI: http://www.shambix.com
 * License GPLv3
*/

define('SIMPLE_PDF_EXPORTER_VERS', '1.7.1');

/*--------------------------------------
|                                      |
|  PATHS & FOLDERS                     |
|                                      |
---------------------------------------*/

    $upload_dir = wp_upload_dir();

    if (!defined('SIMPLE_PDF_EXPORTER_PROCESS'))
        define('SIMPLE_PDF_EXPORTER_PROCESS', plugin_dir_path(__FILE__).'process/');
    if (!defined('SIMPLE_PDF_EXPORTER_LIBS'))
        define('SIMPLE_PDF_EXPORTER_LIBS', plugin_dir_path(__FILE__).'libs/');
    if (!defined('SIMPLE_PDF_EXPORTER_ASSETS'))
        define('SIMPLE_PDF_EXPORTER_ASSETS', plugin_dir_path(__FILE__).'assets/');
    if (!defined('SIMPLE_PDF_EXPORTER_EXPORT'))
        define('SIMPLE_PDF_EXPORTER_EXPORT', $upload_dir['basedir'].'/pdf-export/');
    if (!defined('SIMPLE_PDF_EXPORTER_FILES'))
        define('SIMPLE_PDF_EXPORTER_FILES', PDF_EXPORT.'pdf/');
    if (!defined('SIMPLE_PDF_EXPORTER_HTML'))
        define('SIMPLE_PDF_EXPORTER_HTML', PDF_EXPORT.'html/');

    /* dont edit these here, add them to your wp-config instead */
    /*if (!defined('SIMPLE_PDF_EXPORTER_CACHE'))
      define('SIMPLE_PDF_EXPORTER_CACHE', true);*/
    if (!defined('SIMPLE_PDF_EXPORTER_PAGINATION'))
        define('SIMPLE_PDF_EXPORTER_PAGINATION', false);
    if (!defined('SIMPLE_PDF_EXPORTER_HTML_OUTPUT'))
        define('SIMPLE_PDF_EXPORTER_HTML_OUTPUT', false);
    if (!defined('SIMPLE_PDF_EXPORTER_CSS_FILE'))
        define('SIMPLE_PDF_EXPORTER_CSS_FILE', get_stylesheet_directory_uri().'/pdf_export.css');
    /*if (!defined('SIMPLE_PDF_EXPORTER_LAYOUT_FILE'))
        define('SIMPLE_PDF_EXPORTER_LAYOUT_FILE', get_stylesheet_directory_uri().'/pdf_export.php');*/
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

    if (defined('SIMPLE_PDF_EXPORTER_HTML_OUTPUT')) {
        if (!is_dir(SIMPLE_PDF_EXPORTER_HTML) || !file_exists(SIMPLE_PDF_EXPORTER_HTML)) {
            mkdir(SIMPLE_PDF_EXPORTER_HTML, 0777, true);
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
                
                add_action('wp_loaded', 'simple_pdf_export_process');

                $SIMPLE_PDF_Settings = new SIMPLE_PDF_Settings();
            }

            public static function activate() {

                // Make some Dirs
                if (!is_dir(PDF_EXPORT) || !file_exists(PDF_EXPORT)) {
                    mkdir(PDF_EXPORT, 0777, true);
                }
                if (!is_dir(PDF_FILES) || !file_exists(PDF_FILES)) {
                    mkdir(PDF_FILES, 0777, true);
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
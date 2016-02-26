<?php
/**
 * Plugin Name: Simple PDF Exporter
 * Plugin URI: https://github.com/Jany-M/simple-pdf-exporter/
 * Description: This plugin uses the DOMPDF Library to generate PDFs for whole post types, with index page. 
 * Version: 1.6.5
 * Author: Shambix
 * Author URI: http://www.shambix.com
 * License: GPLv3
*/

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);*/

/*--------------------------------------
|                                      |
|  PATHS & FOLDERS                     |
|                                      |
---------------------------------------*/
    
    define('PDF_PROCESS', plugin_dir_path(__FILE__).'process/');
    define('PDF_LIBS', plugin_dir_path(__FILE__).'libs/');
    define('PDF_ASSETS', plugin_dir_path(__FILE__).'assets/');

    $upload_dir = wp_upload_dir();
    if (!defined('PDF_EXPORT')) {
      define('PDF_EXPORT', $upload_dir['basedir'].'/pdf-export/');
    }
    if (!is_dir(PDF_EXPORT) || !file_exists(PDF_EXPORT)) {
        mkdir(PDF_EXPORT, 0777, true);
    }
    if (!defined('PDF_EXPORT_FILES')) {
      define('PDF_EXPORT_FILES', PDF_EXPORT.'pdf/');
    }
    if (!is_dir(PDF_EXPORT_FILES) || !file_exists(PDF_EXPORT_FILES)) {
        mkdir(PDF_EXPORT_FILES, 0777, true);
    }
    if (!defined('PDF_EXPORT_HTML')) {
      define('PDF_EXPORT_HTML', PDF_EXPORT.'html/');
    }

    // https://github.com/dompdf/dompdf/wiki/About-Fonts-and-Character-Encoding
    // https://github.com/dompdf/dompdf/wiki/CSSCompatibility

    /*if (!defined('DOMPDF_FONT_DIR'))
      define('DOMPDF_FONT_DIR', PDF_EXPORT . 'dompdf-fonts/');
    if (!is_dir(DOMPDF_FONT_DIR) || !file_exists(DOMPDF_FONT_DIR)) {
        mkdir(DOMPDF_FONT_DIR, 0777, true);
    }
    if (!defined('DOMPDF_FONT_CACHE'))
      define('DOMPDF_FONT_CACHE', PDF_EXPORT . 'dompdf-fonts/');
    */

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
                // copy DOMPDF fonts to wp-content/dompdf-fonts/
                /*if(!file_exists(DOMPDF_FONT_DIR.'dompdf_font_family_cache.dist.php')) {
                    copy(PDF_LIBS.'dompdf/lib/fonts/dompdf_font_family_cache.dist.php', DOMPDF_FONT_DIR.'dompdf_font_family_cache.dist.php');
                }*/
            }

            public static function deactivate() {
                // Remove directories created by this plugin in wp-content
                function rrmdir($dir) {
                    foreach(glob($dir . '/*') as $file) {
                        if(is_dir($file)) rrmdir($file); else unlink($file);
                    }
                    rmdir($dir);
                }
                rrmdir(PDF_EXPORT); //this removes the whole folder in wp-uploads - nothing is left
                /*$pdf_folder = $upload_dir['basedir'].'/pdf-export/pdf';
                rrmdir(PDF_EXPORT_FILES); //this removes only the pdf folder in wp-uploads/pdf-export - leaving the main pdf's
                rrmdir($pdf_folder);
                $html_folder = $upload_dir['basedir'].'/pdf-export/html';
                rrmdir(PDF_EXPORT_HTML); // this removes  only the html folder in wp-uploads/pdf-export - leaving the main pdf's
                rrmdir($html_folder);*/

                /*$option_name = 'plugin_option_name';
                delete_option( $option_name );
                //delete_site_option( $option_name );  //multisite only
                global $wpdb;
                $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );*/
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
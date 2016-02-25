<?php
/**
 * Plugin Name: Simple PDF Exporter
 * Plugin URI: https://github.com/Seravo/wp-pdf-templates
 * Description: This plugin utilises the DOMPDF Library to provide a URL endpoint e.g. /my-post/pdf/ that generates a downloadable PDF file.
 * Version: 1.4
 * Author: Jany Martelli
 * Author URI: http://www.shambix.com
 * License: GPLv3
*/

define('SIMPLE_PDF_EXPORT_VERSION', '1.3');
//define('DISABLE_PDF_CACHE', true);

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
                // Add 24h cron job to WP
                wp_schedule_event(time(), 'daily', 'pdf_export_cronjob_every_24h');

                // copy DOMPDF fonts to wp-content/dompdf-fonts/
                /*if(!file_exists(DOMPDF_FONT_DIR.'dompdf_font_family_cache.dist.php')) {
                    copy(PDF_LIBS.'dompdf/lib/fonts/dompdf_font_family_cache.dist.php', DOMPDF_FONT_DIR.'dompdf_font_family_cache.dist.php');
                }*/
            }

            public static function deactivate() {
                /*if (!defined('WP_UNINSTALL_PLUGIN')) {
                    exit();
                }*/

                // Remove cronjob
                wp_clear_scheduled_hook('pdf_export_cronjob_every_24h');

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
            // Add the action link to Plugin, in plugins page
            /*function plugin_settings_link($links) {
                $settings_link = '<a href="options-general.php?page=simple_pdf_export_settings">Settings</a>';
                array_unshift($links, $settings_link);
                return $links;
            }
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", 'plugin_settings_link');*/
        }

        // Add 24h cron job to WP (in case it gets deleted but plugin doesnt get re-activated)
        function pdf_export_daily() {
            wp_schedule_event(time(), 'daily', 'pdf_export_cronjob_every_24h');
        }
        if (!wp_next_scheduled( 'pdf_export_cronjob_every_24h' ) ) {
            add_action('pdf_export_cronjob_every_24h', 'pdf_export');
        }

    }
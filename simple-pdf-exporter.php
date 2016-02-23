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

define('SIMPLE_PDF_EXPORT_VERSION', '1');
//define('DISABLE_PDF_CACHE', true);

ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);

/*---------------------------------------
|                                      |
|  PATHS & FOLDERS                     |
|                                      |
---------------------------------------*/
    
    define('PDF_PROCESS', plugin_dir_path(__FILE__).'process/');
    define('PDF_LIBS', plugin_dir_path(__FILE__).'libs/');

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

    /*if (!defined('DOMPDF_FONT_DIR'))
      define('DOMPDF_FONT_DIR', PDF_EXPORT . 'dompdf-fonts/');
    if (!is_dir(DOMPDF_FONT_DIR) || !file_exists(DOMPDF_FONT_DIR)) {
        mkdir(DOMPDF_FONT_DIR, 0777, true);
    }
    if (!defined('DOMPDF_FONT_CACHE'))
      define('DOMPDF_FONT_CACHE', PDF_EXPORT . 'dompdf-fonts/');

    function _init_dompdf_fonts() {
        // copy DOMPDF fonts to wp-content/dompdf-fonts/
        if(!file_exists(DOMPDF_FONT_DIR . 'dompdf_font_family_cache.dist.php')) {
            copy(
              PDF_LIBS . 'dompdf/lib/fonts/dompdf_font_family_cache.dist.php',
              DOMPDF_FONT_DIR . 'dompdf_font_family_cache.dist.php'
              );
        }
    }
    register_activation_hook(__FILE__, '_init_dompdf_fonts');*/


/*---------------------------------------
|                                      |
|  DOMPDF                              |
|                                      |
---------------------------------------*/
    
    require_once plugin_dir_path(__FILE__) . 'autoload.inc.php';
    use Dompdf\Dompdf;

if(!class_exists('SIMPLE_PDF_EXPORT')) {
    class SIMPLE_PDF_EXPORT  {

        public function __construct()  {
            require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            require_once(sprintf("%s/exporter.php", dirname(__FILE__)));
            add_action('wp_loaded', 'pdf_export');
            $SIMPLE_PDF_EXPORT_Settings = new SIMPLE_PDF_EXPORT_Settings();
        }

        public static function activate() {
            // TODO Add cronjob = 1 x 24h
        }

        public static function deactivate() {

            if (!defined('WP_UNINSTALL_PLUGIN')) {
                exit();
            }

            // TODO Remove cronjob

            // Remove directories created by this plugin
            //is_dir(PDF_EXPORT . 'dompdf-fonts') && rrmdir(PDF_EXPORT . 'dompdf-fonts');
            //is_dir(PDF_EXPORT . 'pdf-cache') && rrmdir(PDF_EXPORT . 'pdf-cache');
            
            // Handles recursive remove
            function rrmdir($dir) {
              foreach(glob($dir . '/*') as $file) {
                if(is_dir($file)) rrmdir($file); else unlink($file);
              } rmdir($dir);
            }
        }

    }
}

if(class_exists('SIMPLE_PDF_EXPORT')) {
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('SIMPLE_PDF_EXPORT', 'activate'));
    register_deactivation_hook(__FILE__, array('SIMPLE_PDF_EXPORT', 'deactivate'));

    // instantiate the plugin class
    $simple_pdf_export = new SIMPLE_PDF_EXPORT();

    if(isset($wp_plugin_template))  {
        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=simple_pdf_export_settings">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }
}
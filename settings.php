<?php 


if(!class_exists('SIMPLE_PDF_EXPORT_SETTINGS')) {

    class SIMPLE_PDF_EXPORT_SETTINGS  {

        public function __construct() {
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
        }

        // hook into WP's admin_init action hook
        public function admin_init() {
            add_settings_section(
                'simple_pdf_export_template-section',
                'PDF Export Settings',
                array(&$this, 'settings_section_simple_pdf_export_template'),
                'simple_pdf_export_template'
            );
        }

        public function settings_section_simple_pdf_export_template()  {
        }

        // ADD MENU
        public function add_menu() {
            add_submenu_page(
                'tools.php',
                'PDF Export',
                'PDF Export',
                'manage_options',
                'simple_pdf_export_template',
                array(&$this, 'plugin_settings_page')
            );
        }

        // MENU CALLBACK
        public function plugin_settings_page() {
            if(!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            // Render the settings template
            ?>
            <div class="wrap">
            <h2>PDF Exporter</h2>
            <a class="ccsve_button button button-success" href="?export=pdf">Export to PDF</a>
          </div>
        <?php
        } // END public function plugin_settings_page()

    } // END class simple_pdf_export_template_Settings

} // END if(!class_exists('simple_pdf_export_template_Settings'))
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
          echo '<p>From this page you can add the default post type with its connected taxonomies and custom fields, that you wish to export.<br>After that, anytime you will use the urls <strong>'.get_bloginfo('url').'/?export=csv</strong> for a CSV file, or <strong>'.get_bloginfo('url').'/?export=xls</strong>, you will get that post type data.</p>';
          echo '<p>At the bottom of this page you can export right away what you just selected, after saving first.</p>';
          echo '<p>You must choose the post type and save the settings <strong>before</strong> you can see the taxonomies or custom fields for a custom post type. Once the page reloads, you will see the connected taxonomies and custom fields for the post type.</p>';
          echo '<hr>';
          echo '<p>If you want to export from a different post type than the one saved in these settings, also from frontend, use the url <strong>'.get_bloginfo('url').'/?export=csv&post_type=your_post_type_slug</strong> for a CSV file, or <strong>'.get_bloginfo('url').'/?export=xls&post_type=your_post_type_slug</strong> to get a XLS.</p>';
           echo '<hr>';
           echo '<p><i>When opening the exported xls, Excel will prompt the user with a warning, but the file is perfectly fine and can then be opened. <strong>Unfortunately this can\'t be avoided</strong>, <a href="http://blogs.msdn.com/b/vsofficedeveloper/archive/2008/03/11/excel-2007-extension-warning.aspx" target="_blank">read more here</a>.</i></p>';
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
        //include(sprintf("%s/settings_page.php", dirname(__FILE__)));
        ?>
        <div class="wrap">
        <h2>PDF Exporter</h2>
        <!-- <form method="post" action="options.php">
          <?php @settings_fields('simple_pdf_export-group'); ?>
          <?php @do_settings_fields('simple_pdf_export-group'); ?>
          <?php do_settings_sections('simple_pdf_export_template'); ?>
          <?php @submit_button(); ?>  
        </form> -->
        <a class="ccsve_button button button-success" href="?export=pdf">Export to PDF</a>
      </div>
    <?php
    } // END public function plugin_settings_page()

    } // END class simple_pdf_export_template_Settings

} // END if(!class_exists('simple_pdf_export_template_Settings'))
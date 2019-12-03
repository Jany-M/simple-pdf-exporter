<?php 


if(!class_exists('SIMPLE_PDF_EXPORT_SETTINGS')) {

    class SIMPLE_PDF_EXPORT_SETTINGS  {

        public function __construct() {
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
        }

        // hook into WP's admin_init action hook
        public function admin_init() {

            // OPTIONS
            register_setting('wp_spe-group', 'spe_pdfhandler');

            add_settings_field(
                'spe_pdfhandler',
                'Download PDF or open in Tab',
                array(&$this, 'settings_field_select_pdfhandler'),
                'simple_pdf_export_settings',
                'simple_pdf_export_settings-section'
            );

            // SECTIONS
            add_settings_section(
                'simple_pdf_export_settings-section',
                'Settings',
                array(&$this, 'settings_section_simple_pdf_export_settings'),
                'simple_pdf_export_settings'
            );
        }

        // INPUT OPTION
        /*public function settings_field_input_delimiter() {
            $pdf_handler = get_option('spe_pdfhandler'); // attachment = download, inline = open in new tab
            if($pdf_handler == '') $pdf_handler = '|';
            echo '<input type="text" id="csv_delimiter" name="ccsve_delimiter" value="'.$pdf_handler.'" />';
        }*/

        // SELECT OPTION
        /*public function settings_field_select_pdfhandler() {
            $pdf_handler = get_option('spe_pdfhandler'); // attachment = download, inline = open in new tab

            if(empty($pdf_handler)) $pdf_handler = 'No';
            $pdf_handler_options = array(
                'attachment'  => "Download PDF",
                'inline' => "Open in Tab"
            );
            // Debug
            if(current_user_can('administrator')) {
                echo '<pre>';
                var_dump($pdf_handler_options);
                echo '</pre>';
            }
            foreach ($pdf_handler_options as $pdf_handler_option) {
                // Debug
                if(current_user_can('administrator')) {
                    echo '<pre>';
                    var_dump($pdf_handler_option);
                    echo '</pre>';
                }
                $checked = ($pdf_handler == $pdf_handler_option) ? ' checked="checked" ' : '';
                // radio buttons
                echo '<input type="radio" id="pdf_handler" name="spe_pdfhandler" value="'.$pdf_handler_option.'" '.$checked.'" />';
                echo '<label for=pdf_handler'.$pdf_handler_option.'> '.$pdf_handler_option.'</label>';
                echo ' <br />';
            }
        }*/

        public function settings_field_select_pdfhandler() {
            $pdf_handler = get_option('spe_pdfhandler'); // attachment = download, inline = open in new tab

            if(empty($pdf_handler)) {
                $pdf_handler = 'attachment';
            }

            $pdf_handler_options = array(
                'Download PDF'  => "attachment",
                'Open in Tab' => "inline"
            );

            foreach($pdf_handler_options as $label => $pdf_handler_option) {
                $checked = checked($pdf_handler_option, $pdf_handler, false);
                
                echo '<p>
                    <input type="radio" id="pdf_handler radio-'.$pdf_handler_option.'" name="spe_pdfhandler" value="'.$pdf_handler_option.'" '.$checked.'>
                    <label for="pdf_handler radio-'.$pdf_handler_option.'">'.$label.'</label>
                </p>';
            }
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
                'simple_pdf_export_settings',
                array(&$this, 'simple_pdf_export_plugin_settings_page')
            );
        }

        // MENU CALLBACK
        public function simple_pdf_export_plugin_settings_page() {
            if(!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            ?>
            <div class="wrap">

                <h2>PDF Exporter</h2>
                
                <div class="wrap simple_pdf_export_wrap">
                    <form method="post" action="options.php">
                        <?php echo settings_fields('wp_spe-group'); ?>
                        <?php echo do_settings_fields('wp_spe-group', 'simple_pdf_export_settings-section'); ?>
                        <?php echo do_settings_sections('simple_pdf_export_settings'); ?>
                        <?php echo submit_button(); ?>
                    </form>
                </div>

                <!-- <h3>Custom Export</h3>
                <form method="post" action="?export=pdf">
                    <input type="text" name="num" placeholder="Max Posts eg. 3"><br>
                    <input type="text" name="post_type" placeholder="Post Type Slug eg. product"><br>
                    <label for="force">Force new PDF generation?</label><input type="checkbox" name="force" value="force" checked><br>
                    <input type="submit" class="ccsve_button button button-success" value="Custom Export to PDF" name="submit">
                </form> -->

                <h3>Default Export</h3>

                <p>This will export ALL <strong>posts</strong> and will force the generation of a new file.</p>
                <p>If you need to export a custom post type, or you want to customize the layout, number of posts exported or build a frontend custom button, please refer to the <a href="https://wordpress.org/plugins/simple-pdf-exporter/faq/" target="_blank">FAQ</a>.</p>
                <p><strong>Remember that if you don't have at least 512MB of RAM, on this server, the export will fail.</strong></p>
                <a class="ccsve_button button button-success" href="?export=pdf&force">Export to PDF</a>
                <br/>
                <p>Here are some url examples you can use (adjust to your needs before pasting it in your browser):</p>
                <ul>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/?export=pdf&num=1&post_type=portfolio&force"><?php echo get_bloginfo('url'); ?>/?export=pdf&num=1&post_type=portfolio&force</a></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/?export=pdf&post_id=1234&force"><?php echo get_bloginfo('url'); ?>/?export=pdf&post_id=1234&force</a></li>
                    <li></li>
                </ul>

          </div>
        <?php
        } // END public function simple_pdf_export_plugin_settings_page()

    } // END class simple_pdf_export_settings_Settings

} // END if(!class_exists('simple_pdf_export_settings_Settings'))
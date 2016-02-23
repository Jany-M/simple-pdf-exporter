<?php

$wp_path = explode('wp-content', dirname(__FILE__));
require_once($wp_path[0].'wp-load.php');

use Dompdf\Dompdf;

//require_once(__DIR__."/dompdf/dompdf_config.inc.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/
function get_html_cover_page() {
                ob_start();
                echo '<table width="100%" >';
                echo '<tr><th colspan="3" align="center" style="height:300px;" >&nbsp;</th></tr>';
                echo '<tr><th colspan="3" align="center" style="color:#4791FF; font-size:22px; font-family:Arial, sans-serif;"><div style="border-bottom: 2px solid #1975D1;"><div style="padding-left:25px; padding-right:25px; padding-bottom:25px;  text-align:center; font-family:Arial, sans-serif;">Starwood EAME Rate Loading Guidelines</div></div><br/><div style="text-align:center; font-family:Arial, sans-serif;">Updated '.@date('jS F Y').'</div></th></tr>';
                echo '<tr><td colspan="3" style="height:100px;">&nbsp;</td></tr>';
                echo '<tr><th colspan="3" >Proprietary Confidential - Only for Internal use</th></tr>';
                echo '</table>';
                return ob_get_clean();

        }

function cover_page() {

                $upload_dir = wp_upload_dir();
                $newpdfpathname = PDF_EXPORT.'cover_page.pdf';
                $html = get_html_cover_page();
                $version = '';
                $header = '';
                $title = '';
                $header_html = '';
                $footer_html = '';
                $report_type_text = '';
                $report_period_text = '';
                $front_page_html = '';
                
    $dompdf = new DOMPDF(array(
        'enable_font_subsetting' => true,
        'default_media_type' => 'print',
        'default_paper_size' => 'A4',
        'font_height_ratio' => 1,
        'enable_remote' => 1,
        'dpi' => 72,
        //'enable_html5_parser' => 1,
    ));
    //$dompdf->set_paper("A4");

                $dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
                $dompdf->render();
                $file_to_save = $newpdfpathname;
                file_put_contents($file_to_save, $dompdf->output());


        

}

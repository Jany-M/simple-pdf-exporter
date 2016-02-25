<?php
require_once(PDF_PROCESS.'config.php');
use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function get_html_cover_page() {
    global $pdf_export_css_file;
    ob_start();

    echo '<link rel="stylesheet" href="'.$pdf_export_css_file.'" type="text/css" media="print" />';

                echo '<table class="cover_page" width="100%" >';
                echo '<tr><th colspan="3" align="center" style="height:300px;" >&nbsp;</th></tr>';
                echo '<tr><th colspan="3" align="center" style="color:#4791FF; font-size:22px; font-family:Arial, sans-serif;"><div style="border-bottom: 2px solid #1975D1;"><div style="padding-left:25px; padding-right:25px; padding-bottom:25px;  text-align:center; font-family:Arial, sans-serif;">Starwood EAME Rate Loading Guidelines</div></div><br/><div style="text-align:center; font-family:Arial, sans-serif;">Updated '.@date('jS F Y').'</div></th></tr>';
                echo '<tr><td colspan="3" style="height:100px;">&nbsp;</td></tr>';
                echo '<tr><th colspan="3" >Proprietary Confidential - Only for Internal use</th></tr>';
                echo '</table>';
                return ob_get_clean();

        }

function cover_page() {

    global $dompdf_settings;

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
                
    $dompdf = new DOMPDF(array($dompdf_settings));


                $dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
                $dompdf->render();
                $file_to_save = $newpdfpathname;
                file_put_contents($file_to_save, $dompdf->output());


        

}

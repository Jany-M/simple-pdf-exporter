<?php
$wp_path = explode('wp-content', dirname(__FILE__)); 
require_once($wp_path[0].'wp-load.php');
require_once plugin_dir_path(__FILE__) . 'autoload.inc.php';
use Dompdf\Dompdf;

require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
require_once(PDF_LIBS."pageno/pdfnumber.php");
require_once(PDF_LIBS."pageno/pageno.php");
require_once(PDF_LIBS."pdfmerger/pdfmerger.php");

global $pdf_export_check;
function pdf_export(){
    global $pdf_export_check;

    $pdf_export_check = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
    if ($pdf_export_check == 'pdf') {

        if (!file_exists(PDF_EXPORT.'eame_pricing_guidelines-'.date('dMY-H').'.pdf')) {

            /*ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            //error_reporting(E_ALL);
            error_reporting(E_ERROR | E_PARSE);*/
            ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");

            require_once(PDF_PROCESS."config.php");
            require_once(PDF_PROCESS."pdf_layout.php");
            require_once(PDF_PROCESS."generate_pdfs_with_pageno.php");
            require_once(PDF_PROCESS."mergepdf.php");
            require_once(PDF_PROCESS."index_page.php");
            require_once(PDF_PROCESS."cover_page.php");
            require_once(PDF_PROCESS."mergepdffinal.php");

            generate_pdfs_with_pageno();
            mergepdf();
            index_page();
            cover_page();
            mergepdffinal();

            /*ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(-1);*/

        }
        
        $fileloc = PDF_EXPORT.'eame_pricing_guidelines-'.date('dMY-H').'.pdf';
        $filename = 'eame_pricing_guidelines-'.date('dMY-H').'.pdf';
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fileloc));
        header('Accept-Ranges: bytes');
        readfile($fileloc);

        exit();

    }

   
}

?>
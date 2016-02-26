<?php
require_once(PDF_PROCESS.'config.php');

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function final_merge() {

    global $pdf_export_post_type, $pdf_export_all_posts;

    $pdf = new DC_Rate_Plan_Pdf_All_PDFMerger;

    $newpdfpathname1 = PDF_EXPORT.$pdf_export_all_posts;
    $newpdfpathnamecover = PDF_EXPORT.'cover_page.pdf';
    $newpdfpathnameindex = PDF_EXPORT.'index_page.pdf';

    $newpdfpathnamefinal = PDF_EXPORT.$pdf_export_post_type.'_export-'.date('dMY').'.pdf';

    $pdf->addPDF($newpdfpathnamecover, 'all');
    $pdf->addPDF($newpdfpathnameindex, 'all');
    $pdf->addPDF($newpdfpathname1, 'all');
    $pdf->merge('file', $newpdfpathnamefinal);

}
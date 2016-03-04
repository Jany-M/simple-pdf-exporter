<?php

function simple_pdf_export_process(){

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);*/

    //?export=pdf&num=3&post_type=post&force
    
    global $pdf_export_post_type, $pdf_export_force, $pdf_posts_per_page, $html;

    $pdf_export_post_type = isset($_REQUEST['post_type']) && $_REQUEST['post_type'] != '' ? $_REQUEST['post_type'] : 'post';
    $pdf_posts_per_page = isset($_REQUEST['num']) ? $_REQUEST['num'] : -1;
    $pdf_export_check = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
    $pdf_export_force = isset($_REQUEST['force']);

    $final_pdf = SIMPLE_PDF_EXPORTER_EXPORT.$pdf_export_post_type.'_export-'.date('dMY').'.pdf';
    
    if ($pdf_export_check == 'pdf') {

         if ($pdf_export_force || !file_exists($final_pdf) || date("dMY", filemtime($final_pdf)) != date('dMY')) {

            require_once(SIMPLE_PDF_EXPORTER_PROCESS."create_pagenumber_merge.php");
            create_pagenumber_merge();
        }
        
        $filename = $pdf_export_post_type.'_export-'.date('dMY').'.pdf';
        
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($final_pdf));
        header('Accept-Ranges: bytes');
        readfile($final_pdf);

        exit();

    }
   
}

?>
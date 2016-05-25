<?php

function simple_pdf_export_process(){

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);*/

    //?export=pdf&num=3&post_type=post&force
    //?export=pdf&post_id=3&post_type=post&force

    global $pdf_export_post_type, $pdf_export_force, $pdf_posts_per_page, $pdf_export_post_id, $pdf_export_final_pdf;

    $pdf_export_post_type = isset($_REQUEST['post_type']) && $_REQUEST['post_type'] != '' ? $_REQUEST['post_type'] : 'post';
    $pdf_export_post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : '';
    $pdf_posts_per_page = isset($_REQUEST['num']) ? $_REQUEST['num'] : -1;
    $pdf_export_check = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
    $pdf_export_force = isset($_REQUEST['force']);

    $pdf_export_final_pdf = SIMPLE_PDF_EXPORTER_EXPORT.$pdf_export_post_type.SIMPLE_PDF_EXPORTER_EXTRA_FILE_NAME.date('dMY').'.pdf';

    if ($pdf_export_check == 'pdf') {

         if ($pdf_export_force || !file_exists($pdf_export_final_pdf) || date("dMY", filemtime($pdf_export_final_pdf)) != date('dMY')) {
         	require_once(SIMPLE_PDF_EXPORTER_PROCESS."create_pagenumber_merge.php");
            create_pagenumber_merge();
        }

        $filename = $pdf_export_post_type.SIMPLE_PDF_EXPORTER_EXTRA_FILE_NAME.date('dMY').'.pdf';

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($pdf_export_final_pdf));
        header('Accept-Ranges: bytes');
        readfile($pdf_export_final_pdf);

        exit();

    }

}

?>

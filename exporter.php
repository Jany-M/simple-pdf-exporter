<?php

$pdf_export_post_type = '';
global $pdf_export_post_type;

function pdf_export(){
    
    global $pdf_export_post_type;
    $pdf_export_post_type = isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : 'post';
    $pdf_export_check = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';
    $final_pdf = PDF_EXPORT.$pdf_export_post_type.'_export-'.date('dMY').'.pdf';
    
    if ($pdf_export_check == 'pdf') {

        if (isset($_REQUEST['force']) || !file_exists($final_pdf) || date("dMY", filemtime($final_pdf)) != date('dMY')) {

            require_once(PDF_PROCESS."create_pagenumber_merge.php");
            require_once(PDF_PROCESS."index_page.php");
            require_once(PDF_PROCESS."cover_page.php");
            require_once(PDF_PROCESS."final_merge.php");

            create_pagenumber_merge();
            index_page();
            cover_page();
            final_merge();

        }
        
        $final_pdf = PDF_EXPORT.$pdf_export_post_type.'_export-'.date('dMY').'.pdf';
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
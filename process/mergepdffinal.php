<?php

$wp_path = explode('wp-content', dirname(__FILE__)); 
require_once($wp_path[0].'wp-load.php');
require_once(PDF_LIBS."pdfmerger/pdfmerger.php");
/*require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
require_once(PDF_LIBS."pageno/pageno.php");*/

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function mergepdffinal() {

                $pdf = new DC_Rate_Plan_Pdf_All_PDFMerger;

                $newpdfpathname1 = PDF_EXPORT.'all_rate_plan.pdf';
                $newpdfpathnamecover = PDF_EXPORT.'cover_page.pdf';
                $newpdfpathnameindex = PDF_EXPORT.'index_page.pdf';
                
                $t = @date('d-m-Y-H-i-s');
                //$pret = get_option('time_generated_pdf'); // for time open it
                //$newpdfpathnamefinal_pre = $newpdfpath.'/'.'all_rate_plan_final_'.$pret.'.pdf';        // for time open it

                //update_option('time_generated_pdf',$t); // for time open it
                //$newpdfpathnamefinal = $newpdfpath.'/'.'all_rate_plan_final_'.$t.'.pdf';  // for time open it
                //$newpdfpathnamefinal = $newpdfpath.'/'.'all_rate_plan_final.pdf'; // OLD FILE NAME

                $newpdfpathnamefinal = PDF_EXPORT.'eame_pricing_guidelines-'.date('dMY-H').'.pdf'; // for time comment it

		// PAOLO: disabled in favor of the exec() down below
                $pdf->addPDF($newpdfpathnamecover, 'all');
                $pdf->addPDF($newpdfpathnameindex, 'all');
                $pdf->addPDF($newpdfpathname1, 'all');
                $pdf->merge('file', $newpdfpathnamefinal);
		

		// PAOLO: pdfunite fa parte del pacchetto poppler-unite
		//exec("/bin/pdfunite '" . $newpdfpathnamecover . "' '" . $newpdfpathnameindex . "' '" . $newpdfpathname1 . "' '" . $newpdfpathnamefinal . "'");

}
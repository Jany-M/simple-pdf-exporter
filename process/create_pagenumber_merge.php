<?php
require_once(PDF_PROCESS.'config.php');
use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function create_pagenumber_merge() {

	global $pdf_export_post_type, $posts_per_page, $pdf_export_all_posts, $dompdf_settings, $pdf_html;

	// Merge Settings
	$pdf2 = new PAGENO;
	$newpdfpathname = PDF_EXPORT.$pdf_export_all_posts;
	$pno = 1;
	$pdf = new DC_Rate_Plan_Pdf_All_PDFMerger;

	// Page Number Settings
	$page_offset = 0;
	update_option('running_time_for_pdf',date('d-m-Y H:i:s'));
	function add_page_no($newpdfpathname, $newpdfpathname2, $offset=0) {
		$pdf123= new PDF();
	    $pdf123->offset = $offset;
	    $pagecount = $pdf123->setSourceFile($newpdfpathname);
	    
	    for ($i=1; $i <= $pagecount; $i++) {
	        $tplidx = $pdf123->ImportPage($i);
	        $size = $pdf123->getTemplateSize($tplidx);

	        if ($size['w'] > $size['h']) {
	            $pdf123->AddPage('L', array($size['w'], $size['h']));
	        } else {
	            $pdf123->AddPage('P', array($size['w'], $size['h']));
	        }
	        
	        //$pdf->addPage();
	        $pdf123->useTemplate($tplidx);
	    }
	    
	    $pdf123->Output($newpdfpathname2,"F");

		unset($pdf123);
		gc_collect_cycles();
		return $pagecount;
	}


	// Check for Cached Posts
	//delete_transient('simple_pdf_export_posts');
	//if(get_transient('simple_pdf_export_posts') === false) {

		// The Query
		$pdf_query_args = array(
			'posts_per_page'   => $posts_per_page,
			'post_type' => $pdf_export_post_type,
			'post_status'      => 'publish',
			//'post_parent'      => 0,
		);

		$pdf_query = new WP_Query( $pdf_query_args );
		//Cache Results
		set_transient('simple_pdf_export_posts', $pdf_query, 23 * HOUR_IN_SECONDS );
	//}

	$pdf_query = get_transient('simple_pdf_export_posts');

	// Get the Posts
	if ( $pdf_query->have_posts() ) : while ( $pdf_query->have_posts() ) : $pdf_query->the_post(); 
			
		//$file_to_save = PDF_EXPORT_FILES.$post->ID.'-'.date('dMY-H').'.pdf';
		$file_to_save = PDF_EXPORT_FILES.$post->ID.'.pdf';
		//if (!file_exists(PDF_EXPORT_FILES.$file_to_save)) {

			$html = get_all_rate_plan($post->ID,$term);
			$file_to_save_temp = $file_to_save.'.temp';
				
			// WRITE TO HTML FILES - DEBUG ONLY
			if($pdf_html == true) {
				$file_to_save2 = PDF_EXPORT_HTML.$post->ID.'-'.date('dMY');
				$myfile = fopen($file_to_save2.".html", "w") or die("Unable to open file!");
				$txt = $html;
				fwrite($myfile, $txt);			
				fclose($myfile);
			}

			/*$version = '';
			$header = '';
			$title = '';
			$header_html = '';
			$footer_html = '';
			$report_type_text = '';
			$report_period_text = '';		
			$front_page_html = '';*/		
			
			// DOMPDF	
			$dompdf = new DOMPDF(array($dompdf_settings));
			$dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
			$dompdf->render();		

			//save the temporary pdf file on the server
			file_put_contents($file_to_save_temp, $dompdf->output());			

			//save final pdf with page number
			$page_offset += add_page_no($file_to_save_temp, $file_to_save, $page_offset);
			unlink($file_to_save_temp);

			// Merger Stuff
			update_post_meta($post->ID, 'pdf_page_no', $pno);
			$pdf->addPDF($file_to_save, 'all');
			$pagecount = $pdf2->setSourceFile($file_to_save);
			$pno += $pagecount;

		//}

	endwhile;  wp_reset_postdata();  endif;	

	// Merge all pdfs in one
	$pdf->merge('file', $newpdfpathname);

}
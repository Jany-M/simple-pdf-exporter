<?php
require_once(PDF_PROCESS.'config.php');
use Dompdf\Dompdf;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);

function create_pagenumber_merge() {

	global $wp_query, $pdf_export_post_type, $pdf_posts_per_page, /*$dompdf_settings, */$pdf_export_force;

	$newpdfpathname = PDF_EXPORT.$pdf_export_post_type.'_export-'.date('dMY').'.pdf';

	// Merge Settings
	$pdf = new DC_Rate_Plan_Pdf_All_PDFMerger;

	// Page Number Settings
	if(PDF_EXPORT_PAGINATION) {
		$pdf2 = new PAGENO;
		$pno = 1;
		$page_offset = 0;
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
	}

	// Check for Cached Posts
	if($pdf_export_force = true)
		delete_transient('simple_pdf_export_posts');

	if(get_transient('simple_pdf_export_posts') === false) {

		// The Query
		$pdf_query_args = array(
			'posts_per_page'   => $pdf_posts_per_page,
			'post_type' => $pdf_export_post_type,
			'post_status'      => 'publish',
			//'post_parent'      => 0,
		);

		$pdf_query = new WP_Query( $pdf_query_args );

		//Cache Results
		set_transient('simple_pdf_export_posts', $pdf_query, 23 * HOUR_IN_SECONDS );
	}

	$pdf_query = get_transient('simple_pdf_export_posts');

	// Get the Posts
	if ($pdf_query->have_posts()) : while ($pdf_query->have_posts()) : $pdf_query->the_post();
		
		$post_id = get_the_ID();

		$file_to_save = PDF_EXPORT_FILES.$post_id.'.pdf';
		
		if ($pdf_export_force = true || !file_exists($file_to_save) || date("dMY-H", filemtime($file_to_save)) != date('dMY-H')) {

			$html = create_pdf_layout($post,$term);
			
			// DOMPDF	
			//$dompdf = new DOMPDF(array($dompdf_settings));
			$dompdf = new DOMPDF();
			$dompdf->setPaper(DOMPDF_PAPER_SIZE, DOMPDF_PAPER_ORIENTATION);
		    $options = $dompdf->getOptions();
		    $options->set(array(
		        'isHtml5ParserEnabled' => DOMPDF_ENABLE_HTML5,
		        'isRemoteEnabled' => DOMPDF_ENABLE_REMOTE,
		        'dpi' => DOMPDF_DPI,
		        'isFontSubsettingEnabled' => DOMPDF_ENABLE_FONTSUBSETTING,
		        'defaultMediaType' => DOMPDF_MEDIATYPE,
		        'fontHeightRatio' => DOMPDF_FONTHEIGHTRATIO
		    ));
	      	$dompdf->setOptions($options);

			$dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
			$dompdf->render();


			if(PDF_EXPORT_PAGINATION) {
				$file_to_save_temp = $file_to_save.'.temp';
				//save the temporary pdf file on the server
				file_put_contents($file_to_save_temp, $dompdf->output());
				//save final pdf with page number
				$page_offset += add_page_no($file_to_save_temp, $file_to_save, $page_offset);
				// Remove temp file
				unlink($file_to_save_temp);
			} else {
				//save the pdf file on the server
				file_put_contents($file_to_save, $dompdf->output());	
			}

			// WRITE TO HTML FILES - DEBUG ONLY
			if(PDF_EXPORT_HTML_OUTPUT) {
				$file_to_save2 = PDF_EXPORT_HTML.$post_id.'.html';
				$myfile = fopen($file_to_save2, "w") or die("Unable to open file!");
				$txt = $html;
				fwrite($myfile, $txt);			
				fclose($myfile);
			}
	
		}

		if(PDF_EXPORT_PAGINATION) {
			// Pagination Stuff
			update_post_meta($post_id, 'pdf_export_page_no', $pno);
			$pagecount = $pdf2->setSourceFile($file_to_save);
			$pno += $pagecount;
		}

		// Create PDF of single post
		$pdf->addPDF($file_to_save, 'all');
		//$dompdf->stream($file_to_save, array('Attachment'=>'0')); // this opens it directly, in the same window

	endwhile; 

	else:
	wp_die('This post type: '.$pdf_export_post_type.' doesn\'t have any posts!<br/>Try to add to your URL &post_type=slug-of-post-type and make sure there actually are published posts of that type.', 'Simple PDF Exporter');

	wp_reset_postdata();  endif;

	// Merge all PDFs in one
	$pdf->merge('file', $newpdfpathname);

}
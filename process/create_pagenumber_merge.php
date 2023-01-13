<?php
require_once(SIMPLE_PDF_EXPORTER_PROCESS.'config.php');
use Dompdf\Dompdf;
use PDFMerger\PDFMerger; // Added by Lomix

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);*/

function create_pagenumber_merge() {

	global 	$pdf_export_post_type,
            $pdf_export_force,
            $pdf_posts_per_page,
            $pdf_export_post_id,
            $pdf_export_final_pdf;

	// Get post type in case the post_id parameter was used, but not the post_type one
	if($pdf_export_post_id != '' && $pdf_export_post_type = 'post') {
		$pdf_export_post_type = get_post_type($pdf_export_post_id);
	}

	// Merge Settings
	//$pdf = new DC_Rate_Plan_Pdf_All_PDFMerger; // Removed by Lomix
	$pdf = new PDFMerger; // Added by Lomix

	// Page Number Settings
	if(SIMPLE_PDF_EXPORTER_PAGINATION && $pdf_export_post_id == '' && $pdf_posts_per_page > 1) {
		$pdf2 = new PAGENO;
		$pno = 1;
		$page_offset = 0;
		function add_page_no($pdf_export_final_pdf, $newpdfpathname2, $offset=0) {
			$pdf123= new PDF();
		    $pdf123->offset = $offset;
		    $pagecount = $pdf123->setSourceFile($pdf_export_final_pdf);

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
	if($pdf_export_force === true)
		delete_transient('simple_pdf_export_posts');

	// Check if transient has expired or doesnt exist
	if (get_option('_transient_timeout_simple_pdf_export_posts') < time() || get_transient('simple_pdf_export_posts') == false) {

		// The Query
		if($pdf_export_post_id != '') { // Get a specific Post
			$pdf_query_args = array(
				'p'   => $pdf_export_post_id,
				'post_type' => $pdf_export_post_type
			);
		} else {
			$pdf_query_args = array( // Get all Posts
				'posts_per_page'   => $pdf_posts_per_page,
				'post_type' => $pdf_export_post_type,
				'post_status'      => 'publish',
				//'post_parent'      => 0,
			);
		}

		$pdf_query = new WP_Query( $pdf_query_args );

		//Cache Results
		set_transient('simple_pdf_export_posts', $pdf_query, 23 * HOUR_IN_SECONDS );
	}

	$pdf_query = get_transient('simple_pdf_export_posts');

	// Get the Posts
	if ($pdf_query->have_posts()) : while ($pdf_query->have_posts()) : $pdf_query->the_post();

		global $post;
		setup_postdata($post);

		$post_id = get_the_ID();
		$file_to_save = SIMPLE_PDF_EXPORTER_EXPORT.'pdf/'.$post_id.'.pdf';

		if ($pdf_export_force === true || !file_exists($file_to_save) || date("dMY-H", filemtime($file_to_save)) != date('dMY-H')) {
			
			$term = isset($term) ? $term : '';
			$html = create_pdf_layout($post,$term);

			// WRITE TO HTML FILES - DEBUG ONLY
			if(SIMPLE_PDF_EXPORTER_HTML_OUTPUT || SIMPLE_PDF_EXPORTER_DEBUG) {
				$file_to_save2 = SIMPLE_PDF_EXPORTER_EXPORT.'html/'.$post_id.'.html';
				$myfile = fopen($file_to_save2, "w") or die("Unable to open file!");
				$txt = $html;
				fwrite($myfile, $txt);
				fclose($myfile);
			}

			// DOMPDF
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

			if(SIMPLE_PDF_EXPORTER_PAGINATION && $pdf_export_post_id == '' && $pdf_posts_per_page > 1) {
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

		}

		if(SIMPLE_PDF_EXPORTER_PAGINATION && $pdf_export_post_id == '' && $pdf_posts_per_page > 1) {
			// Pagination Stuff
			update_post_meta($post_id, 'pdf_export_page_no', $pno);
			$pagecount = $pdf2->setSourceFile($file_to_save);
			$pno += $pagecount;
		}

		// Create PDF of single post
		$pdf->addPDF($file_to_save, 'all');
		//$dompdf->stream($file_to_save, array('Attachment'=>'0')); // this opens it directly, in the same window

		wp_reset_postdata(); wp_reset_query();

	endwhile;

	else:
	wp_die('The post type "'.$pdf_export_post_type.'"" doesn\'t have any posts!<br/>Try to add to your URL &post_type=slug-of-post-type and make sure there actually are published posts of that type.', 'Simple PDF Exporter');

	endif;

	$pdf->merge('file', $pdf_export_final_pdf);

}

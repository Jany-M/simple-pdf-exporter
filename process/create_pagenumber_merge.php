<?php
require_once(PDF_PROCESS.'config.php');
use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function create_pagenumber_merge() {

	global $pdf_export_all_posts, $dompdf_settings, $pdf_html;

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

	// Check for Cached Taxonomy Terms
	//delete_transient('simple_pdf_export_taxterms');
	if(get_transient('simple_pdf_export_taxterms') === false) {
		$taxonomies = array( 
			'rate-type',			
		);
		$args = array(
			'orderby'           => 'name', 
			'order'             => 'ASC',
			'hide_empty'        => true, 
			'exclude'           => array(), 
			'exclude_tree'      => array(), 
			'include'           => array(),
			'number'            => '', 
			'fields'            => 'all', 
			'slug'              => '',
			'parent'            => '',
			'hierarchical'      => true, 
			'child_of'          => 0,
			'childless'         => false,
			'get'               => '', 
			'name__like'        => '',
			'description__like' => '',
			'pad_counts'        => false, 
			'offset'            => '', 
			'search'            => '', 
			'cache_domain'      => 'core'
		);
		$terms = get_terms($taxonomies, $args);
		//Cache Results
		set_transient('simple_pdf_export_taxterms', $terms, 23 * HOUR_IN_SECONDS );
	}
	$terms = get_transient('simple_pdf_export_taxterms');
	
	foreach( $terms as $term ) {
		$term_transient = 'simple_pdf_export_taxterms'.$term->term_id;
		//delete_transient($term_transient);
		// Check for Cached Posts of Terms
		if(get_transient($term_transient) === false) {
			$args2 = array(
				'posts_per_page'   => $posts_per_page,
				'post_type' => 'rate-plan',
				'order' => 'ASC',
				'orderby' => 'meta_value',
				'meta_key' => 'wpcf-rate-plan-id',
				'post_status'      => 'publish',
				'post_parent'      => 0,
				'tax_query' => array(
					array(
						'taxonomy' => 'rate-type',
						'field' => 'name',
						'terms' => $term->name
					)
				)
			);
			$posts_array = get_posts( $args2 );
			//Cache Results
			set_transient($term_transient, $posts_array, 23 * HOUR_IN_SECONDS );
		}
		$posts_array = get_transient($term_transient);	

		// Get the Posts
		foreach( $posts_array as $post ) : setup_postdata( $post );
			
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

		endforeach;			
		wp_reset_postdata();	
	}

	// Merge all pdfs in one
	$pdf->merge('file', $newpdfpathname);

}
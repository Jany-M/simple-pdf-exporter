<?php

$wp_path = explode('wp-content', dirname(__FILE__));
require_once($wp_path[0].'wp-load.php');
require_once(PDF_PROCESS.'config.php'); //posts_per_page setting
require_once(PDF_PROCESS.'pdf_layout.php');
require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
require_once(PDF_LIBS."pageno/pdfnumber.php");

use Dompdf\Dompdf;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function generate_pdfs_with_pageno() {

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

	// Check for Cached Taxonomy Terms
	foreach( $terms as $term ) {
		$args2 = array(
			'posts_per_page'   => -1,
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

		foreach( $posts_array as $post ) : setup_postdata( $post );
			
			$file_to_save = PDF_EXPORT_FILES.$post->ID.'-'.date('dMY-H').'.pdf';
			
			if (!file_exists(PDF_EXPORT_FILES.$file_to_save)) {

				$html = get_all_rate_plan($post->ID,$term);
				$file_to_save_temp = $file_to_save.'.temp';
				
				// WRITE TO HTML FILES - DEBUG ONLY
				/*
				if (!is_dir(PDF_EXPORT_HTML) || !file_exists(PDF_EXPORT_HTML)) {
			        mkdir(PDF_EXPORT_HTML, 0777, true);
			    }
				$file_to_save2 = PDF_EXPORT_HTML.$post->ID.'-'.date('dMY-H');
				$myfile = fopen($file_to_save2.".html", "w") or die("Unable to open file!");
				$txt = $html;
				fwrite($myfile, $txt);			
				fclose($myfile);*/

				$version = '';
				$header = '';
				$title = '';
				$header_html = '';
				$footer_html = '';
				$report_type_text = '';
				$report_period_text = '';		
				$front_page_html = '';		
			
				$dompdf = new DOMPDF(array(
					'enable_font_subsetting' => true,
					'default_media_type' => 'print',
					'default_paper_size' => 'A4',
					'font_height_ratio' => 1,
					'enable_remote' => 1,
					'dpi' => 72,
					//'enable_html5_parser' => 1,
				));
				//$dompdf->set_paper("A4");

				$dompdf->load_html(stripslashes(preg_replace('/\s{2,}/', '', $html)));
				$dompdf->render();		

				//save the temporary pdf file on the server
				file_put_contents($file_to_save_temp, $dompdf->output());			

				//save final pdf with page number
				$page_offset += add_page_no($file_to_save_temp, $file_to_save, $page_offset);
				unlink($file_to_save_temp);

			}

		endforeach;			
		wp_reset_postdata();	
	}

}
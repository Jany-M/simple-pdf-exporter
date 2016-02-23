<?php

$wp_path = explode('wp-content', dirname(__FILE__)); 
require_once($wp_path[0].'wp-load.php');
require_once(PDF_PROCESS."config.php"); //$posts_per_page definition
require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
require_once(PDF_LIBS."pdfmerger/pdfmerger.php");
require_once(PDF_LIBS."pageno/pageno.php");

//use Dompdf\Dompdf;
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("error_log", "/var/log/php-fpm/pdf-gen.log");*/

function mergepdf() {

	$pdf2 = new PAGENO;

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
		
		$newpdfpathname = PDF_EXPORT.'all_rate_plan.pdf';
		$pno = 1;

		$pdf = new DC_Rate_Plan_Pdf_All_PDFMerger;

		foreach( $terms as $term ) {
			$args2 = array(
				// CHANGE VALUE OF RATE PLANS HERE FOR TESTING -1 IS ALL - $posts_per_page
				'posts_per_page'   => -1,
				'post_type' => 'rate-plan',
				'order' => 'ASC',
				'orderby' => 'meta_value',
				'meta_key' => 'wpcf-rate-plan-id',
				'post_status'      => 'publish',
				// only get parents, not children - if hierarchical
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

				$pdfname = PDF_EXPORT_FILES.$post->ID.'-'.date('dMY-H').'.pdf';
				//print $post->ID."\n";
				
				if (file_exists($pdfname)) {
					update_post_meta($post->ID, 'pdf_page_no', $pno);
					$pdf->addPDF($pdfname, 'all');
					$pagecount = $pdf2->setSourceFile($pdfname);
					$pno += $pagecount;
				}
				else {
					print "ERROR";
				}

			endforeach;
			
			wp_reset_postdata();	
		}
		$pdf->merge('file', $newpdfpathname);

}
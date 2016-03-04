<?php

/* -------------------------------------------------------------------------------- 
*
* CUSTOM VARS & SETUP
*
-------------------------------------------------------------------------------- */

global  $pdf_posts_per_page, 
		$pdf_export_post_type,
		$pdf_export_css_file,
		$pdf_export_force;

if(!file_exists(SIMPLE_PDF_EXPORTER_CSS_FILE))
	$pdf_export_css_file = esc_url(plugins_url('assets/pdf_export.css', dirname(__FILE__)));

/* -------------------------------------------------------------------------------- 
*
* INCLUDES
*
-------------------------------------------------------------------------------- */

require_once SIMPLE_PDF_EXPORTER_LIBS.'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once(SIMPLE_PDF_EXPORTER_PROCESS.'pdf_layout.php');
require_once(SIMPLE_PDF_EXPORTER_LIBS."fpdf/fpdf.php");
require_once(SIMPLE_PDF_EXPORTER_LIBS."fpdi/fpdi.php");
require_once(SIMPLE_PDF_EXPORTER_LIBS."fpdi_addon/annots.php");
if (SIMPLE_PDF_EXPORTER_PAGINATION ) {
	require_once(SIMPLE_PDF_EXPORTER_LIBS."pageno/pdfnumber.php");
	require_once(SIMPLE_PDF_EXPORTER_LIBS."pageno/pageno.php");
}
require_once(SIMPLE_PDF_EXPORTER_LIBS."pdfmerger/pdfmerger.php");
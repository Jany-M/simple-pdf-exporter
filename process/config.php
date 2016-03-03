<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_PARSE);*/

/* -------------------------------------------------------------------------------- 
*
* CUSTOM VARS & SETUP
*
-------------------------------------------------------------------------------- */

global  $pdf_posts_per_page, 
		$pdf_export_post_type,
		$pdf_export_css_file,
		$pdf_export_force;

if(!file_exists(PDF_EXPORT_CSS_FILE))
	$pdf_export_css_file = esc_url(plugins_url('assets/pdf_export.css', dirname(__FILE__)));

/* -------------------------------------------------------------------------------- 
*
* INCLUDES
*
-------------------------------------------------------------------------------- */

require_once PDF_LIBS.'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once(PDF_PROCESS.'pdf_layout.php');
require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
if (PDF_EXPORT_PAGINATION ) {
	require_once(PDF_LIBS."pageno/pdfnumber.php");
	require_once(PDF_LIBS."pageno/pageno.php");
}
require_once(PDF_LIBS."pdfmerger/pdfmerger.php");

/* -------------------------------------------------------------------------------- 
*
* DOMPDF
*
-------------------------------------------------------------------------------- */

//global $dompdf_settings;

/*set_base_path(get_stylesheet_directory_uri());
$this->setFontDir($this->chroot . "/lib/fonts");
$this->setFontCache($this->getFontDir());*/

/*$dompdf_settings = array(
	'enable_font_subsetting' => true,
	'default_media_type' => 'print',
	'default_paper_size' => 'A4',
	'font_height_ratio' => 1,
	'enable_remote' => true,
	'dpi' => 72,
	//'enable_html5_parser' => 1, // not working well
);*/
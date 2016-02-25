<?php

/* -------------------------------------------------------------------------------- 
*
* CUSTOM VARS & SETUP
*
-------------------------------------------------------------------------------- */

$wp_path = explode('wp-content', dirname(__FILE__));
require_once($wp_path[0].'wp-load.php');

require_once PDF_LIBS.'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once(PDF_PROCESS.'pdf_layout.php');
require_once(PDF_LIBS."fpdf/fpdf.php");
require_once(PDF_LIBS."fpdi/fpdi.php");
require_once(PDF_LIBS."fpdi_addon/annots.php");
require_once(PDF_LIBS."pageno/pdfnumber.php");
require_once(PDF_LIBS."pageno/pageno.php");
require_once(PDF_LIBS."pdfmerger/pdfmerger.php");

$posts_per_page = -1;

/* -------------------------------------------------------------------------------- 
*
* DOMPDF
*
-------------------------------------------------------------------------------- */

global $dompdf_settings;

/*set_base_path(get_stylesheet_directory_uri());
$this->setFontDir($this->chroot . "/lib/fonts");
$this->setFontCache($this->getFontDir());*/

$dompdf_settings = array(
	'enable_font_subsetting' => true,
	'default_media_type' => 'print',
	'default_paper_size' => 'A4',
	'font_height_ratio' => 1,
	'enable_remote' => true,
	'dpi' => 72,
	//'enable_html5_parser' => 1, // not working well
);

/* -------------------------------------------------------------------------------- 
*
* VARIOUS
*
-------------------------------------------------------------------------------- */

global $pdf_export_post_type, $pdf_export_all_posts, $pdf_export_css_file, $pdf_html;

$pdf_html = false;
if($pdf_html == true) {
	if (!is_dir(PDF_EXPORT_HTML) || !file_exists(PDF_EXPORT_HTML)) {
		mkdir(PDF_EXPORT_HTML, 0777, true);
	}
}
$pdf_export_all_posts = 'all_posts.pdf';
$pdf_export_css = get_stylesheet_directory_uri().'/assets/css/pdf.css';
if(!file_exists($pdf_export_css)) {
	$pdf_export_css_file = get_bloginfo('url').'/wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css';
}
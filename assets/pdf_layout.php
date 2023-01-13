<?php
require_once(SIMPLE_PDF_EXPORTER_PROCESS.'config.php');
global $pdf_export_css_file;

if(!function_exists('create_pdf_layout')) {

	function create_pdf_layout($post, $term ) {
		global $post, $pdf_export_css_file;

		$html = '';

		ob_start();
		?>

		<!-- HEADER -->
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
		<head>
		<title>PDF EXPORT</title>
		<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo $pdf_export_css_file; ?>" />

		</head>

		<body class="pdf_export_body">

		<!-- CONTENT -->
		<div class="pdf_export_wrapper main_div">
			
			<h2><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>

			<p class="pdf_export_p"><?php echo $post->post_content; ?></p>

		</body>
		</html>

		<?php

		return ob_get_clean();
	}

}

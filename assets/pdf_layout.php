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
		<link rel="stylesheet" type="text/css" href="<?php echo $pdf_export_css_file; ?>" />
		</head>

		<body>

		<!-- CONTENT -->
		<div class="main_div">
			
				<h2><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>

				$post->post_content.

		<!--  FOOTER -->
		</body>
		</html>

		<?php
		return ob_get_clean();
	}

}

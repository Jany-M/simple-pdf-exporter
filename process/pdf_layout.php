<?php 
require_once(PDF_PROCESS.'config.php');

function get_all_rate_plan($post_id, $term ) {
	global $pdf_export_css_file;

	$html = '';
	$post = get_post($post_id);
	ob_start();

	echo '<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
		<head>
		<link rel="stylesheet" type="text/css" href="'.$pdf_export_css_file.'" />
		</head>
		<body>';

	echo '<div class="main_div">';

	echo '<div class="post_wrapper" ><a href="'.get_permalink($post->ID).'">'.get_permalink($post->ID).'</a></div>';
		
		echo '<table cellspacing="0" width="100%"">';
			echo '<tr>';
			echo '<td>';
			echo '<h2>'.$post->post_title.'</h2>';		
			echo '</td>';
			echo '</tr>';			

				echo '<table cellspacing="0" width="100%">';
				echo '<tr>';
				echo '<td>';
				echo '<p>'.$post->post_content.'</p>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';

			echo "</td>";
			echo "</tr>";

		echo "</table>";		
	
	echo "</div>";
	echo "</body></html>";
	
	return ob_get_clean();
}
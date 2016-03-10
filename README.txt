=== Simple PDF Exporter ===

Contributors: dukessa, Shambix
Tags: pdf, dompdf, exporter, export, custom post types, export pdf, pdf collection, create pdf
Requires at least: 4
Tested up to: 4.4.1
Stable tag: trunk
Author: Shambix
Author URI: http://www.shambix.com
Plugin URI: https://wordpress.org/plugins/simple-pdf-exporter/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

Export a single PDF with all posts, or custom post types.

> **IMPORTANT** This plugin requires at least 512MB of free RAM available, or it will timeout / return an error.

= The Basics =

The plugin checks if a pdf already exists with the same date (ddMonyear), if yes, the existing pdf will be served, otherwise a new will be generated. Since the PDF generation uses up a lot of resources, this will prevent too many runs of the plugin and the crashing of your server.
Check the example below or the FAQ for ways to force the PDF generation anyway.

**Depending on how many posts you have, it might take from a few seconds to several minutes for a new PDF to be generated.**

> If no PDF is generated you probably don't have enough server resources. **This can't be fixed, as PDF libraries are very resource-hungry.**
Ask your hosting to check how many resources you would need to run the plugin and if there is anything you can do, within your hosting limits, to make sure the plugin has enough or appropriate RAM/PHP settings.

If you don't use a custom url, hence you don't add the `post_type` parameter to the url, the default post type exported will always be WP default `post`.

You can add a frontend link or button on your frontend and customize it, like in this example for Twitter Bootstrap:

`<?php // Check if PDF Export Plugin exists first
if( function_exists('pdf_export')) { ?>
	<ul class="nav nav-tabs pull-right pdf_export_menu">
		<li role="presentation" class="dropdown">
		<a class="dropdown-toggle btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Export Posts to PDF <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<?php
					$final_pdf = SIMPLE_PDF_EXPORTER_EXPORT.'post_export-'.date('dMY').'.pdf';
					if(file_exists($final_pdf)) {
						$file_date = date("d M Y - H:i", filemtime($final_pdf));
						?>
						<li><a class="" href="?export=pdf" target="_blank">Download Existing Version <small>(<?php echo $file_date; ?> GMT)</small></a></li>
				<?php } ?>
				<li><a class="" href="?export=pdf&force" target="_blank">Generate New Version <small>(might take several minutes)</small></a></li>
			</ul>
		</li>
	</ul>
<?php } ?>`

= The PDF Template =

Currently, the template for the each exported post is very basic (and a table, since floating doesn't play nicely with the DOMPDF library), feel free to edit it here `wp-content/plugins/simple-pdf-exporter/process/pdf_layout.php`, **only if you know what you are doing**.
Your layout must be echoed in php. eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

You can use a custom CSS to customize the layout.
Create a pdf_export.css in your theme folder, otherwise the plugin's default (and really basic) CSS will be used (you can find it here `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css`)

= Questions? =

Check the FAQ before opening new threads in the forum!

> Contact me if you want a **custom version of the plugin**, for a fee (contact form at [shambix.com](http://www.shambix.com)).

= Libraries & Credits =

* [DOMPDF](https://github.com/dompdf/dompdf)
* [FPDF](http://www.fpdf.org/)
* [FPDI](https://www.setasign.com/products/fpdi/about/)
* [FPDI ADDON](https://gist.github.com/2020422)

== Installation ==

1. Download and activate the plugin.
2. In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser. You can also make the url into a link or button on your site, to use on frontend, please check the FAQ.

== Frequently Asked Questions ==

= I activated the plugin but can't see any difference. What do I do? =
In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser.
You can also make the url into a link or button on your site, to use on frontend.

= How do I make a button or link for the frontend? =
You can add a frontend link or button on your frontend and customize it, like in this example for Twitter Bootstrap:
Change the parameters to suit your needs.

`<?php // Check if PDF Export Plugin exists first
if( function_exists('pdf_export')) { ?>
	<ul class="nav nav-tabs pull-right pdf_export_menu">
		<li role="presentation" class="dropdown">
		<a class="dropdown-toggle btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Export Posts to PDF <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<?php
					$post_type_to_export = 'portfolio';
					$final_pdf = SIMPLE_PDF_EXPORTER_EXPORT.$post_type_tp_export'_export-'.date('dMY').'.pdf';
					if(file_exists($final_pdf)) {
						$file_date = date("d M Y - H:i", filemtime($final_pdf));
						?>
						<li><a class="" href="?export=pdf&post_type=portfolio&num=3" target="_blank">Download Existing Version <small>(<?php echo $file_date; ?> GMT)</small></a></li>
				<?php } ?>
				<li><a class="" href="?export=pdf&post_type=portfolio&num=3&force" target="_blank">Generate New Version <small>(might take several minutes)</small></a></li>
			</ul>
		</li>
	</ul>
<?php } ?>`

= I want to limit the amount of posts =
Use `&num=x` after your url
eg. `http://yoursite.com/?export=pdf&num=3`

= I want to create a PDF from a custom post type =
Use `&post_type=x`
eg. `http://yoursite.com/?export=pdf&post_type=your-post-type-slug`

= How do I customize the layout of the posts in the pdf? =
You need to change the file `pdf_layout.php` inside the plugin folder `wp-content/plugins/simple-pdf-exporter/process/pdf_layout.php`
Your layout must be echoed in php. eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

= I want to override the existing file / generate a new pdf no matter what =
Use `&force` after your url
eg. `http://yoursite.com/?export=pdf&force`

= Can I use a custom CSS file? =
You can use a custom CSS to customize the layout.
Create a file named pdf_export.css in your theme folder, otherwise the plugin's default (and really basic) CSS will be used (you can find it here `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css`)

= Is there some other option I can change? =
Yes there's a few. In order to change them, add them to your `wp-config.php` file.
eg. define(SIMPLE_PDF_EXPORTER_PAGINATION', true);

Here's the full list and what they are set to, by default:

* define(SIMPLE_PDF_EXPORTER_PAGINATION', false);
* define(SIMPLE_PDF_EXPORTER_HTML_OUTPUT', false);
* define(SIMPLE_PDF_EXPORTER_CSS_FILE', get_stylesheet_directory_uri().'/pdf_export.css');
* define('DOMPDF_PAPER_SIZE', 'A4');
* define('DOMPDF_PAPER_ORIENTATION', 'portrait');
* define('DOMPDF_DPI', 72);
* define('DOMPDF_ENABLE_REMOTE', true);
* define('DOMPDF_ENABLE_HTML5', false);
* define('DOMPDF_ENABLE_FONTSUBSETTING', true);
* define('DOMPDF_MEDIATYPE', 'print');
* define('DOMPDF_FONTHEIGHTRATIO', 1);


== Changelog ==

= 1.7 (3 mar 2016) =
* Cleaned up everything
* Added constants i/o vars
* Added more options
* Simplified process

= 1.5 (25 feb 2016) =
* Updated DOMPDF 0.7
* Updated FPDF 1.81
* Updated FPDI 1.6.1

= 1.4 =
* Added support for custom post types
* Added force generation files
* Added check if file already exists in the same day (dont create it again)
* Added caching in wp queries
* Heavy use of single config
* Re-wrote plugin activation - deactivation
* Created WP event cron job
* Create html versions too, on request (config)
* Added custom css file, as well as pre-made one (instead of inline css)
* Merged posts pdf creation with page number and posts merge

= 1.0 (23 feb 2016) =
* Initial release on Github 
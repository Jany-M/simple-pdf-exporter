=== Simple PDF Exporter ===

Contributors: dukessa, Shambix
Tags: pdf, dompdf, exporter, export, custom post types
Requires at least: 4
Tested up to: 4.4.1
Stable tag: trunk
Author: Shambix
Author URI: http://www.shambix.com
Plugin URI: https://github.com/Jany-M/simple-pdf-exporter
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This plugin uses the DOMPDF Library to generate PDFs for whole post types, with index page.
There might be a Pro version with more options, check back in a while or email me at infoATshambix.com if you want a custom version of it, for a fee.

The plugin checks if a pdf already exists with the same date (ddMonyear), if yes, the existing pdf will be served, otherwise a new will be generated. Since the PDF generation uses up a lot of resources, this will prevent too many runs of the plugin and the crashing of your server.
Check the example below or the FAQ for ways to force the PDF generation anyway.

Depending on how many posts you have, it might take from a few seconds to several minutes for a new PDF to be generated.

"If no PDF is generated you probably don't have enough server resources. This can't be fixed, as PDF libraries are very resource-hungry. Ask your hosting to check how many resources you would need to run the plugin and if there is anything you can do, within your hosting limits, to make sure the plugin has enough or appropriate RAM/PHP settings."

You can add a frontend link or button on your frontend and customize it, like in this example for Twitter Bootstrap:

`<?php // Check if PDF Export Plugin exists first
if( function_exists('pdf_export')) { ?>
	<ul class="nav nav-tabs pull-right pdf_export_menu">
		<li role="presentation" class="dropdown">
		<a class="dropdown-toggle btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Export Posts to PDF <span class="caret"></span></a>
			<ul class="dropdown-menu">
				<?php
					$final_pdf = PDF_EXPORT.'post_export-'.date('dMY').'.pdf';
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

"If you don't use a custom url, hence you don't add the `post_type` parameter to the url, the default post type exported will always be WP default `post`."

Currently, the template for the each exported post is very basic (and a table, since floating doesn't play nicely with the DOMPDF library), feel free to edit it here `wp-content/plugins/simple-pdf-exporter/process/pdf_layout.php`
Your layout must be echoed in php. eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

You can use a custom CSS to customize the layout.
Create a pdf.css in your theme folder, otherwise the plugin's default (and really basic) CSS will be used (you can find it here `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css`)

= Libraries & Credits =

* [DOMPDF](https://github.com/dompdf/dompdf)
* [FPDF](http://www.fpdf.org/)
* [FPDI](https://www.setasign.com/products/fpdi/about/)
* [FPDI ADDON](https://gist.github.com/2020422)

== Installation ==

1. Download and activate the plugin.
2. In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser. You can also make the url into a link or button on your site, to use on frontend.

== Frequently Asked Questions ==

= I activated the plugin but can't see any difference. What do I do? =
In order to export a pdf, you can do it from backend from Tools -> PDF Export or enter `http://yoursite.com/?export=pdf` in your browser.
You can also make the url into a link or button on your site, to use on frontend.

= I want to create a PDF from a custom post type =
Use `&post_type=your-post-type-slug`
eg. `http://yoursite.com/?export=pdf&post_type=your-post-type-slug`

= How do I customize the layout of the posts in the pdf? =
For now, you need to change the file pdf_layout.php inside the plugin folder `wp-content/plugins/simple-pdf-exporter/process/pdf_layout.php`
Your layout must be echoed in php. eg. `echo '<div>the content goes here</div>';` or it won't show up in the PDF.

= I want to override the cache / generate a new pdf no matter what =
Use &force after your url
eg. `http://yoursite.com/?export=pdf&force`

= Can I use a custom CSS file? =
Yes.
You can use a custom CSS to customize the layout.
Create a pdf.css in your theme folder, otherwise the plugin's default (and really basic) CSS will be used (you can find it here `wp-content/plugins/simple-pdf-exporter/assets/pdf_export.css`)


== Changelog ==

= 1.5 (25 feb 2015) =
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

= 1.0 (23 feb 2015) =
* Initial release on Github 
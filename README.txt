=== Simple PDF Exporter ===
Contributors: dukessa, Shambix, Jany-M
Tags: pdf, dompdf, exporter, export, custom post types
Requires at least: 4
Tested up to: 4.4.1
Stable tag: trunk
Author: Shambix
Author URI: http://www.shambix.com
Plugin URI: https://github.com/Seravo/wp-pdf-templates
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

NOT SUITABLE FOR PRODUCTION SITES YET.
STILL CONTAINS VARIOUS HARDCODED THINGS.

This plugin uses the DOMPDF Library to generate PDFs for whole post types, with cover page and index page, with a custom order (by custom field & name).

= Libraries & Credits =

* [DOMPDF](https://github.com/dompdf/dompdf)
* [FPDF](http://www.fpdf.org/)
* [FPDI](https://www.setasign.com/products/fpdi/about/)
* [FPDI ADDON](https://gist.github.com/2020422)

== Installation ==

1. Download and activate the plugin.

== Frequently Asked Questions ==

= I activated the plugin but can't see any difference. What do I do? =


= I don't like the way my PDF printing looks. How do I change it? =

= I want to override the cache / generate a new pdf no matter what =
Use &force after your url
eg. http://yoursite.com/?export=pdf&force

= I want to create a PDF from a custom post type =
Use &post_type=your-post-type-slug
eg. http://yoursite.com/?export=pdf&post_type=your-post-type-slug


== Screenshots == 


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
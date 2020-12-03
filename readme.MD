The WordPress Code Manager plugin was built solely for this site, however I am releasing it open source in case others find it useful as well. The plugin does quite a many thing and much of it cool behind the scenes stuff. For instance, each project (especially if for a programming site) may require a different template to show different aspects of the project. For instance, in building this site, I realized that some projects, depending on the technology used, etc, would require custom templating on top of our standard code detail template. One of the cool features of this plugin is that you are able to create a tpl/code-manager directory in your theme and the plugin will scan that directory for all PHP files. It will look through these files for the string “Template Name: ‘. It will then use the filename of the file and this template name to allow you to specify individual templates for individual code posts. Some features are shown on the screenshots and others detail in the documentation section. Also note that if a demo page is necessary, I wrote a new rewrite rule that will automatically add a /demo URL segment. This address will load up a template in theme_dir/tpl/code-manager/ called project-slug-demo.php. As an example for this code post, if a demo page existed then it would be available at http://www.whateverdomain.com/code/wordpress-code-manager/demo. This happens automatically as long as, for this example, there is a template called theme_dir/tpl/code-manager/wordpress-code-manager-demo.php. This is not necessary to use but may come in handy. This plugin also comes with a shortcode for formatting code with syntax highlighting, using the hightlight.js library. To use this shortcode, wrap your code in the [code][/code] shortcode.

### How To Install

1.  Extract the zip and upload to your wp-content/plugins directory
2.  In your WP Admin Dashboard, go to Plugins and click Activate

### How To Use

Use is exactly like any standard page or post except for the following added functionality:

#### Code Details Metabox

On the right hand side of the Add Code or Edit Code pages, you will notice new Code Details, Screenshots and Code Documentation metaboxes. These are detailed below:

*   <span>Template</span> - The template selection allows you to select a different template for a particular code post. While it is possible to modify the default template, if you would like to use theme specific files, this is the best way to do this. The plugin will look in your theme directory under the tpl/code-manager/ subdirectories for PHP files. It will then scan the file for the string "Template Name: ". The plugin will then allow you to choose these plugins when creating a new code post.
*   <span>Code File</span> - This is simply the file you would like to post for download. The automatically generated download link will usually be a zip file of compressed source files but it could be any downloadable file.

#### Code Documentation Metabox

Every piece of code needs some sort of documentation to describe it. This is where the HTML for this section will go. For instance, this entire code documentation section that you are looking at right now is the same area. One notable piece of code involved, is a javascript (in plugin_dir/assets/js/frontend.js) snippet I wrote that will find all h3s and h4s in the documentation area and it will generate the menu you see above based on the content of the h3 or h4\. Every code detail page you look at on this site will have this menu, which is purely automatically generated.

#### Screenshots

Screenshots are always useful when showing off a piece of code or WordPress plugin, etc. This is where this section comes in handy. This particular template uses the first image for the code listing page and hides said image for the code detail template. As this plugin is completely customizable, regarding templating, this can be changed easily and at will.

### Shortcodes

*   Code Highlighting - Entering the [[code]] shortcode will allow you enter code into a container with syntax highlighting. Simply encapsulate your code in a [[code][/code]] block and the plugin will do the rest.

### Plugin API and Methods

All plugin methods are available through the global variable $code_manager (found in plugin's init.php file). Methods are detailed below:

### Methods List Coming Soon!

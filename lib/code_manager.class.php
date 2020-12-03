<?php
require_once('code_manager_model.class.php');
/*******************************************************************************
 * Define our initial class
 ******************************************************************************/
class Code_Manager {
				//Instantiate our private variables
				private $model;
				
				//Instantiate our public variables
				public $plugin_path, $plugin_uri, $post, $code;
				
				/*******************************************************************************
				 * Instantiate our constructor
				 ******************************************************************************/
				public function __construct(){
								//Call the init function
								$this->init();
				}
				
				/*******************************************************************************
				 * Perform initialization functions
				 ******************************************************************************/
				public function init(){
								//Instantiate our model
								$this->model = new Code_Manager_Model();
								
								//Init paths
								$this->plugin_path = plugin_dir_path(__FILE__).'../';
								$this->plugin_uri = plugin_dir_url(__FILE__).'../';
								
								//Init hooks
								$this->init_hooks();
								
								//Init filters
								$this->init_filters();
								
								//Init shortcode
								$this->init_shortcode();
				}
				
				/*******************************************************************************
				 * Initializes the hooks for the plugin
				 ******************************************************************************/
				public function init_hooks(){
								//Add custom post type
								add_action('init', array(&$this, 'register_custom_post_type'));
								
								//Add custom taxonomies for our custom post type
								add_action('init', array(&$this, 'register_custom_taxonomies'));
        
        //Adds the query var functionality
        add_action('init', array(&$this, 'init_rewrite_rules'));
								
								//Add custom menu entries to the custom post type menu
								add_action('admin_menu', array(&$this, 'admin_menu'));
								
								//Get our post object during the wp_head action
								add_action('wp', array(&$this, 'wp_head'));
								
								//Include scripts and styles for the admin
								add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
								
								//Include scripts and styles for the frontend
								add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
								
								//Add meta boxes to the custom post type editor screen
								add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'), 1);
								
								//Save the meta data when a post is saved
								add_action('save_post', array(&$this, 'save_post'));
								
								//Add custom columns to the post listing page for our custom post type
								add_action('manage_posts_custom_column', array(&$this, 'manage_pages_custom_column'), 10, 2);
								
								//Set up Ajax action for pages that need to retrieve the data on demand
								add_action('wp_ajax_get_code', array(&$this, 'ajax_get_code'));
								add_action('wp_ajax_nopriv_get_code', array(&$this, 'ajax_get_code'));
								
								//Set up Ajax action for retrieving code by type ID
								add_action('wp_ajax_get_code_by_type', array(&$this, 'ajax_get_code_by_type'));
								add_action('wp_ajax_nopriv_get_code_by_type', array(&$this, 'ajax_get_code_by_type'));
				}
				
				/*******************************************************************************
				 * Initializes the filters for the plugin
				 ******************************************************************************/
				public function init_filters(){
								//Add messages for the code post type
								add_filter('post_updated_messages', array(&$this, 'post_updated_messages'));
								
								//Filter for code listing
								add_filter('manage_code_columns' , array(&$this, 'manage_code_columns'));
								
								//Create filter for overriding default template when displaying single code
								add_filter('template_include', array(&$this, 'template_include'));
        
        //Add filter for adding query variables
        add_filter('query_vars', array(&$this, 'query_vars'), 10, 1);
				}
				
				/*******************************************************************************
				 * Initializes the shortcode
				 ******************************************************************************/
				public function init_shortcode(){
								//Add a shortcode for displaying the code
								add_shortcode('code_manager_display', array(&$this, 'shortcode_code_manager_display'));
        
        //Add a shortcode for highlighting code on the post content
								add_shortcode('code', array(&$this, 'shortcode_code'));
				}
				
				/*******************************************************************************
				 * Adds custom post types to the WP DB
				 ******************************************************************************/
				public function register_custom_post_type(){
								register_post_type(CODE_MANAGER_POST_TYPE, array(
												'labels' => array(
																'name'               => 'Code',
																'singular_name'      => 'Code',
																'menu_name'          => 'Code',
																'name_admin_bar'     => 'Code',
																'add_new'            => 'Add New',
																'add_new_item'       => 'Add New Code',
																'new_item'           => 'New Code',
																'edit_item'          => 'Edit Code',
																'view_item'          => 'View Code',
																'all_items'          => 'All Code',
																'search_items'       => 'Search Code',
																'parent_item_colon'  => 'Parent Code:',
																'not_found'          => 'No code found.',
																'not_found_in_trash' => 'No code found in Trash.',
												),
												'hierarchical' => true,
												'public' => true,
												'show_ui' => true,
												'show_admin_column' => true,
												'show_in_nav_menus' => false,
												'show_tagcloud' => false,
												'menu_icon' => $this->plugin_uri.'/assets/images/code.png',
												'rewrite' => array('slug' => 'code'),
												'supports' => array('title', 'editor', 'thumbnail')
								));
				}
				
				/*******************************************************************************
				 * Registers custom taxonomies such as categories for our custom post type
				 ******************************************************************************/
				public function register_custom_taxonomies(){
								//Register the initial taxonomy
								register_taxonomy(CODE_MANAGER_TERM, CODE_MANAGER_POST_TYPE, array(
												'labels'                => array(
																'name'                       => 'Code Types',
																'singular_name'              => 'Code Type'
												),
												'query_var'             => true,
												'rewrite'               => array('slug' => CODE_MANAGER_TERM),
												'hierarchical' => true,
												'public' => true,
												'show_ui' => true,
												'show_admin_column' => true,
												'show_in_nav_menus' => false,
												'show_tagcloud' => false
								));
								
								//Add the default "Miscellaneous" term
								wp_insert_term('Miscellaneous', CODE_MANAGER_TERM);
				}
    
    /*******************************************************************************
				 * Adds rewrite rule for custom query vars
				 ******************************************************************************/
    public function init_rewrite_rules() {
        //Add rewrite rule for demo query var 
        add_rewrite_rule(
            '^code/([^/]+)/([^/]+)/?',
            'index.php?pagename=code&code=$matches[1]&subpage=$matches[2]',
            'top'
        );
    }
    
    /*******************************************************************************
				 * Adds rewrite tag for custom query vars
				 ******************************************************************************/
    public function register_rewrite_tags(){
        add_rewrite_tag('%subpage%', '[a-z]');
    }
    
    /*******************************************************************************
				 * Adds query vars
				 ******************************************************************************/
    public function query_vars($query_vars) {
        $query_vars[] = 'subpage';
        return $query_vars;
    }
				
				/*******************************************************************************
				 * Adds custom menu entries to our custom post type
				 ******************************************************************************/
				public function admin_menu(){
								
				}
				
				/*******************************************************************************
				 * Adds messages for the custom post type
				 ******************************************************************************/
				public function post_updated_messages($messages){
								$post = get_post();
								$post_type = get_post_type($post);
								$post_type_object = get_post_type_object($post_type);
							 if ($post_type==CODE_MANAGER_POST_TYPE){
												$messages[CODE_MANAGER_POST_TYPE] = array(
																0  => '', // Unused. Messages start at index 1.
																1  => 'Code updated.',
																2  => 'Custom field updated.',
																3  => 'Custom field deleted.',
																4  => 'Code updated.',
																5  => isset($_GET['revision']) ? sprintf('Code restored to revision from %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
																6  => 'Code published.',
																7  => 'Code saved.',
																8  => 'Code submitted.',
																9  => sprintf(
																				'Code scheduled for: <strong>%1$s</strong>.',
																				// translators: Publish box date format, see http://php.net/date
																				date_i18n('M j, Y @ G:i'), strtotime($post->post_date)),
																10 => 'Code draft updated.'
												);
											
												if ($post_type_object->publicly_queryable){
																$permalink = get_permalink($post->ID);
																
																$view_link = sprintf(' <a href="%s">%s</a>', esc_url($permalink), 'View code');
																$messages[$post_type][1] .= $view_link;
																$messages[$post_type][6] .= $view_link;
																$messages[$post_type][9] .= $view_link;
																
																$preview_permalink = add_query_arg('preview', 'true', $permalink);
																$preview_link = sprintf(' <a target="_blank" href="%s">%s</a>', esc_url($preview_permalink), 'Preview code');
																$messages[$post_type][8]  .= $preview_link;
																$messages[$post_type][10] .= $preview_link;
												}
								}
								
								return $messages;
				}
				
				/*******************************************************************************
				 * Add custom columns to the post listing for our custom post type
				 ******************************************************************************/
				public function manage_code_columns($columns){
								//Add the new items to the array
								return array_merge($columns, array(
												'zip-url' => 'Zip URL',
												'demo-url' => 'Demo URL'
								));
				}
				
				/*******************************************************************************
				 * Adds content to the columns added in the function above
				 ******************************************************************************/
				public function manage_pages_custom_column($column, $post_id){
								//Get the post object for this post ID
								$post = get_post($post_id);
								
								//Check which column is being rendered
								switch($column){
												case 'demo-url':
																echo get_permalink($post->ID).'/demo';
																break;
												case 'zip-url':
																echo get_post_meta($post_id, 'zip-url', true);
																break;
								}
				}
				
				/*******************************************************************************
				 * Override default display template
				 ******************************************************************************/
				public function template_include($template){
								global $post;
        
								//Get the subpage
        $subpage = get_query_var('subpage');
        
        //Verify we have a subpage to load
        $subpage = empty($subpage) ? false : $subpage;
        
        //Check if this post has a specific template assigned
        $custom_tpl = get_post_meta($post->ID, 'code-manager-tpl', true);
        
								//Generate paths for different templates
								$plugin_tpl = $this->plugin_path.'/tpl/frontend/code-detail'.($subpage ? '-'.$subpage : '').'.php';
        
								//Add meta data to post
								$post->meta = $this->model->get_code_meta($post->ID);
								
								//Determine which template to override
								switch(basename($template)){
												case 'single.php':
												case 'singular.php':
																$template = empty($custom_tpl) ? $plugin_tpl : $custom_tpl;
																break; 
								}
                                                        
								return $template;
				}
				
				/*******************************************************************************
				 * Puts the post in a global plugin variable and get attachments
				 ******************************************************************************/
				public function wp_head(){
								global $wp_query, $post;
								
								//Set the post object
								$this->post = isset($wp_query->post) ? $wp_query->post : new stdClass();
								if (isset($this->post->ID)){
												//Get the post meta data
												$this->post->meta = $this->model->get_code_meta($this->post->ID);
												
												//Set the global post object to be the plugin's post object
												$post = $this->post;
								}
    }
				
				/*******************************************************************************
				 * Registers scripts and styles to be placed in the admin header
				 ******************************************************************************/
				public function admin_enqueue_scripts(){
								//Set the script dependencies
								$deps = array('jquery');
								
								//Enqueue scripts
								wp_enqueue_media();
								wp_enqueue_script('code-manager-admin-script', $this->plugin_uri.'assets/js/admin.js', $deps);
								
								//Register styles
								wp_enqueue_style('code-manager-admin-style', $this->plugin_uri.'assets/css/admin.css');
				}
				
				/*******************************************************************************
				 * Registers scripts and styles to be placed in the frontend header
				 ******************************************************************************/
				public function wp_enqueue_scripts(){
								//Set the script dependencies
								$deps = array('jquery');
								
								//Enqueue the styles after they're registered
        wp_enqueue_style('code-manager-highlightjs-style', '//cdn.jsdelivr.net/highlight.js/8.9.1/styles/xcode.min.css');
								wp_enqueue_style('code-manager-frontend-style', $this->plugin_uri.'assets/css/frontend.css');
        wp_enqueue_style('code-manager-frontend-responsive-style', $this->plugin_uri.'assets/css/frontend-responsive.css');
								
								//Enqueue the scripts after they're registered
        wp_enqueue_script('code-manager-highlightjs-script', '//cdn.jsdelivr.net/highlight.js/8.9.1/highlight.min.js', $deps);
								wp_enqueue_script('code-manager-frontend-script', $this->plugin_uri.'assets/js/frontend.js', $deps);
				}
				
				/*******************************************************************************
				 * Adds custom meta boxes to the custom post type editor screen
				 ******************************************************************************/
				public function add_meta_boxes(){
        add_meta_box('code-manager-docs', 'Code Documentation', array(&$this, 'display_meta_box_docs'), 'code', 'normal', 'core');
        add_meta_box('code-manager-images', 'Screenshots', array(&$this, 'display_meta_box_images'), 'code', 'normal', 'core');
        add_meta_box('code-manager-details', 'Code Details', array(&$this, 'display_meta_box_details'), 'code', 'side', 'core');
				}
				
				/*******************************************************************************
				 * Displays the custom meta boxes
				 ******************************************************************************/
				public function display_meta_box_details($post){
								global $wpdb;
        
        //Instantiate the arrays
        $templates = array();
								
								//Get meta data
								$meta = get_post_meta($post->ID);
								
								//Loop through all meta keys
								foreach($meta as $key=>$val){
												$data[$key] = $val[0];
								}
        
								//Loop through files in template directory
        foreach(glob(get_template_directory().'/tpl/code-manager/*.php') as $tpl){
            //Open file and check if this contains the template line
            if ($is_tpl = $this->fgrep($tpl, 'Template Name: ', true)){
                //Extract the template name from the line
                $tpl_name = str_replace('Template Name: ', '', strstr($is_tpl, 'Template Name: '));
                
                //Push the template information to the templates array
                array_push($templates, array('filename' => $tpl, 'tpl_name' => $tpl_name));
            }
        }
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-details.php');
				}
    
    /*******************************************************************************
				 * Displays the custom meta boxes
				 ******************************************************************************/
				public function display_meta_box_images($post){
								//Init data array
								$data = array(
												'images' => get_post_meta($post->ID, 'code-manager-images', true),
												'featured-image' => get_post_meta($post->ID, 'code-manager-featured-image', true)
								);
								
								//Process data
								$data['featured-image'] = !empty($data['featured-image']) ? $data['featured-image'][0] : $data['featured-image'];
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-images.php');
				}
    
    /*******************************************************************************
				 * Displays the documentation meta box
				 ******************************************************************************/
				public function display_meta_box_docs($post){
								//Get the documentation HTML
        $docs = get_post_meta($post->ID, 'code-manager-docs', true);
								
								//Include the template
								include($this->plugin_path.'tpl/admin/metabox-docs.php');
				}
				
				/*******************************************************************************
				 * Saves the meta box data when a post is saved
				 ******************************************************************************/
				public function save_post($post_id, $post_obj = ''){
								//If the post object is not set, get it from the provided post ID
								$post_obj = empty($post_obj) ? get_post($post_id) : $post_obj;
								
								//Make sure this is for our custom post type
								if ($post_obj->post_type=='code'){
												//Update the post meta data
            if (isset($_POST['code-manager-tpl']) && !empty($_POST['code-manager-tpl'])) update_post_meta($post_id, 'code-manager-tpl', $_POST['code-manager-tpl']);
												if (isset($_POST['code-manager-zip-url']) && !empty($_POST['code-manager-zip-url'])) update_post_meta($post_id, 'code-manager-zip-url', $_POST['code-manager-zip-url']);
            if (isset($_POST['code-manager-zip-url']) && !empty($_POST['code-manager-github-link'])) update_post_meta($post_id, 'code-manager-github-link', $_POST['code-manager-github-link']);
            if (isset($_POST['code-manager-docs-editor']) && !empty($_POST['code-manager-docs-editor'])) update_post_meta($post_id, 'code-manager-docs', $_POST['code-manager-docs-editor']);
            if (isset($_POST['code-manager-images']) && !empty($_POST['code-manager-images'])) update_post_meta($post_id, 'code-manager-images', $_POST['code-manager-images']); else update_post_meta($post_id, 'code-manager-images', '');
												if (isset($_POST['code-manager-featured-image']) && !empty($_POST['code-manager-featured-image'])) update_post_meta($post_id, 'code-manager-featured-image', $_POST['code-manager-featured-image']);
								}
				}
				
				/*******************************************************************************
				 * Template tag for returning code types
				 ******************************************************************************/
				public function get_code_types(){
								return $this->model->get_code_types();
				}
				
				/*******************************************************************************
				 * Template tag for returning code
				 ******************************************************************************/
				public function get_code($code_id = 0, $numposts = -1){
								return $code_id==0 ? $this->model->get_all_code($numposts) : $this->model->get_code_by_id($code_id);
				}
				
				/*******************************************************************************
				 * Template tag for returning code by code type
				 ******************************************************************************/
				public function get_code_by_type($type, $numposts = -1){
								return $this->model->get_code_by_type($type, $numposts);
				}
				
				/*******************************************************************************
				 * Template tag for returning current featured code
				 ******************************************************************************/
				public function get_featured_code(){
								return $this->model->get_featured_code();
				}
				
				/*******************************************************************************
				 * Action for retrieving code through Ajax
				 ******************************************************************************/
				public function ajax_get_code(){
								//Process POST data
								$code_id = isset($_POST['code_id']) ? $_POST['code_id'] : 0;
								$numposts = isset($_POST['numposts']) ? $_POST['numposts'] : -1;
								
								//Get the code information
								$code = isset($code_id) && !empty($code_id) ? $this->model->get_code_by_id($code_id) : $this->model->get_all_code($numposts);
								
								//Send the Ajax response with the code information
								wp_send_json($code);
				}
				
				/*******************************************************************************
				 * Action for retrieving code by type through Ajax
				 ******************************************************************************/
				public function ajax_get_code_by_type(){
								//Process POST data
								$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0;
								$numposts = isset($_GET['numposts']) ? $_GET['numposts'] : -1;
								
								//Get the code information
								$code = $this->model->get_code_by_type($type_id, $numposts);
								
								//Send the Ajax response with the code information
								wp_send_json($code);
				}
				
				/*******************************************************************************
				 * Shortcode for displaying code
				 ******************************************************************************/
				public function shortcode_code_manager_display($atts){
								//Extract the shortcode attributes
								extract(shortcode_atts(array(
												'numposts'        => -1,
												'template'        => 'list',
												'type'            => '',
												'id'              => '',
												'size'            => 'normal',
												'transition'      => 'true',
												'transition_type' => 'flip' //Options are fade and flip
								), $atts));
								
								//Process the boolean variables
								$transition = ($transition=='false' ? false : ($transition=='true' ? true : false));
								
								//Determine if we are looking for code by type
								if (!empty($type)){ //Search by type
            $code = $this->get_code_by_type($type, $numposts);
								} elseif (!empty($id)){ //Search by id
            $code = $this->get_code($id, $numposts);
								} else { //Just search
            $code = $this->model->get_all_code();
								}
								
								//Check if there is a template in the theme directory
								$tpl_path = get_template_directory().'/code-manager/tpl/code-listing-'.$template.'-'.$size.'.php';
								$plugin_path = $this->plugin_path.'/tpl/frontend/code-listing-'.$template.'-'.$size.'.php';
								
								//Begin the output buffer so we can save the template HTML as a variable
								ob_start();
								
								//Include the template file
								include(file_exists($tpl_path) ? $tpl_path : $plugin_path);
								
								//Save the contents of the output buffer to a variable
								$html = ob_get_contents();
								
								//Close the output buffer and clear it
								ob_end_clean();
								
								return $html;
				}
    
    /*******************************************************************************
				 * Shortcode for displaying code
				 ******************************************************************************/
				public function shortcode_code($atts, $content = ''){
								return '<pre><code>'.$content.'</code></pre>';
				}
    
    /*******************************************************************************
				 * Looks for string in file and, if found, returns line number. Otherwise false
				 ******************************************************************************/
    public function fgrep($file, $string, $return_line = false){
        //Open file for reading
        $handle = fopen($file, 'r');
        
        //Init the valid flag
        $valid = false;
        
        //Init counter to keep track of line string is found on
        $line = 0;
        
        //Loop through each line of the file until the string is found
        while (($buffer = fgets($handle)) !== false) {
            //Check if the string is found
            if (strpos($buffer, $string) !== false) {
                //If so, set to be valid and break out of the loop to save memory and processor usage
                $valid = true;
                break;
            }
            
            //Increment line counter if file is not found
            $line++;
        }
        
        //Close the file and free the handle
        fclose($handle);
        
        //Return line number and line if the string was found. Otherwise false.
        return $valid ? $line.($return_line ? ': '.$buffer : $line) : false;
    }
				
				/*******************************************************************************
				 * Prints out a formatted variable
				 ******************************************************************************/
				public function print_r($var, $echo = true){
								//Generate HTML
								$html = '<pre>'.$print_r($var, true).'</pre>';
								
								//Return or echo new text
								if ($echo) echo $html; else return $html;
				}
}
?>
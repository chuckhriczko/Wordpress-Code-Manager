<?php
/*******************************************************************************
 * This model class is used to separate all the data related processes from
 * the plugin logic
 ******************************************************************************/
class Code_Manager_Model {
				/*******************************************************************************
				 * Get all of the taxonomies associated with our custom post type
				 ******************************************************************************/
				public function get_code_types(){
								return get_terms(CODE_MANAGER_TERM, array(
												'orderby'       => 'name', 
												'order'         => 'ASC',
												'hide_empty'    => false, 
												'hierarchical'  => true
								));
				}
				
				/*******************************************************************************
				 * Get all of the code
				 ******************************************************************************/
				public function get_all_code($numposts = -1, $sort = 'ASC', $orderby = 'rand'){
								global $wpdb;

								//Generate the query parameters
								$args = array(
												'post_type' => 'code',
												'post_status' => 'publish',
												'orderby' => $orderby,
												'order' => $sort,
												'posts_per_page' => $numposts
								);
								
								//Get the code
								$code = new WP_Query($args);
								$code = $code->posts;
								
								//Loop through each code
								foreach($code as $key=>$cur_code){
												//Get code types for this code
												$code[$key]->types = $this->get_code_types_by_id($cur_code->ID);
												
												//Get meta data for the code
												$code[$key]->meta = $this->get_code_meta($cur_code->ID);
								}
								
								//If there is only one result, change to a single dimensional array
								//$code = count($code)==1 ? $code[0] : $code;
								
								return $code;
				}
				
				/*******************************************************************************
				 * Get code by ID
				 ******************************************************************************/
				public function get_code_by_id($post_id = 0){
								//Get code
								$code = get_post($post_id);
								
								//Get code types for this code
								$code->types = $this->get_code_types_by_id($code->ID);
								
								//Get meta data for the code
								$code->meta = $this->get_code_meta($code->ID);
								
								return $code;
				}
				
				/*******************************************************************************
				 * Get code by type
				 ******************************************************************************/
				public function get_code_by_type($type_id = 0, $numposts = -1){
								global $wpdb;
					
								//If the code type is a string, get the ID
								$tax_field = is_int($type_id) ? 'term_id' : 'name';
								
								//Get code
								$code = get_posts(array(
									'posts_per_page'    => $numposts,
									'post_type'			=> CODE_MANAGER_POST_TYPE,
									'tax_query'			=> array(array(
										'taxonomy' => CODE_MANAGER_TERM,
										'field'    => 'id',
										'terms'    => $type_id
									))
								));
								
								//Init return array
								$return_array = array();
								
								//Loop through each code
								foreach($code as $key=>$cur_code){
												//Process content
												$cur_code->post_content = apply_filters('the_content', $cur_code->post_content);
												
												//Generate an excerpt of the content
												$code[$key]->excerpt = strlen($cur_code->post_content)>200 ? substr($cur_code->post_content, 0, 199).'&hellip;' : $cur_code->post_content;
																
												//Get the taxonomy for the current code
												$code[$key]->terms = wp_get_post_terms($cur_code->ID, CODE_MANAGER_TERM);
												
												//Loop through all the terms
												foreach($code[$key]->terms as $term){
																//Check if the term is associated with this code
																if ($term->term_id==$type_id){
																				//Get code types for this code
																				$code[$key]->types = $this->get_code_types_by_id($cur_code->ID);
																				
																				//Get meta data for the code
																				$code[$key]->meta = $this->get_code_meta($cur_code->ID);
																												
																				//Add this code to the return array
																				array_push($return_array, $code[$key]);
																				break;
																}
												}
								}
								
								//Return the post(s)
								return $return_array;
				}
				
				/*******************************************************************************
				 * Get code by type
				 ******************************************************************************/
				public function get_code_types_by_id($id = 0){
								return wp_get_post_terms($id, CODE_MANAGER_TERM);
				}
				
				/*******************************************************************************
				 * Get code metadata
				 ******************************************************************************/
				public function get_code_meta($post_id = 0){
								global $wpdb;
								
								//Get meta data
								$code_meta = get_post_meta($post_id);
												
								//Loop through meta and remove the multidimensional array
								foreach($code_meta as $key=>$meta){
												$code_meta[$key] = current($meta);
								}

								//Get the code types
								$code_meta['types'] = get_the_terms($post_id, 'code_type');
								
								//Init the type slugs array
								$code_meta['type_slugs'] = array();
								
								//Verify we have code types
								if (!empty($code_meta['types'])){
												//Loop through types and generate the type slugs array
												foreach($code_meta['types'] as $type){
																array_push($code_meta['type_slugs'], $type->slug);
												}
								}
								
								//Get the permalink
								$code_meta['permalink'] = get_permalink($post_id);
								
								//Unserialize images
								$code_meta['code-manager-images'] = is_array($code_meta['code-manager-images']) ? $code_meta['code-manager-images'] : unserialize($code_meta['code-manager-images']);
								
								return $code_meta;
				}
}
?>
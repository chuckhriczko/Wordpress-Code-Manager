<?php global $code_manager; ?>
<div class="code-manager-container">
				<aside class="code-manager-menu">
								<ul>
												<li class="active"><a href="#cat-all">All</a></li>
												<?php
												foreach($code_manager->get_code_types() as $type){
																?>
																<li><a href="#type-<?php echo $type->term_id; ?>"><?php echo $type->name; ?></a></li>
																<?php
												}
												?>
								</ul>
				</aside>
				<div class="code-manager-code-list">
								<?php
								//Verify we have code
								if (empty($code)){
												//Display no code message
												echo '<h3>No code projects were found.</h3>';
								} else {
												//Loop through all the code
												foreach($code as $key=>$cur_code){
																//Process content
																$cur_code->post_content = apply_filters('the_content', $cur_code->post_content);
																
																//Generate an excerpt of the content
																$excerpt = strlen($cur_code->post_content)>200 ? substr($cur_code->post_content, 0, 199).'&hellip;' : $cur_code->post_content;
																?>
																<article class="code-manager-code-item">
																				<h3><a href="<?php echo get_permalink($cur_code->ID); ?>" title="<?php echo do_shortcode($cur_code->post_content); ?>"><?php echo $cur_code->post_title; ?></a></h3>
																				<a class="code-img" href="<?php echo get_permalink($cur_code->ID); ?>" title="<?php echo do_shortcode($cur_code->post_content); ?>"><img src="<?php echo $cur_code->meta['code-manager-images'][0]; ?>" alt="<?php echo $cur_code->post_title; ?>" /></a>
																				<div class="code-description"><?php echo $excerpt; ?></div>
																</article>
																<?php
																/* ?>
																<article id="code-manager-code-<?php echo $key; ?>" class="code-manager-code-item">
																				<h3>
																								<a href="<?php echo get_permalink($cur_code->ID); ?>"><?php echo $cur_code->post_title; ?></a>
																								<span class="code-manager-header-icons">
																												<?php
																												foreach($cur_code->types as $type){
																																?><img src="<?php echo $code_manager->plugin_uri.'/assets/images/'.$type->slug.'.png'; ?>" alt="<?php echo $type->name; ?>" /><?php
																												}
																												?>
																								</span>
																				</h3>
																				<div class="code-manager-code-description">
																								<?php echo $excerpt; ?>
																								<div class="code-manager-view-code-links">
																												<a href="<?php echo get_permalink($cur_code->ID); ?>">View</a> |
																												<a href="<?php echo $cur_code->meta['code-manager-zip-url']; ?>" target="_blank">Download</a>
																								</div>
																				</div>
																</article>
																<?php*/
												}
								}
								?>
				</div>
</div>
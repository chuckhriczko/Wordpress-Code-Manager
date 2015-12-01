<?php
/*********************************************************
 * Custom template for displaying a single code item
 *********************************************************/
global $post, $code_manager;

//Include the theme header
get_header();
?>

<section id="content-container" class="code-detail-container page-content">
	<section id="content-body">
		<section id="code-detail-body">
			<h2>
				<?php echo $post->post_title; ?>
				<span class="code-detail-technologies">
					<ul class="horizontal-list">
						<?php
						//Loop through the technologies used
						foreach($post->meta['types'] as $type){
							?><li><img src="<?php echo $code_manager->plugin_uri; ?>/assets/images/<?php echo $type->slug; ?>.png" alt="<?php echo $type->name; ?>" title="<?php echo $type->name; ?>" /></li><?php
						}
						?>
					</ul>
				</span>
			</h2>
			<div class="code-detail-links"><a href="<?php echo $post->meta['code-manager-zip-url']; ?>" target="_blank">Download Code</a></div>
			<div class="code-detail-text">
				<?php echo apply_filters('the_content', $post->post_content); ?>
				<h3 class="code-detail-header">Screenshots:</h3>
				<ul id="code-detail-images" class="horizontal-list">
					<?php
					foreach($post->meta['code-manager-images'] as $key=>$image){;
						//For template purposes, ignore the first image
						if ($key>0){
							?><li><a rel="lightbox" href="<?php echo $image; ?>" class="code-detail-image" title="<?php echo $post->post_title; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $post->post_title; ?>" width="240" height="280" /></a></li><?php
						}
					}
					?>
				</ul>
				<h3 id="code-detail-docs" class="code-detail-header">Documentation:</h3>
				<div id="code-detail-docs-content"><?php echo apply_filters('the_content', $post->meta['code-manager-docs']); ?></div>
				<br class="cl" />
			</div>
		</section>
	</section>
</section>
<?php get_footer(); ?>
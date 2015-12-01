<?php if (count($data['images']>0) && !empty($data['images'][0])){ ?>
				<p>Click an image to set it as the featured image for this code.</p>
				<section id="code-manager-images-list">
								<?php
								foreach($data['images'] as $key=>$image){
												?>
												<label class="code-manager-image-container" for="code-manager-upload-image-<?php echo $key; ?>">
																<input type="radio" id="code-manager-upload-image_<?php echo $key; ?>" name="code-manager-featured-image[]" value="<?php echo $key; ?>"<?php echo $key==$data['featured-image'] && $data['featured-image']!='' ? ' checked="checked"' : ''; ?> />
																<img src="<?php echo $image; ?>" alt="<?php echo $image; ?>" width="128" height="128" />
																<a href="#remove" title="Remove Image" data-key="<?php echo $key; ?>">Remove</a>
																<input type="hidden" name="code-manager-images[]" value="<?php echo $image; ?>" />
												</label>
												<?php
								}
								?>
				</section>
<?php } else { ?>
				<p>You haven't uploaded any images for this code yet.</p>
				<section id="code-manager-images-list"></section>
<?php } ?>
<input id="code-manager-images-btn-submit" class="button code-manager-upload-image-btn" type="button" value="Add Image(s)" />
<input id="code-manager-images-btn-remove-all" class="button" type="button" value="Remove All Images" />
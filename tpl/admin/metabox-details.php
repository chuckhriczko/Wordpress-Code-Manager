<?php
//Get the template saved to this post
$custom_tpl = get_post_meta($post->ID, 'code-manager-tpl', true);
?>
<label for="code-manager-tpl">Template:</label>
<select id="code-manager-tpl" name="code-manager-tpl">
				<option value="">Default</option>
				<?php
								//Loop through list of templates that were found
								foreach($templates as $key=>$tpl){
												?><option value="<?php echo $tpl['filename']; ?>"<?php echo !empty($custom_tpl) && $tpl['filename']==$custom_tpl ? ' selected="selected"' : ''; ?>><?php echo $tpl['tpl_name']; ?></option><?php
								}
				?>
</select>
<label for="code-manager-github-link">Github Link</label>
<input type="text" id="code-manager-github-link" name="code-manager-github-link" value="<?php echo isset($data['code-manager-github-link']) ? $data['code-manager-github-link'] : ''; ?>" />
<label for="code-manager-zip">Code File</label>
<input type="text" id="code-manager-zip-url" name="code-manager-zip-url" value="<?php echo isset($data['code-manager-zip-url']) ? $data['code-manager-zip-url'] : ''; ?>" />
<input type="button" id="code-manager-zip-choose" name="code-manager-zip-choose" value="..." />
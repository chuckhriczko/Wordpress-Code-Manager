var Code_Manager_Admin = {
				anim_speed: 'medium',
    cache: {}
}; //Init the primary admin object

(function($) {
				$(document).ready(function(){
        Code_Manager_Admin.init_DOM_cache();
								Code_Manager_Admin.init_zip_uploader_form();
        Code_Manager_Admin.init_images_uploader_form();
        Code_Manager_Admin.init_radios();
				});
    
    /*******************************************************************************
				 * Initializes the DOM cache for faster access
				 ******************************************************************************/
    Code_Manager_Admin.init_DOM_cache = function(){
        Code_Manager_Admin.cache.images = $('#code-manager-images');
								Code_Manager_Admin.cache.images_list = $('#code-manager-images-list');
    }
				
				/*******************************************************************************
				 * Initializes the uploader form for the code upload section
				 ******************************************************************************/
				Code_Manager_Admin.init_zip_uploader_form = function(){
								var custom_uploader;
								$('#code-manager-zip-choose').click(function(e){												
												//If the uploader object has already been created, reopen the dialog
												if (custom_uploader) {
																custom_uploader.open();
																return;
												}
												
												//Extend the wp.media object
												custom_uploader = wp.media.frames.file_frame = wp.media({
																title: 'Choose File...',
																button: {
																				text: 'Choose File...'
																},
																multiple: false
												});
												
												//When a file is selected, grab the URL and set it as the text field's value
												custom_uploader.on('select', function() {
																//Get the uploader selection
																selection = custom_uploader.state().get('selection');
																
																//Verify the user has selected an image
																if (selection.length>0){
																				//Init our HTML variable
																				var html = '';
																				
																				//Loop through each selection
																				selection.map(function(attachment, index){
																								//Turn the attachment object into a JSON object
																								attachment = attachment.toJSON();
																								
																								//Generate an image container
																								$('#code-manager-zip-url').val(attachment.url);
																				});
																}
												});
												
												//Open the uploader dialog
												custom_uploader.open();
												
												e.preventDefault();
												return false;
								});
				}
    
    /*******************************************************************************
				 * Initializes the uploader form for the screenshots section
				 ******************************************************************************/
				Code_Manager_Admin.init_images_uploader_form = function(){
								var custom_uploader;
								$('#code-manager-images-btn-submit').click(function(e){												
												//If the uploader object has already been created, reopen the dialog
												if (custom_uploader) {
																custom_uploader.open();
																return;
												}
												
												//Extend the wp.media object
												custom_uploader = wp.media.frames.file_frame = wp.media({
																title: 'Choose Image(s)',
																button: {
																				text: 'Choose Image(s)'
																},
																multiple: true
												});
												
												//When a file is selected, grab the URL and set it as the text field's value
												custom_uploader.on('select', function() {
																//Get the uploader selection
																selection = custom_uploader.state().get('selection');
																
																//Verify the user has selected an image
																if (selection.length>0){
																				//Init our HTML variable
																				var html = '';
																				
																				//Loop through each selection
																				selection.map(function(attachment, index){
																								//Turn the attachment object into a JSON object
																								attachment = attachment.toJSON();
																								
																								//Generate an image container
																								html += '<label class="code-manager-image-container" for="code-manager-upload-image-' + index + '"><input type="radio" id="code-manager-upload-image-' + index + '" name="code-manager-featured-image[]" value="' + attachment.url + '" /><img src="' + attachment.url + '" alt="' + attachment.title + '" width="128" height="128" /><a href="#remove" title="Remove Image">Remove</a><input type="hidden" name="code-manager-images[]" value="' + attachment.url + '" /></label>';
																				});
																				
																				//Add the images to the list
																				Code_Manager_Admin.cache.images_list.append(html);
																}
												});
												
												//Open the uploader dialog
												custom_uploader.open();
												
												e.preventDefault();
												return false;
								});
								
								//Init remove all button
								Code_Manager_Admin.cache.images_list.siblings('input#code-manager-images-btn-remove-all').on('click', function(e){
												if (confirm('Are you sure you would like to remove ALL the images from this code?')) Code_Manager_Admin.cache.images_list.empty();
								});
								
								//Init the remove links for individual images
								Code_Manager_Admin.cache.images_list.on('click', '.code-manager-image-container a', function(e){
												$(this).parent('label.code-manager-image-container').empty().remove();
												
												e.preventDefault();
												return false;
								});
				}
    
    /*******************************************************************************
				 * Initializes the images section's featured radio buttons
				 ******************************************************************************/
				Code_Manager_Admin.init_radios = function(){
								//Find each image in the image container
								Code_Manager_Admin.cache.images_list.find('.code-manager-image-container img').on('click', function(){
												//Trigger the radio button click so our POST data is correct
												$(this).siblings('input[type="radio"]').trigger('click');
								});
				}
}(jQuery));
var Code_Manager = {
				animSpeed: 'medium',
				cache: {}
}; //Init the primary frontend object

(function($) {
				$(document).ready(function(){
								//Initializes the DOM cache
								Code_Manager.init_dom_cache();
								
								//Binds events to page elements
								Code_Manager.bind_events();
        
        //Adds menu to documentation based on h3s and h4s in documentation content
        Code_Manager.init_docs();
        
        //Initialize syntax highlighting
        hljs.initHighlightingOnLoad();
				});
				
				/**********************************************************************
					* Caches DOM elements for faster access
					*********************************************************************/
				Code_Manager.init_dom_cache = function(){
								//Cache elements from the code manager grid
								Code_Manager.cache.container = $('.code-manager-container');
								Code_Manager.cache.list_items = Code_Manager.cache.container.find('ul li');
								
								//Cache elements from the code detail page
								Code_Manager.cache.detail = $('#code-detail-body');
								Code_Manager.cache.scroller = $('.code-manager-scroller .code-manager-scroller-content');
								Code_Manager.cache.scroller_preview = Code_Manager.cache.scroller.find('.code-manager-scroller-preview');
								Code_Manager.cache.scroller_list = Code_Manager.cache.scroller.find('ul');
								Code_Manager.cache.thumb_list = $('#code-detail-body .code-detail-sidebar ul.code-manager-horizontal-list');
				}
				
				/**********************************************************************
					* Initializes the thumbnail scroller for the code pages on resize
					*********************************************************************/
				Code_Manager.bind_events = function(){
								//Bind the thumbnail list click event
								$('#code-detail-body .code-detail-sidebar ul li a.thumb-scroller').on('click', function(e){
												//Open the scroller with the desired image preset
												Code_Manager.scroller.open(e, $(this).data('key'), true);
												
												//Prevent default action
												e.preventDefault();
												return false;
								});
								
								//Bind the click event for the larger code picture
								$('#code-detail-body div.code-detail-text a.code-detail-trigger').on('click', function(e){
												//Find the number of slides in the scroller
												var slides = $('.code-manager-scroller').find('ul li').length - 2;
												
												//Open the scroller with the desired image preset
												Code_Manager.scroller.open(e, slides-1, true);
												
												//Prevent default action
												e.preventDefault();
												return false;
								});
																
								//Bind the sidebar handler to filter code using Ajax
								$('.code-manager-container aside ul li a').on('click', function(e){
									//Cache the code content area
									var $list = $('.code-manager-code-list');
									
									//Hide the content area
									$list.fadeOut(Code_Manager.animSpeed);
											
									//Get the term ID
									var type_id = $(this).attr('href');
									
									//Convert the term_id to be only a number
									type_id = parseInt(type_id.replace('#type-', ''));
									
									//Perform Ajax request
									$.ajax({
										url: '/wp-admin/admin-ajax.php',
										dataType: 'json',
										data: {
											action: 'get_code_by_type',
											type_id: type_id
										},
										success: function(json){
											//Init our HTML variable
											var html = '';
											
											//Determine if any code were received
											if (json.length==0){
												html = '<h3 style="font-size: 20px; margin: 5px 0;">No code matching your criteria were found.</h3><p>Please try a different category.</p>';
                                            } else {
												//Loop through each code
												$.each(json, function(index, val){
             html += '<article class="code-manager-code-item"><h3><a title="' + val.post_content + '" href="' + val.meta['permalink'] + '">' + val.post_title + '</a></h3><a title="' + val.post_content + '" href="' + val.meta['permalink'] + '" class="code-img"><img alt="' + val.post_title + '" src="' + val.meta['code-manager-images'][0] + '"></a><div class="code-description">' + val.excerpt + '</div></article>';
												});
											}
											
											//Show the portfolio content area
												$list.empty().html(html).fadeIn(Code_Manager.animSpeed);
										}
									});
									
									e.preventDefault();
									return false;
								});
				}
    
    /**********************************************************************
					* Initializes documentation menu
					*********************************************************************/
    Code_Manager.init_docs = function(){
        //Cache the DOM elements
        var $docs_header = $('#code-detail-docs'),
            $docs_content = $docs_header.next('#code-detail-docs-content'),
            menu_html = '<ol>';
        
        //Find all h3s and h4s in the content and loop through them,
        //whilst generating the menu HTML
        $docs_content.find('h3, h4').each(function(){
            //Generate menu item hash
            var hash = $(this).text().replace(/ /g, '-').replace(/:/g, '').toLowerCase();
            
            //Set the ID of this header to be the hash
            $(this).attr('id', hash);
            
            //Begin generating HTML for menu
            menu_html += '<li><a data-scroll href="#' + hash + '">' + $(this).text() + '</a></li>';
        });
        
        //Close out the HTML
        menu_html += '</ol>';

        //Insert menu right below the documentation header
        $docs_header.after(menu_html);
    }
}(jQuery));
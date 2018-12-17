<?php
// For enabling the auto-converting of WPBookList Libraries 
function wpbooklist_categories_mobile_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-categories-save-button").click(function(event){

	  		$('#wpbooklist-categories-success-div').html('');
	  		$('#wpbooklist-spinner-1').animate({'opacity':'1'})
	  		console.log($('#wpbooklist-categories-checkbox'));
	  		var checked = $('#wpbooklist-categories-checkbox').prop('checked');

		  	var data = {
				'action': 'wpbooklist_categories_mobile_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_categories_mobile_action_callback" ); ?>',
				'checked':checked
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	if(response == 1){

			    		$('#wpbooklist-categories-success-div').html('<div id="wpbooklist-addbook-success-thanks">Success! You\'ve just saved your \'WPBookList Categories\' Settings.<br/>Thanks for using WPBookList, and if you happen to be thrilled with WPBookList, then by all means, <a id="wpbooklist-addbook-success-review-link" href="https://wordpress.org/support/plugin/wpbooklist/reviews/?filter=5" >Feel Free to Leave a 5-Star Review Here!</a><img id="wpbooklist-smile-icon-1" src="<?php echo ROOT_IMG_ICONS_URL; ?>smile.png"></div>');
			    		$('#wpbooklist-spinner-1').animate({'opacity':'0'})
			    	}
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_categories_mobile_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_categories_mobile_action_callback', 'security' );
	$checked = filter_var($_POST['checked'],FILTER_SANITIZE_STRING);
	
	$table_name = $wpdb->prefix . 'wpbooklist_category_settings';
	$data = array(
    	'mobileconvert' => $checked
    );
    $format = array( '%s'); 
    $where = array( 'ID' => 1 );
    $where_format = array( '%d' );
    echo $wpdb->update( $table_name, $data, $where, $format, $where_format );

	wp_die();
}




/*
 * Below is a categories ajax function and callback, 
 * complete with console.logs and echos to verify functionality
 */

/*
// For adding a book from the admin dashboard
add_action( 'admin_footer', 'wpbooklist_categories_action_javascript' );
add_action( 'wp_ajax_wpbooklist_categories_action', 'wpbooklist_categories_action_callback' );
add_action( 'wp_ajax_nopriv_wpbooklist_categories_action', 'wpbooklist_categories_action_callback' );


function wpbooklist_categories_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#wpbooklist-admin-addbook-button").click(function(event){

		  	var data = {
				'action': 'wpbooklist_categories_action',
				'security': '<?php echo wp_create_nonce( "wpbooklist_categories_action_callback" ); ?>',
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function wpbooklist_categories_action_callback(){
	global $wpdb;
	check_ajax_referer( 'wpbooklist_categories_action_callback', 'security' );
	//$var1 = filter_var($_POST['var'],FILTER_SANITIZE_STRING);
	//$var2 = filter_var($_POST['var'],FILTER_SANITIZE_NUMBER_INT);
	echo 'hi';
	wp_die();
}*/
?>
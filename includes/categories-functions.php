<?php

/**
 * Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
 */
function wpbooklist_categories_core_plugin_required() {

  // Require core WPBookList Plugin.
  if ( ! is_plugin_active( 'wpbooklist/wpbooklist.php' ) && current_user_can( 'activate_plugins' ) ) {

    // Stop activation redirect and show error.
    wp_die( 'Whoops! This WPBookList Extension requires the Core WPBookList Plugin to be installed and activated! <br><a target="_blank" href="https://wordpress.org/plugins/wpbooklist/">Download WPBookList Here!</a><br><br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
  }
}



// Function to add table names to the global $wpdb
function wpbooklist_categories_register_table_name() {
    global $wpdb;
    $wpdb->wpbooklist_category_settings = "{$wpdb->prefix}wpbooklist_category_settings";
}

// Runs once upon plugin activation and creates tables
function wpbooklist_categories_create_tables() {
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  global $wpdb;
  global $charset_collate; 

  // Call this manually as we may have missed the init hook
  wpbooklist_categories_register_table_name();

  $sql_create_table = "CREATE TABLE {$wpdb->wpbooklist_category_settings} 
  (
        ID bigint(190) auto_increment,
        mobileconvert varchar(255),
        PRIMARY KEY  (ID),
          KEY mobileconvert (mobileconvert)
  ) $charset_collate; ";
  dbDelta( $sql_create_table);
  $table_name = $wpdb->prefix . 'wpbooklist_category_settings';
  $wpdb->insert( $table_name, array('ID' => 1));

}

// Adding the front-end ui css file for this extension
function wpbooklist_jre_categories_frontend_ui_style() {
    wp_register_style( 'wpbooklist-categories-frontend-ui', CATEGORIES_ROOT_CSS_URL.'categories-frontend-ui.css' );
    wp_enqueue_style('wpbooklist-categories-frontend-ui');
}

// Function to display a library with a shortcode
function wpbooklist_category_shortcode_function($atts){
  global $wpdb;
  extract(shortcode_atts(array(
          'table' => $wpdb->prefix."wpbooklist_jre_saved_book_log",
          'width' => '100'
  ), $atts));

  if(isset($atts['table'])){
    $table =  $atts['table'];
  }

  if(isset($atts['width'])){
    $width = $atts['width'];
  }



  $atts_array = array(
    'table' => $table,
    'width' => $width
  );

  ob_start();
  include_once( CATEGORIES_ROOT_INCLUDES_UI . 'class-frontend-categories-ui.php');
  $categories = new WPBookList_Category_UI($atts_array);
  echo $categories->html_output;
  return ob_get_clean();
}

// For expanding the drop-downs
function categories_expand_javascript() { 
  ?>
    <script type="text/javascript" >
    "use strict";
    jQuery(document).ready(function($) {
      $(document).on("click",".wpbooklist-categories-indiv-container", function(event){
        var initialheight = $(this).css('height')
        $(this).find('.wpbooklist-categories-carrot-img').addClass('wpbooklist-categories-carrot-spinner')
        if(initialheight == '50px'){ 
          var innerheight = $(this).find('.wpbooklist-categories-book-holder').css('height').replace('px', '');
          $(this).animate({'height':innerheight+'px'})
          $(this).css({'box-shadow': 'inset 5px -3px 70px 34px #e1e1e1'})
        } else {
          $(this).animate({'height':'50px'})
          $(this).find('.wpbooklist-categories-carrot-img').removeClass('wpbooklist-categories-carrot-spinner')
          $(this).css({'box-shadow': 'inset 5px 0px 25px 20px #e1e1e1'})
        }



        event.preventDefault ? event.preventDefault() : event.returnValue = false;
      });
  });
  </script>
  <?php
}

// For replacing shortcode in the content if on mobile
function wpbooklist_categories_content( $content ) {
    // If the content has the wpbooklist shortcode...
    if( has_shortcode( $content, 'wpbooklist_shortcode' ) ) {

      global $wpdb;

      $table_name = $wpdb->prefix . 'wpbooklist_category_settings';
      $db_row = $wpdb->get_row("SELECT * FROM $table_name");
      if($db_row->mobileconvert == 'true'){

        // Include and instantiate the class that will determine if the viitor is on a mobile device
        if(!class_exists('Mobile_Detect')){
          include_once CATEGORIES_ROOT_INCLUDES.'mobile-detect/mobile-detect.php';
        }
        $detect = new Mobile_Detect;

        // Any mobile device (phones or tablets).
        if ( $detect->isMobile() ) {
          $contents = array();
          $startDelimiterLength = strlen('[');
          $endDelimiterLength = strlen(']');
          $startFrom = $contentStart = $contentEnd = 0;
          while (false !== ($contentStart = strpos($content, '[', $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($content, ']', $contentStart);
            if (false === $contentEnd) {
              break;
            }
            $contents[] = substr($content, $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
          }

          $array_length = sizeof($contents);
          foreach ($contents as $key => $shortcode) {
            // Make sure we don't overshoot the array
            if($key+1 <= $array_length){
              if(strrpos($shortcode, 'wpbooklist_shortcode') !== false){
                $shortcode_args = explode('wpbooklist_shortcode', $shortcode);
                if(sizeof($shortcode_args) > 1){
                  $content = str_replace('['.$shortcode.']', '[wpbooklist_categories '.$shortcode_args[1].']', $content);
                }
              }
            }
          }
        }
      }
    }
 
    return $content;
}


// For modifying the width of the views
function categories_boilerplate_action_javascript() { 
  ?>
    <script type="text/javascript" >
    "use strict";
    console.log('hi1')
    jQuery(document).ready(function($) {
      console.log('hi2')
      if($('#wpbooklist-categories-shortcode-atts-div').length != 0){
        console.log('hi3')
        var width = $('#wpbooklist-categories-shortcode-atts-div').attr('data-width');
        console.log('hi4'+width)
        $('#wpbooklist_categories_main_display_div').css({'width':width+'%'})
      }
  });
  </script>
  <?php
}
/*
 * Below is a boilerplate function with Javascript
 *
/*

// For 
add_action( 'admin_footer', 'categories_boilerplate_javascript' );

function categories_boilerplate_action_javascript() { 
  ?>
    <script type="text/javascript" >
    "use strict";
    jQuery(document).ready(function($) {
      $(document).on("click",".categories-trigger-actions-checkbox", function(event){

        event.preventDefault ? event.preventDefault() : event.returnValue = false;
      });
  });
  </script>
  <?php
}
*/
?>
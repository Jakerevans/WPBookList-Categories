<?php
/**
 * WPBookList WPBookList_Categories_Form Submenu Class
 *
 * @author   Jake Evans
 * @category ??????
 * @package  ??????
 * @version  1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPBookList_Categories_Form', false ) ) :
/**
 * WPBookList_Categories_Form Class.
 */
class WPBookList_Categories_Form {

	public static function output_categories_form(){
		global $wpdb;
		// Getting all user-created libraries
		$table_name = $wpdb->prefix . 'wpbooklist_category_settings';
		$db_row = $wpdb->get_row("SELECT * FROM $table_name");
    if($db_row->mobileconvert == 'true'){
      $mobile = 'checked';
    } else {
      $mobile = '';
    }

		$string1 = '<div style="text-align:center;"><p>Check the box below to automatically convert your existing <span class="wpbooklist-color-orange-italic">WPBookList</span> Libraries to the more mobile-friendly <span class="wpbooklist-color-orange-italic">WPBookList</span> Categories view, when the visitor to your website is on a mobile device.</p><br/><br/><div style="text-align:center;" id="wpbooklist-categories-checkbox-div"><input id="wpbooklist-categories-checkbox" type="checkbox" '.$mobile.' /><label>Enable Auto-Converting to \'Categories\' View</label><br/><br/><div class="wpbooklist-spinner" id="wpbooklist-spinner-1"></div><div style="color:#F05A1A;margin-bottom:20px;line-height:1;" id="wpbooklist-categories-success-div"></div><button id="wpbooklist-categories-save-button">Save Settings</button><br/><br/></div></div>';

    return $string1;
	}
}

endif;
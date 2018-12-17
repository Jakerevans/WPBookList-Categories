<?php
/**
 * WPBookList Categories UI Class
 *
 * @author   Jake Evans
 * @category Categories UI
 * @package  Includes/UI
 * @version  1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPBookList_Category_UI', false ) ) :
/**
 * WPBookList_Category_UI Class.
 */
class WPBookList_Category_UI {

	public $table = '';
	public $width = '';


	public $books_array = array();
	public $html_output = '';
	public $display_options_actual = array();
	public $display_options_table = '';
	public $final_category_array = array();


	public function __construct($atts_array) {

		$this->table = $atts_array['table'];
		$this->width = $atts_array['width'];
		$this->build_book_table();
		$this->get_books();
		$this->get_display_options();
		$this->output_html();
	}

	private function build_book_table(){
		global $wpdb;
		if($this->table != $wpdb->prefix.'wpbooklist_jre_saved_book_log'){
			$this->table = $wpdb->prefix.'wpbooklist_jre_'.$this->table;
		}
	}

	private function get_books(){
		global $wpdb;
		$this->books_array = $wpdb->get_results("SELECT * FROM $this->table");
	}

	private function get_display_options(){
		global $wpdb;
		// Building display options table
		if($this->table == $wpdb->prefix.'wpbooklist_jre_saved_book_log'){
			$this->display_options_table = $wpdb->prefix.'wpbooklist_jre_user_options';
		} else {
			$temp = explode('_', $this->table);
			$temp = array_pop($temp);
			$this->display_options_table = $wpdb->prefix.'wpbooklist_jre_settings_'.strtolower($temp);
		}

		// Getting all display options
		$this->display_options_actual = $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->display_options_table WHERE ID = %d", 1));
	}

	private function output_html(){

		$string  = '<div data-table="'.$this->table.'" data-width="'.$this->width.'" id="wpbooklist-categories-shortcode-atts-div"></div><div id="wpbooklist_categories_main_display_div">';

		if($this->books_array == null || sizeof($this->books_array) == 0){
			$this->html_output = '<div>Uh-oh! You haven\'t added any books to this Library! Add some books and then check back here.';
			return;
		}

		// Creating a unique list of categories
		$temp_category_array = array();
		foreach($this->books_array as $key=>$book){
			if($book->category == null){
				$book->category = 'Uncategorized';
			}
			array_push($temp_category_array, $book->category);
		}

		
		$temp_category_array = array_unique($temp_category_array);
		foreach($temp_category_array as $cat){
			if($cat != ''){
				array_push($this->final_category_array, $cat);
			}
		}
		sort($this->final_category_array);


		foreach ($this->final_category_array as $key => $value) {
			$forid =  strtolower(preg_replace('/\s+/', '', $value));
			$string = $string.'<div class="wpbooklist-categories-indiv-container" id="wpbooklist-categories-'.$forid.'"><div class="wpbooklist-categories-indiv-inner-container"><div class="wpbooklist-categories-title-holder"><img class="wpbooklist-categories-book-img" src="'.CATEGORIES_ROOT_IMG_URL.'books.svg"/>  '.$value.'<img class="wpbooklist-categories-carrot-img" src="'.CATEGORIES_ROOT_IMG_URL.'repeat.svg"/></div></div><div class="wpbooklist-categories-book-holder">';

			foreach ($this->books_array as $key => $book) {
				if($book->category == $value){
					$string = $string.'<div class="wpbooklist_category_entry_div">
		                <p style="display:none;" id="wpbooklist-hidden-isbn1">'.$book->isbn.'</p>
		                <div class="wpbooklist_category_inner_main_display_div">
		                    <img class="wpbooklist_cover_image_class wpbooklist-show-book-colorbox" data-bookid="'.$book->ID.'" data-booktable="'.$this->table.'" id="wpbooklist_cover_image" src="'.$book->image.'" style="opacity: 1;">
		                    <span class="hidden_id_title">'.$book->ID.'</span>
		                    <p class="wpbooklist_saved_title_link wpbooklist-show-book-colorbox" data-bookid="'.$book->ID.'" data-booktable="'.$this->table.'" id="wpbooklist_saved_title_link">'.$book->title.'<span class="hidden_id_title">'.$book->ID.'</span>
		                    </p><div class="wpbooklist-library-frontend-purchase-div"></div></div></div>';
				}
			}

			$string = $string.'</div></div>';


		}

		$this->html_output = $string.'</div>';
	}

}


endif;
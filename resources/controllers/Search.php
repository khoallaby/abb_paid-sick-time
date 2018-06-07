<?php
namespace ABetterBalance\Plugin;

class Search extends PaidSickTime {


    public function init() {
        add_shortcode( 'pst-search', function() {
            static::getView( 'search' );
        } );
    }

    public static function addSearchPage() {

	    $args = [
		    'post_title'   => 'Search',
		    'post_content' => '[pst-search]',
		    'post_status'  => 'publish',
		    'post_type'    => self::$cptName,
	    ];


	    return wp_insert_post ( $args );
    }


}

add_action( 'init', [ \ABetterBalance\Plugin\Search::get_instance(), 'init' ] );

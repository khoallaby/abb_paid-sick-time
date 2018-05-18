<?php
namespace ABetterBalance\Plugin;

class Search extends PaidSickTime {


    public function init() {
        add_shortcode( 'pst-search', function() {
            static::getView( 'search' );
        } );
    }


}

add_action( 'init', [ \ABetterBalance\Plugin\Search::get_instance(), 'init' ] );

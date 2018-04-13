<?php
namespace ABetterBalance\Plugin;

class Search extends PaidSickTime {


    public function init() {
        add_shortcode( 'pstl-search', [ $this, 'shortcodeSearch' ] );
    }


    public function shortcodeSearch() {
        self::getView( 'search' );
    }

}

add_action( 'init', [ \ABetterBalance\Plugin\Search::get_instance(), 'init' ] );

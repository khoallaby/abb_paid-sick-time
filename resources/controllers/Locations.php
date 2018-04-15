<?php
namespace ABetterBalance\Plugin;


class Locations extends PaidSickTime {

    public static $taxName = 'location';


    public function init() {
        $this->registerTaxonomy( self::$taxName, self::$taxName . 's', static::$cptName, [
            'public' => true,
            'public_queryable' => true,
            'show_in_menu' => true,
            'hierarchical' => false,
        ] );
    }

}

add_action( 'init', array( \ABetterBalance\Plugin\Locations::get_instance(), 'init' ) );

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


    /**
     * Return the location names as (id/slug) => Location name
     */
    public static function getLocations() {

        return get_terms(Locations::$taxName);
        /*
        foreach( get_terms(Locations::$taxName) as $location ) {
            $locationSlugs[ $location->term_id ] = $location->slug;
            $postsOrdered[ $location->slug ] = []; // spawn empty array so no errors later for a non existent array
        }
        */
    }

    public static function getLocationSlugTitles() {
        $locations = [
            'state'    => 'States',
            #'city' => 'Cities',
            #'county' => 'Counties'
            'cali'     => 'California Cities',
            'non-cali' => 'Other Counties & Cities (Outside California)'
        ];
        return $locations;
    }



}

add_action( 'init', array( \ABetterBalance\Plugin\Locations::get_instance(), 'init' ) );

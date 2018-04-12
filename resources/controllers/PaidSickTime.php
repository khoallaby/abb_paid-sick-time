<?php
namespace ABetterBalance\Plugin;

/**
 * https://stackoverflow.com/questions/44653777/creating-a-repeater-meta-box-without-a-plugin-in-wordpress
 * Class Questions
 * @package ABetterBalance\Plugin
 */
class PaidSickTime extends CustomPostTypes {

    public static $cptName = 'Paid sick time law';

    public function init() {
        add_action( 'init', [ $this, 'registerAll' ]);
    }


    public function registerAll() {
        $this->registerCpt( self::$cptName, self::$cptName . 's', [
            'exclude_from_search' => true,
            'supports'            => [ 'title', 'editor', 'page-attributes', 'custom-fields', /*'thumbnail'*/ ],
            'taxonomies'          => [ 'category' ],
        ] );
    }



}

add_action( 'init', array( \ABetterBalance\Plugin\PaidSickTime::get_instance(), 'init' ) );

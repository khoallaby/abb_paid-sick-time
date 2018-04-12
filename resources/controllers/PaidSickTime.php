<?php
namespace ABetterBalance\Plugin;

/**
 * https://stackoverflow.com/questions/44653777/creating-a-repeater-meta-box-without-a-plugin-in-wordpress
 * Class Questions
 * @package ABetterBalance\Plugin
 */
class PaidSickTime extends CustomPostTypes {

    public static $cptFullName = 'Paid sick time law';
    public static $cptName;

    public function init() {
        self::$cptName = parent::uncleanName( static::$cptFullName );
        add_action( 'init', [ $this, 'registerAll' ]);
    }


    public function registerAll() {
        $this->registerCpt( static::$cptFullName, static::$cptFullName . 's', [
            'exclude_from_search' => true,
            'supports'            => [ 'title', 'editor', 'page-attributes', 'custom-fields', /*'thumbnail'*/ ],
            'taxonomies'          => [ 'category' ],
        ] );
    }



}

add_action( 'init', array( \ABetterBalance\Plugin\PaidSickTime::get_instance(), 'init' ) );

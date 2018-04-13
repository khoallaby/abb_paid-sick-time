<?php
namespace ABetterBalance\Plugin;

/**
 * https://stackoverflow.com/questions/44653777/creating-a-repeater-meta-box-without-a-plugin-in-wordpress
 * Class Questions
 * @package ABetterBalance\Plugin
 */
class PaidSickTime extends CustomPostTypes {

    public static $cptName;
    public static $cptFullName          = 'Paid sick time law';
    public static $nonce                = 'pst-questions-nonce';
    public static $capability           = 'edit_theme_options';
    public static $answersMetaName      = '_answers';
    public static $questionsMetaName    = '_questions';
    public static $questionsOptionName  = 'pst_questions';

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

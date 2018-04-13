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
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ], 100 );
    }


    public function registerAll() {
        $this->registerCpt( static::$cptFullName, static::$cptFullName . 's', [
            'exclude_from_search' => true,
            'supports'            => [ 'title', 'editor', 'page-attributes', 'custom-fields', /*'thumbnail'*/ ],
            'taxonomies'          => [ 'category' ],
        ] );
    }




    // Enqueues styles/scripts on frontend
    public function enqueueScripts() {
        if( is_post_type_archive(PaidSickTime::$cptName) || is_singular(PaidSickTime::$cptName) )
            wp_enqueue_style( 'abb-pst-style', abb_pst_plugin_url . 'assets/css/style.css' );
    }



    /**
     * Prints out admin notices for notifying the user if their $update is successful or not
     * @param $update   true/false
     */
    public static function printAdminNotices( $update ) {
        add_action( 'admin_notices', function() use($update) {
            if( $update ) {
                $message = 'Saved successfully!';
                $messageClass = 'updated fade';
            } else {
                $message = 'Error while updating..';
                $messageClass = 'error';
            }

            echo '<div class="' . $messageClass . '"><p><strong>' . __( $message ) . '</strong></p></div>';
        } );
    }


    /**
     * Checks to see if the user can update the post based on verifying the nonce, and if their capabilities are enough
     * @return bool
     */
    public static function canUpdateData() {
        if( current_user_can( static::$capability) ) {
            if( !isset( $_POST[ static::$nonce ] ) || !wp_verify_nonce( $_POST[ static::$nonce ], static::$nonce ) )
                return false;
            else
                return true;
        }
        return false;
    }


    /**
     * Sanitizes the data (from $_POST), removes empty array values
     * @param $data
     *
     * @return array
     */
    public static function sanitizeData( $data ) {
        # sanitize input
        $data = stripslashes_deep( $data );
        $data = array_map( 'sanitize_textarea_field', $data );

        # removes empty elements
        $data = array_filter( $data, function($value) { return $value !== ''; } );

        return $data;
    }



}

add_action( 'init', array( \ABetterBalance\Plugin\PaidSickTime::get_instance(), 'init' ) );

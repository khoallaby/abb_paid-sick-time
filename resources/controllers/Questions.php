<?php
namespace ABetterBalance\Plugin;

class Questions extends PaidSickTime {


    public function init() {
        add_action( 'admin_menu', [ $this, 'addSubmenu' ] );
    }


    /**
     * Adds a submenu link 'Questions' under the CPT menu
     */
    public function addSubmenu() {
        $menuSlug = 'questions';
        $hook = add_submenu_page(
            'edit.php?post_type=' . PaidSickTime::$cptName,
            __( PaidSickTime::$cptFullName . ' Questions' ),
            __( 'Questions' ),
            static::$capability,
            $menuSlug,
            [ $this, 'submenuPage' ]
        );

        # $hook = {cpt}_page_{menuslug}
        # i.e. paid-sick-time-law_page_questions
        add_action( 'load-' . $hook, [ $this, 'SaveQuestionsPage' ] );
    }


    /**
     * Pulls the view for the questions submenu page
     */
    public function submenuPage() {
        static::getMenuView( 'questions/settings' );
    }
    
    
    /**
     * Saves the questions on the questions submenu page
     */
    public static function saveQuestionsPage() {
        if( isset($_POST['questions']) && isset($_POST[ static::$nonce ]) )
            self::saveQuestions( $_POST['questions'] );
    }


    /**
     * Generic function for saving questions
     * @param array $questions
     */
    public static function saveQuestions( $questions = [] ) {
        if( !$questions || empty($questions) ) {
            if( isset($_POST['questions']) )
                $questions = $_POST['questions'];
            else
                return;
        }

        if( empty($questions) ||
            !isset( $_POST[ static::$nonce ] ) ||
            !wp_verify_nonce( $_POST[ static::$nonce ], static::$nonce ) )
            return;


        if( !current_user_can( static::$capability) )
            return;

        # sanitize input
        $questions = stripslashes_deep( $questions );
        $questions = array_map( 'sanitize_textarea_field', $questions );

        # removes empty elements
        $questions = array_filter( $questions, function($value) { return $value !== ''; } );

        $update = update_option( static::$questionsOptionName, $questions );

        ## output an admin notice
        if( $update ) {
            add_action( 'admin_notices', function() use($update) {
                if( $update ) {
                    $message = 'Saved successfully!';
                    $messageClass = 'updated fade';
                } else {
                    $message = 'Something went wrong..';
                    $messageClass = 'error';
                }

                echo '<div class="' . $messageClass . '"><p><strong>' . __( $message ) . '</strong></p></div>';
            } );
        }

    }

}

add_action( 'init', array( \ABetterBalance\Plugin\Questions::get_instance(), 'init' ));

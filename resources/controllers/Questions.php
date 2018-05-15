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
            'edit.php?post_type=' . static::$cptName,
            __( static::$cptFullName . ' Questions' ),
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


        if( !static::canUpdateData() )
            return;

        $questions = static::sanitizeData( $questions );
        $update = update_option( static::$questionsOptionName, $questions );

        static::printAdminNotices( $update );

    }

    public static function getQuestions( $questionIDs = [] ) {
        $questions = get_option( static::$questionsOptionName );

        if( $questionIDs ) {
            // return only the questions from $questionIDs
            return array_filter(
                $questions,
                function($key) use ($questionIDs) {
                    return in_array( $key, $questionIDs );
                },
                ARRAY_FILTER_USE_KEY
            );
        } else {
            return $questions;
        }
    }
}

add_action( 'init', array( \ABetterBalance\Plugin\Questions::get_instance(), 'init' ));

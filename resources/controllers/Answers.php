<?php
namespace ABetterBalance\Plugin;

class Answers extends PaidSickTime {

    public function init() {
        add_action( 'admin_init', [ $this, 'addMetaBoxes' ], 2 );
        add_action( 'save_post', [ $this, 'saveAnswersPage' ] );
    }


    /**
     * Adds the meta boxes to edit PSTL
     */
    public function addMetaBoxes() {
        add_meta_box(
            'answers-metaboxes',
            'Questions & Answers',
            [
                $this,
                'displayMetaBoxes'
            ],
            static::$cptName,
            'normal',
            'default'
        );
    }


    /**
     * Gets the view for displaying the questions/answers metaboxes when editing a PSTL
     */
    public function displayMetaBoxes() {
        static::getView('questions/answers-metaboxes');
    }


    /**
     * Saves the answers on the edit PSTL page
     */
    public static function saveAnswersPage() {
        if( isset($_POST['answers']) && isset($_POST[ static::$nonce ]) )
            self::saveAnswers( $_POST['answers'] );
    }



    /**
     * Generic function for saving answers
     * @param array $answers
     */
    public static function saveAnswers( $answers = [], $postID = null ) {
        global $post;
        if( !$postID )
            $postID = $post->ID;

        if( !$answers || empty($answers) )
            return;

        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if( !static::canUpdateData() )
            return;

        $answers = static::sanitizeData( $answers );
        $update = update_post_meta( $postID, static::$answersMetaName, $answers );

        static::printAdminNotices( $update );

    }


    /**
     * Get the answers from $posts
     * @param array $posts - An array of location IDs
     *
     * @return array|mixed
     */
    public static function getAnswers( $posts = [] ) {
        $answers = [];

        if( empty($posts) )
            $posts = static::getPSTsByLocation( [], true );

        if( is_array($posts) ) {
            foreach( $posts as $p )
                $answers[$p] = get_post_meta( $p, PaidSickTime::$answersMetaName, true );
        } elseif( !is_array($posts) ) {
            $answers = get_post_meta( $posts, PaidSickTime::$answersMetaName, true );
        }


        return $answers;
    }


}

add_action( 'init', array( \ABetterBalance\Plugin\Answers::get_instance(), 'init' ));

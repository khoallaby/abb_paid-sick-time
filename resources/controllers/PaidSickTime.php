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
        add_action( 'template_redirect', [ $this, 'renderPdf' ] );
        add_filter( 'archive_template', [ $this, 'getArchiveTemplate' ] );
        //add_filter( 'single_template', [ $this, 'getSingleTemplate' ] );
    }


    public function registerAll() {
        $this->registerCpt( static::$cptFullName, static::$cptFullName . 's', [
            'exclude_from_search' => true,
            'supports'            => [ 'title', 'editor', 'page-attributes', 'custom-fields', /*'thumbnail'*/ ],
            'taxonomies'          => [ Locations::$taxName ],
        ] );
    }

    /**
     * Checks if we're exporting pdf on any of the CPT pages
     */
    public function renderPdf() {
        global $post;

        if( isset($_REQUEST['export']) ) {
            $questions = $answers = $locations = [];

            # if search
            if( is_singular( PaidSickTime::$cptName ) && is_single('search')) {
                $questionIDs = isset($_REQUEST['questions']) ? $_REQUEST['questions'] : [];
                $locations = isset($_REQUEST['locations']) ? $_REQUEST['locations'] : [];

                $questions = Questions::getQuestions( $questionIDs );
                $answers = Answers::getAnswers( $locations );

            # if single
            } elseif( is_singular( PaidSickTime::$cptName ) ) {
                $questions = get_option( PaidSickTime::$questionsOptionName );
                $answers = Answers::getAnswers( $post->ID );
                $locations = [ $post->ID ];

            # if archive
            } elseif( is_post_type_archive( PaidSickTime::$cptName ) ) {
                $questions = get_option( PaidSickTime::$questionsOptionName );
                $locations = self::getPSTsByLocation( [], true );
                $answers = Answers::getAnswers( $locations );
            }


            Pdf::renderPdf( [
                'questions' => $questions,
                'answers' => $answers,
                'locations' => $locations
            ] );

        }
    }


    // Actions for pulling overriding plugin template files for archive/single PST pages
    function getArchiveTemplate( $archiveTemplate ) {
        $templateName = 'archive-paid-sick-time-law';
        if ( is_post_type_archive ( self::$cptName ) && !strpos($archiveTemplate, $templateName) )
            $archiveTemplate = static::getViewTemplate( 'theme/' . $templateName );
        return $archiveTemplate;
    }


    function getSingleTemplate( $singleTemplate ) {
        vard($singleTemplate);
        $templateName = 'single-paid-sick-time-law';
        if ( is_singular( self::$cptName ) && !strpos($singleTemplate, $templateName) )
            $singleTemplate = static::getViewTemplate( 'theme/' . $templateName );
        return $singleTemplate;
    }



    // Enqueues styles/scripts on frontend
    public function enqueueScripts() {
        if( is_post_type_archive(self::$cptName) || is_singular(self::$cptName) ) {
            wp_enqueue_style( 'abb-pst-style' );
            wp_enqueue_script( 'abb-pst-js' );
        }

    }




    /**************************************************************************************************************
     * Generic functions for retrieving/modifying the PST posts
     *************************************************************************************************************/


    /**
     * Gets all the PST posts
     * @param array $args   Args for wp_query
     *
     * @return \WP_Query
     */
    public static function getPSTs( $args = [] ) {
        // exclude the search page
        #$search = get_page_by_title( 'search', OBJECT, self::$cptName );

        $defaults = [
            #'order'   => 'DESC',
            #'orderby' => 'display_name',
            #'post__not_in' => [ $search->ID ]
        ];
        $args = wp_parse_args( $args, $defaults );


        $posts = self::getQuery( self::$cptName, $args );

        return $posts->posts;
    }


    /**
     * Gets all the PST posts and parses all the posts into their separate locations, i.e. city/county/state
     * @param array $args       - Args for wp_query
     * @param bool $returnAsIDs - Return the PSTs as IDs
     *
     * @return array
     */
    public static function getPSTsByLocation( $args = [], $returnAsIDs = false  ) {
        $posts         = self::getPSTS( $args );
        $postsIDs      = [];
        $postsOrdered  = []; // an array divided by the locations.
        $locationSlugs = [];
        $californiaID  = 0;

        // get postID of california
        foreach( $posts as $post ) {
            if( strtolower($post->post_title) == 'california' )
                $californiaID = $post->ID;
        }

        // get all the locations (state/county/city)
        foreach( get_terms(Locations::$taxName) as $location ) {
            $locationSlugs[ $location->term_id ] = $location->slug;
            $postsOrdered[ $location->slug ] = []; // spawn empty array so no errors later for a non existent array
        }

        // loop through and generate the multi dimensional array ($postsOrdered), divided by locations
        foreach( $posts as $post ) :
            $locations = get_the_terms( $post->ID, 'location' );
            if( !empty($locations) ) {
                foreach( $locations as $location ) {
                    $postsOrdered[$location->slug][] = $post;
                    $postsIDs[] = $post->ID;

                    // logic for getting cali city/counties
                    if( $post->post_parent == $californiaID )
                        $postsOrdered['cali'][] = $post;
                    // else, is it a city or county (not state), and its not in cali?
                    elseif( in_array($location->slug, ['city', 'county']) )
                        $postsOrdered['non-cali'][] = $post;
                }
            }

        endforeach;

        if( $returnAsIDs )
            return $postsIDs;
        else
            return $postsOrdered;

    }




    public static function getPSTsByState( $state ) {

    }


    public static function addPST( $title, $answers = [], $state = false, $city = '', $county = '' ) {

		$args = [
			'post_title'   => $title,
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => self::$cptName,
			//'meta_input'  => $meta
		];

		# update post if exists, else create a new one
		if( $exists = get_page_by_title( $title, OBJECT, self::$cptName ) ) {
			$postId = $args['ID'] = $exists->ID;
			// todo: update post
			#wp_update_post( $args );

		} else {
			// add state ID as parent
			if( is_string($state) ) {
				$statePost = get_page_by_title( $state, OBJECT, self::$cptName );
				// state level post doesn't exist, so just create a blank one
				if( !$statePost ) {
					$statePostId = wp_insert_post( [
						'post_title'  => $state,
						'post_content'  => '',
						'post_status' => 'publish',
						'post_type'   => self::$cptName,
					] );

					#wp_set_object_terms( $statePostId, 'State', Locations::$taxName );

					$args['post_parent'] = $statePostId;
				} else {
					$args['post_parent'] = $statePost->ID;
				}
			}

			$postId = wp_insert_post( $args );
		}


		// save answers
	    if( !empty($answers) ) {
			Answers::saveAnswers( $answers, $postId );
	    }
		
		// set state/city/county tags
	    if( $state === true )
		    wp_set_object_terms( $postId, 'State', Locations::$taxName );
	    if( isset( $county ) )
		    wp_set_object_terms( $postId, 'County', Locations::$taxName );
	    if( isset( $city ) )
		    wp_set_object_terms( $postId, 'City', Locations::$taxName );


    }






    /**************************************************************************************************************
     * Functions for pulling in views from theme templates
     *************************************************************************************************************/
    public static function getArchiveView() {
        static::getView('theme/archive-' . self::$cptName );
    }

    public static function getSingleView() {
        static::getView('theme/single-' . self::$cptName );
    }








    /**************************************************************************************************************
     * Generic functions for saving/sanitizing custom data
     *************************************************************************************************************/



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

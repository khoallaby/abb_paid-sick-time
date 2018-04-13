<?php

namespace ABetterBalance\Plugin;

use WP_Query, WP_User_Query;


class Base {
    private static $instance = array();
    public static $capability, $dirAssets;

	protected function __construct() {
	}


	public static function get_instance() {
		$c = get_called_class();
		if ( !isset( self::$instance[$c] ) ) {
			self::$instance[$c] = new $c();
			self::$instance[$c]->init();
		}
		return self::$instance[$c];
	}

	public function init() {
	    self::$dirAssets = dirname(__FILE__) . '/../../assets/';

		//require_once dirname(__FILE__) . '/../../vendor/autoload.php';
        #add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );
        #add_action( '_admin_menu', [ $this, 'admin_init' ], 2 );

        if( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
        } else {
            #if( is_post_type_archive(PaidSickTime::$cptName) )
            #add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ], 100 );
            #add_action( 'gform_enqueue_scripts', array( $this, 'remove_gravityforms_css' ) );
        }

    }



	public static function get( $var ) {
		return static::$$var;
	}



    /******************************************************************************************
     * Actions/filters
     ******************************************************************************************/


    // Enqueues styles/scripts on admin
    public function adminEnqueueScripts() {
        wp_enqueue_style( 'abb-pst-style-admin', abb_pst_plugin_url . 'assets/css/style-admin.css' );
    }

    // Enqueues styles/scripts on frontend
    public function enqueueScripts() {
        /*
        // wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'child-style',
            get_stylesheet_directory_uri() . '/assets/css/style.css',
            array( 'parent' ),
            wp_get_theme()->get( 'Version' )
        );
        */
    }


    // do stuff on admin
    public function admin_init() {
        #require_once( dirname(__FILE__) . '/AdminUi.php');
    }



    // disable gravity forms css
    function remove_gravityforms_css() {
        wp_deregister_style( 'gforms_formsmain_css' );
        wp_deregister_style( 'gforms_reset_css' );
        wp_deregister_style( 'gforms_ready_class_css' );
        wp_deregister_style( 'gforms_browsers_css' );
    }


    /**
     * Ability to use post_title__in, in WP_Query. Search for post titles from an array
     * @param $where
     * @param $wp_query
     *
     * @return string
     */
    public function posts_where( $where, &$wp_query ) {
        global $wpdb;
        if ( $post_title__in = $wp_query->get( 'post_title__in' ) ) {
            $post_title__in = array_map( 'sanitize_title_for_query', $post_title__in );
            $post_title__in = "'" . implode( "','", $post_title__in ) . "'";
            $where .= " AND {$wpdb->posts}.post_name IN ($post_title__in)";
        }
        return $where;
    }


    /**
     * Returns an array to use for $wp_query[meta_query]
     * @param $values
     *
     * @return array
     */
    public static function getMetaQuery( $values ) {
        return self::getMetaQueryBy( 'id', $values );
    }


    /**
     * Returns an array to use for $wp_query[meta_query]
     * @param $values
     *
     * @return array
     */
    public static function getMetaQueryBy( $field = 'id', $values = '' ) {

        if( is_array($values) ) {
            $value = $values;
            $compare = 'IN';
        } else {
            $value = $values;
            $compare = '=';
        }

        $metaQuery = [
            [
                'key'     => $field,
                'terms'   => $value,
                'compare' => $compare
            ]
        ];

        return $metaQuery;
    }


    /**
     * Returns an array to use for $wp_query[tax_query]
     * @param $values
     *
     * @return array
     */
    public static function getTaxQuery( $values ) {
        return self::getTaxQueryBy( 'id', $values );
    }


    /**
     * Returns an array to use for $wp_query[tax_query]
     * @param $values
     *
     * @return array
     */
    public static function getTaxQueryBy( $field = 'id', $values = [] ) {

        if( $field == 'id' )
            $terms = array_map( 'intval', $values );
        # todo: untested
        elseif( $field == 'slug' )
            $terms = array_map( 'sanitize_title', $values );
        else
            $terms = $values;

        $taxQuery = [
            [
                'taxonomy' => 'tag-category',
                'field'    => $field,
                'terms'    => $terms,
                'operator' => 'AND'
            ]
        ];

        return $taxQuery;
    }


    #add_action( 'wp', array( $this, 'force_404' ) );
    public function force_404() {
        #global $wp_query; //$posts (if required)
        #if(is_page()){ // your condition
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
        #}
    }



    /******************************************************************************************
     * Querying Functions
     ******************************************************************************************/

    public static function getQuery( $post_type = 'post', $args = array() ) {
        if( $post_type == 'user' ) {

            $defaults = array (
                'order'          => 'DESC',
                'orderby'        => 'display_name',
                #'role'           => '',
                #'search'         => '*'.esc_attr( $search_term ).'*',
                #'count_total'    => true
            );

            $args = wp_parse_args( $args, $defaults );
            $query = new WP_User_Query( $args );

        } else {

            $defaults = array(
                'post_type'      => array( $post_type ),
                'post_status'    => array( 'publish' ),
                'posts_per_page' => -1,
                'order'          => 'DESC',
                'orderby'        => 'post_date'
            );

            $args  = wp_parse_args( $args, $defaults );
            $query = new WP_Query( $args );
        }

        return $query;
    }


    public static function getOne( $post_type = 'post', $args = array() ) {
        $args['posts_per_page'] = 1;

        $query = self::getQuery( $post_type, $args );
        if( $query->have_posts() ) {
            if( $post_type == 'user' )
                $posts = $query->get_results();
            else
                $posts = $query->get_posts();
            return $posts[0];

        } else {
            return false;
        }
    }


    public static function getPosts( $post_type = 'post', $args = array() ) {
        $query = self::getQuery( $post_type, $args );
        if( $post_type == 'user' )
            return $query->get_results();
        else
            return $query->get_posts();
    }






    public static function getView( $file, $return = false ) {
        $dirPlugin = dirname(__FILE__) . '/../views/';
        $dirtheme = 'views/';
        $fileName = $file . '.php';

        if( $theme_file = locate_template([ $dirtheme . $fileName ]) )
            $template = $theme_file;
        else
            $template = $dirPlugin . $fileName;


        if( $return )
            ob_start();

        include $template;

        if( $return )
            return ob_get_clean();
        else
            return null;

    }


    /**
     * Generic getView() function that runs common functions like checking for capability access
     * @param string $view  File name of the view w/o .php
     */
    public function getMenuView( $view ) {
        if ( !current_user_can( static::$capability ) )
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        static::getView( $view );
    }




    /******************************************************************************************
     * Random helper functions
     ******************************************************************************************/



    /**
     * Searches inside an array of $objects for $object->key = $value
     * @param array $objects    An array of objects
     * @param string $key       The key to search for in each object
     * @param string $value     The value of the $key to search for
     * @param integer $limit    Limit to return
     *
     * @return array
     */
    public static function searchObjectsFor( $objects, $key, $value, $limit = null ) {
        $matches = array_filter(
            $objects,
            function ( $e ) use($key, $value) {
                return $e->{$key} == $value;
            }
        );

        if( is_int( $limit ) )
            $matches = array_slice( $matches, 0, $limit );

        if( count( $matches ) == 1 && $limit == 1 )
            return $matches[0];
        else
            return $matches;
    }





    /**
     * Figure out what page you're on
     * @return string
     */
    function getPageType() {
        global $wp_query;
        $page = 'notfound';

        if ( $wp_query->is_page ) {
            $page = is_front_page() ? 'front' : 'page';
        } elseif ( $wp_query->is_home ) {
            $page = 'home';
        } elseif ( $wp_query->is_single ) {
            $page = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
        } elseif ( $wp_query->is_category ) {
            $page = 'category';
        } elseif ( $wp_query->is_tag ) {
            $page = 'tag';
        } elseif ( $wp_query->is_tax ) {
            $page = 'tax';
        } elseif ( $wp_query->is_archive ) {

            if ( $wp_query->is_day )
                $page = 'day';
            elseif ( $wp_query->is_month )
                $page = 'month';
            elseif ( $wp_query->is_year )
                $page = 'year';
            elseif ( $wp_query->is_author )
                $page = 'author';
            else
                $page = 'archive';

        } elseif ( $wp_query->is_search ) {
            $page = 'search';
        } elseif ( $wp_query->is_404 ) {
            $page = 'notfound';
        }

        return $page;
    }
}

add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Base::get_instance(), 'init' ));
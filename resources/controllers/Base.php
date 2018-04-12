<?php

namespace ABetterBalance\Plugin;

use WP_Query;


class Base {
    private static $instance = array();

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
		//require_once dirname(__FILE__) . '/../../vendor/autoload.php';
        #add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );

	}



	public static function get( $var ) {
		return static::$$var;
	}



    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/

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





    /******************************************************************************************
     * Querying Functions
     ******************************************************************************************/

	public static function getQuery( $post_type = 'post', $args = array() ) {
		$defaults = array (
			'post_type'      => array( $post_type ),
			'post_status'    => array( 'publish' ),
			'posts_per_page' => - 1,
			'order'          => 'DESC',
			'orderby'        => 'post_date'
		);

		$args = wp_parse_args( $args, $defaults );
		$query = new WP_Query( $args );

		return $query;
	}


	public static function getPosts( $post_type = 'post', $args = array() ) {
		$query = self::getQuery( $post_type, $args );
		return $query->get_posts();
	}




}


add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Base::get_instance(), 'init' ));
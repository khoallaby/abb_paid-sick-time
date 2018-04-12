<?php
namespace ABetterBalance\Plugin;


class CustomPostTypes extends Base {
    /**
     * todo: pull templates from plugin folder first
     * https://wordpress.stackexchange.com/questions/17385/custom-post-type-templates-from-plugin-folder
     */
	public function init() {
        #add_action( 'init', [ \ABetterBalance\Plugin\CustomPostTypes::get_instance(), 'registerAll' ]);
	}


	public function registerAll() {
        //$this->register_tax( 'career-category', 'career-categories', 'careers' );
    }


	public function registerTaxonomy( $taxName, $taxNamePlural, $postType, $args = [] ) {
		register_taxonomy(
			$taxName,
			$postType,
			[
				'label' => __( $this::cleanName($taxNamePlural) ),
				#'public' => false,
				'rewrite' => false,
				'hierarchical' => true,
            ]
		);
	}


	public function registerCpt( $cptName, $cptNamePlural, $args = [] ) {

		$labels = [
			'name'                => _x( ucwords($cptNamePlural), 'Post Type General Name' ),
			'singular_name'       => _x( ucwords($cptName) . '', 'Post Type Singular Name' ),
			'menu_name'           => __( ucwords($cptNamePlural) ),
			'parent_item_colon'   => __( 'Parent ' . ucwords($cptName) ),
			'all_items'           => __( 'All ' . ucwords($cptNamePlural) ),
			'view_item'           => __( 'View ' . ucwords($cptName) ),
			'add_new_item'        => __( 'Add New ' . ucwords($cptName) ),
			'add_new'             => __( 'Add New' ),
			'edit_item'           => __( 'Edit ' . ucwords($cptName) ),
			'update_item'         => __( 'Update ' . ucwords($cptName) ),
			'search_items'        => __( 'Search ' . ucwords($cptName) ),
			'not_found'           => __( 'Not found' ),
			'not_found_in_trash'  => __( 'Not found in Trash' ),
        ];
		$defaults = [
			'label'               => __( $cptNamePlural ),
			'description'         => __( ucwords($cptName) . ' Description' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'editor', 'thumbnail', /*'excerpt', 'custom-fields'*/ ],
			'taxonomies'          => [ /*'category', 'post_tag'*/ ],
			'hierarchical'        => true,
			'public'              => true,
            /*
            # Defaults for public = true
			'show_ui'             => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
            */
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-post',
			'can_export'          => true,
			'has_archive'         => true,
			'capability_type'     => 'page',
		];
		$args = wp_parse_args( $args, $defaults );
		register_post_type( static::uncleanName($cptName), $args );

	}


	public static function cleanName( $str ) {
		return ucwords(str_replace( '-', ' ', $str ));
	}

    public static function uncleanName( $str ) {
        return strtolower(str_replace( ' ', '-', $str ));
    }

}

add_action( 'init', [ \ABetterBalance\Plugin\CustomPostTypes::get_instance(), 'init' ]);
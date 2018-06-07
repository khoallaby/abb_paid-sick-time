<?php
namespace ABetterBalance\Plugin;

use PHPExcel_IOFactory;


class Importer extends PaidSickTime {
	public static $keys;


	public function init() {
		#require_once dirname(__FILE__) . '/../../vendor/autoload.php';
		add_action( 'admin_menu', [ $this, 'addSubmenu' ] );
	}




	/******************************************************************************************
	 * Menus
	 ******************************************************************************************/



	/**
	 * Adds a submenu link 'Importer' under the CPT menu
	 */
	public function addSubmenu() {
		$menuSlug = 'importer';
		$hook = add_submenu_page(
			'edit.php?post_type=' . static::$cptName,
			__( ucwords( static::$cptFullName ) . ' Importer' ),
			__( 'Importer' ),
			static::$capability,
			$menuSlug,
			[ $this, 'viewImporter' ]
		);

		# $hook = {cpt}_page_{menuslug}
		# i.e. paid-sick-time-law_page_questions
		//add_action( 'load-' . $hook, [ $this, 'SaveQuestionsPage' ] );

	}
	/**
	 * Generic getView() function that runs common functions like checking for capability access
	 * @param string $view  File name of the view w/o .php
	 */

	public function viewImporter() {
		self::getMenuView( 'admin/importer' );
	}





}

add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Importer::get_instance(), 'init' ));
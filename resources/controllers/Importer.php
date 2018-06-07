<?php
namespace ABetterBalance\Plugin;

use PHPExcel_IOFactory;


class Importer extends PaidSickTime {
	public static $keys;


	public function init() {
		#require_once dirname(__FILE__) . '/../../vendor/autoload.php';
		add_action( 'admin_action_' . static::$cptName . '-importer', [ $this, 'parseUpload' ] );
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



	/******************************************************************************************
	 * Import functions
	 ******************************************************************************************/

	public static function parseUpload() {
		#@todo: add nonce
		#$url = $_SERVER['HTTP_REFERER'];

		if( $file = $_FILES['pstl-import-file']['tmp_name'] )
			static::parseFile( $file );

		$url = html_entity_decode( menu_page_url( $_POST['page'], false ) );

		wp_redirect( $url );
	}


	public static function truncate() {

		$posts = get_posts( [ 'post_type' => static::$cptName, 'numberposts' => 999 ] );
		foreach( $posts as $post )
			wp_delete_post( $post->ID, true);

		Questions::deleteQuestions();
	}


	/**
	 * Loads a file, and gets it ready for parsing. Checks for CSV
	 *
	 * @todo: do a better job at checking for csv
	 * @param $file
	 *
	 * @return mixed
	 */
	public static function loadFile( $file ) {

		$filetype = wp_check_filetype( basename( $file ), null );
		$csv = $filetype['type'] == 'text/csv' ? true : false;

		if( $csv ) {
			$inputFileType = 'CSV';
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$phpExcel = $objReader->load($file);
		} else {
			$phpExcel = PHPExcel_IOFactory::load( $file );
		}

		$worksheet = $phpExcel->getActiveSheet();

		return $worksheet;

	}

	/**
	 * Parses a file into an array
	 * https://gist.github.com/calvinchoy/5821235
	 * @param string $file      Filename
	 * @param bool $useHeaders  If there's a header column at row 1, use that as metakeys
	 *
	 */
	public static function parseFile( $file, $useHeaders = true ) {

		self::truncate();
		Search::addSearchPage();

		$worksheet = self::loadFile( $file );

		$highestRow    = $worksheet->getHighestRow();
		$highestColumn = $worksheet->getHighestColumn();
		#$statesArray   = $worksheet->rangeToArray( 'B1:' . $highestColumn . '1', null, true, true, true );
		##$countiesArray = $worksheet->rangeToArray( 'B2:' . $highestColumn . '2', null, true, true, true );
		#$citiesArray   = $worksheet->rangeToArray( 'B3:' . $highestColumn . '3', null, true, true, true );

		// loop through columns by letters
		for ( $column = 'A'; $column != $highestColumn; $column++ ) {
			// create $answers array.. start at 4, where the actual data starts
			$answers = [];
			for ( $row = 4; $row <= $highestRow; $row++ )
				$answers[] = $worksheet->getCell( $column . $row )->getCalculatedValue();

			// if column = A, save the questions
			if( $column == 'A' ) {
				Questions::saveQuestions( $answers );
			// add PST
			} else {
				$state  = $worksheet->getCell( $column . 1 )->getValue();
				$county = $worksheet->getCell( $column . 2 )->getValue();
				$city   = $worksheet->getCell( $column . 3 )->getValue();

				if( !empty($county) && !empty($city) )
					$title = $county . ' and ' . $city;
				elseif( !empty($county) )
					$title = $county;
				elseif( !empty($city) )
					$title = $city;
				else
					$title = $state;

				$state  = $title == $state ? true : $state;

				static::addPST( $title, $answers, $state, $city, $county );
			}

		}

	}





}

add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Importer::get_instance(), 'init' ));
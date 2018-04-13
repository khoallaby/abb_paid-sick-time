<?php
namespace ABetterBalance\Plugin;



class Shortcodes extends Base {
	protected $the_title = 'the_title';

	public function init() {
		#add_shortcode( 'name-of-shortcode', [ $this, 'shortcodeFunction' ] );

		#add_filter( 'no_texturize_shortcodes', [ $this, 'no_wptexturize' ] );
		add_action('wp_head', [ $this, 'shortcode_unautop' ] );
	}


	/*
	# example shortcode
	public function shortcodeFunction( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'id'        => '',
			'class'     => '',
		), $atts );

		return $this->getView( 'fileName' );
	}
	*/



	/**
	 * Removes wpautop from happening inside shortcodes
	 * http://stackoverflow.com/questions/5940854/disable-automatic-formatting-inside-wordpress-shortcodes
	 */
	public function shortcode_unautop() {
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 99 );
		add_filter( 'the_content', 'shortcode_unautop', 100 );
	}


    /**
     * Takes an array of shortcodes that won't
     * @param array $shortcodes
     *
     * @return array
     */
	public function no_wptexturize( $shortcodes ) {
		$shortcodes[] = 'row';
		return $shortcodes;
	}


}

add_action( 'plugins_loaded', [ \ABetterBalance\Plugin\Shortcodes::get_instance(), 'init' ] );
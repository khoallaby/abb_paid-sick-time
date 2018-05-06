<?php
namespace ABetterBalance\Plugin;

use Mpdf\Mpdf;


class Pdf extends Base {

    public static $pdf;

	public function init() {
        require_once __DIR__ . '/../../vendor/autoload.php';
        self::$pdf = new \Mpdf\Mpdf();
	}


	# https://gist.github.com/calvinchoy/5821235
    public static function renderPdf( $posts = null, $questions = null ) {

	    $content = self::getView( 'theme/pdf-paid-sick-time-law', true );
        $stylesheet = file_get_contents( abb_pst_plugin_path . '/assets/css/style.css');
        #die($stylesheet);

        self::$pdf->WriteHTML( $stylesheet, 1 );
        self::$pdf->WriteHTML( $content, 2 );
        self::$pdf->Output();
    }



}

add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Pdf::get_instance(), 'init' ));
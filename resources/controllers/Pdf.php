<?php
namespace ABetterBalance\Plugin;

use Mpdf\Mpdf;


class Pdf extends Base {

    public static $pdf;

	public function init() {
        require_once __DIR__ . '/../../vendor/autoload.php';
        self::$pdf = new \Mpdf\Mpdf( [
        	'orientation' => 'L',
	        'shrink_tables_to_fit' => 1,
	        'shrink_this_table_to_fit' => 1,
	        #'table_error_report' => true,
        ] );
	}


	# https://gist.github.com/calvinchoy/5821235
    public static function renderPdf( $vars = [] ) {
	    self::$pdf->shrink_tables_to_fit = 1;
	    self::$pdf->shrink_this_table_to_fit = 1;
	    $content = self::getView( 'theme/pdf-paid-sick-time-law', true, $vars );
        $stylesheet = file_get_contents( abb_pst_plugin_path . '/assets/css/style.css');

        self::$pdf->WriteHTML( $stylesheet, 1 );
        self::$pdf->WriteHTML( $content, 2 );
        self::$pdf->Output();
    }



}

add_action( 'plugins_loaded', array( \ABetterBalance\Plugin\Pdf::get_instance(), 'init' ));
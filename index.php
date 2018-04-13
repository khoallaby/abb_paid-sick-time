<?php

/**
 * Plugin Name: ABB - Paid sick time laws
 * Plugin URI: http://www.ABetterBalance.org
 * Description: Paid sick time laws
 * Version: 0.0.1
 * Author: Andy Nguyen
 * Author URI: http://www.andynguyen.net
 */


define('pstl_plugin_url',plugin_dir_url(__FILE__ ));
define('pstl_plugin_path',plugin_dir_path(__FILE__ ));

# todo: build importer pls
require_once( dirname(__FILE__) . '/resources/controllers/Base.php');
require_once( dirname(__FILE__) . '/resources/controllers/CustomPostTypes.php');
require_once( dirname(__FILE__) . '/resources/controllers/PaidSickTime.php');
require_once( dirname(__FILE__) . '/resources/controllers/Questions.php');
require_once( dirname(__FILE__) . '/resources/controllers/Answers.php');


function vard($s) {
    echo '<pre>';
    var_dump($s);
    echo '</pre>';
}
<?php
namespace ABetterBalance\Plugin;
global $post;


if( isset($_POST) || isset($_GET) ) {
    Base::getView('search-results');
} else {
    Base::getView('search-form');
}


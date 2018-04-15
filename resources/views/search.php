<?php
namespace ABetterBalance\Plugin;
global $post;


if( isset($_REQUEST) && !empty($_REQUEST) ) {
    Base::getView('search-results');
} else {
    Base::getView('search-form');
}


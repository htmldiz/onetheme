<?php
require_once dirname(__FILE__).'/inc/install.php';
require_once dirname(__FILE__).'/inc/enqueue.php';
require_once dirname(__FILE__).'/inc/widgets.php';
if (!function_exists('onetheme_the_posts_navigation')) {
	function onetheme_the_posts_navigation(){
		the_posts_pagination();
	}
}
<?php 

if ( comments_open() ) {
	if ( have_comments() ) {
		wp_list_comments();
		the_comments_navigation();
	}
}
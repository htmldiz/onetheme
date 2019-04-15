<?php get_header();
while ( have_posts() ) :
	the_post();
	onetheme_the_posts_navigation();
endwhile;
get_footer(); ?>
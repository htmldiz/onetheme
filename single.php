<?php get_header();
while ( have_posts() ) :
	the_post();
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_tags();
	wp_link_pages();
	comment_form();
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	} ?></div>
<?php endwhile;
get_footer(); ?>
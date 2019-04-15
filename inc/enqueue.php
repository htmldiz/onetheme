<?php 

/**
 * 
 */
class Onetheme_enqueue
{
	
	function __construct()
	{
		add_action( 'wp_enqueue_scripts', array( $this , 'enqueue_scripts' ) );
	}
	function enqueue_scripts(){
		wp_enqueue_style( 'style-name', get_stylesheet_uri() );
		wp_enqueue_script(
			'webinarstheme-scripts',
			get_template_directory_uri() . '/js/webinarstheme.script.js',
			array('jquery')
		);
		if ( is_singular() ){
			wp_enqueue_script( "comment-reply" );
		}
	}
}
$Onetheme_enqueue = new Onetheme_enqueue();
?>
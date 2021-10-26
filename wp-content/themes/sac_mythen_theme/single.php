<?php get_header(); ?>

<section id="content" role="main">
	<?php if ( have_posts() ) :  ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); 
			$my_id= get_the_ID();
			$my_args = array("post_id" => $my_id); ?>			
			<?php get_template_part( 'entry', NULL, $my_args ); ?>
			<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
		<?php endwhile;?> 
	<?php endif; ?>

		<?php get_template_part( 'nav', 'below-single' ); ?>

</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
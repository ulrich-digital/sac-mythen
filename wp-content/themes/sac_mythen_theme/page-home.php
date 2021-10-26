<?php
/*
* Template Name: Home
*/
?>
<?php get_header(); ?>

<section id="content" role="main">
	<?php 
	if ( have_posts() ) : 
		while ( have_posts() ) : 
			the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<section class="entry-content">
					<?php 
					
					//the_content();
					$blocks=parse_blocks(get_the_content());
					foreach($blocks as $block):
						if("acf/slick-slider-front-page" == $block["blockName"]):
							echo render_block($block);
						elseif("acf/aktuelles-auf-startseite" == $block['blockName']):
							echo render_block($block);
						elseif("acf/aktuelle-touren-auf-startseite"==$block["blockName"]):
							echo render_block($block);
						elseif("acf/neue-tourenberichte" == $block["blockName"]):
							echo render_block($block);
						endif;
					endforeach;
					$my_post_id = get_the_ID(); ?>
				</section>
			</article>
		<?php endwhile; ?>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
	<div class="chip_button_container flex centered">
		<?php 
		/* =============================================================== *\ 
		   Link zu 2.4 Touren-Archiv – Auflistung aller vergangener Touren
		\* =============================================================== */ 
		echo make_button_touren_archiv();
		?>
	</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
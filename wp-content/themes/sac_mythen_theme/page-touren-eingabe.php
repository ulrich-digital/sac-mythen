<?php 
/*
Template Name: Touren-Eingabe
*/

/* ================================================================================================ *\ 

 	 Dieses Template stellt eine Frontend-Eingabe-Maske für die Toureneingabe bereit. 

\* ================================================================================================ */ 
?>

<?php get_header(); ?>

<section id="content" role="main">
	<?php if ( have_posts() ) : 
		while ( have_posts() ) : 
			the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="header">
					<h1 class="entry-title"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
				</header>
	
				<section class="entry-content">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
					<?php the_content(); ?>
					
					

					<section data-target-id="#section_1" class="form_section">
						<h3 class="form_header">1. Wann soll Deine Tour stattfinden?</h3>
						<div class="flip_card_contaienr">
							<div class="flip_card front_side ">
								<div class="flip_card_inner">
									
									<div class="flip_card_front rounded white bordered box_shadow flex">
										<div class="card_content">
											<div class="input_container">
												<label for="start">Start date:</label>
												<input type="date" name="eintaegig_start" value="">
											</div>
										</div>
									</div>
									
									<div class="flip_card_back rounded white bordered box_shadow">
										<div class="card_content">
											<div class="input_container">
												<label for="start">Start date:</label>
												<input type="date" name="mehrtagig_start" value="">
											</div>

											<div class="input_container">
												<label for="start">Start date:</label>
												<input type="date" name="mehrtagig_ende" value="">
											</div>
										</div>
									</div>								
								</div>
							</div>
							
							<?php // Button für ein oder mehrtägite Tour ?>
							<div class="chip_button_container flex centered">	
								<div class="flip_chard_button plus more_button animated_button white go_green extreme_rounded" style="background: var(--alertRed); color:white">Mehrtägige Tour</div>
							</div>
						</div>
						
						
						<div style="height:10vh"></div>
						<h4 class="form_header">Hier kannst du bereits mögliche Verschiebe-Daten angeben</h4>
						<?php
						echo make_verschiebe_datum();
						?>
						

						<div class="chip_button_container flex centered">	
							<div class="plus_verschiebe_datum plus more_button animated_button white go_green extreme_rounded section_button" style="background: var(--alertRed); color:white"> <i class="fa-solid fa-circle-plus"></i>&nbsp;Verschiebe-Datum hinzufügen </div>
						</div>

						
						<div class="button_line">
							<a class="text_button box_shadow prev anchor_link" data-id="#section_0" href="#"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
							<a class="text_button box_shadow next anchor_link" data-id="#section_2" href="#">weiter <i class="fa-solid fa-chevron-right"></i></a>	
						</div>
				</section>

				<?php // Tourenbericht ?>	
				<section data-target-id="#section_2" class="form_section">
					<h3>1. Wann findet die Tour statt?</h3>
					<div class="button_line">
						<a class="text_button box_shadow prev anchor_link" data-id="#section_1" href="#"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
						<a class="text_button box_shadow next anchor_link" data-id="#section_3" href="#">weiter <i class="fa-solid fa-chevron-right"></i></a>	
					</div>
				</section>
					
					
					
					
					
					
					
					
					
					
					
					
					<div class="entry-links"><?php wp_link_pages(); ?></div>
				</section>
			</article>
			
			<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
		<?php endwhile; ?>
	<?php endif; ?>
</section>

<style>
.flip_card {
	background-color: transparent;
	width: 300px;
	height: 100px;
	perspective: 1000px; /* Remove this if you don't want the 3D effect */
	margin: auto;
	width: 90%;
	max-width: 400px;
	}

.flip_card_front{
	height:60px
	}

.flip_card_back{
	height:100px
	}

.card_content{
	display:flex;
	justify-content: center;
	align-items: center;
	flex-wrap: wrap;
	width: 100%;
	height:100%;
	padding:10px;
	
	}


/* This container is needed to position the front and back side */
.flip_card_inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.8s;
  transform-style: preserve-3d;
}


.flip_card.back_side .flip_card_inner{
	transform: rotateY(180deg);
	
}

/* Position the front and back side */
.flip_card_front, .flip_card_back {
  position: absolute;
  width: 100%;
  -webkit_backface-visibility: hidden; /* Safari */
  backface-visibility: hidden;
}


/* Style the back side */
.flip_card_back {
  transform: rotateY(180deg);
}
</style>


<?php get_sidebar(); ?>
<?php get_footer(); ?>
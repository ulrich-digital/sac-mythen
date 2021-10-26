<?php 
/*
* 	Ausgabe der Inhlate:
*	- archive > Shortcards
*	- single > default
*	((single-touren > siehe: single-touren.php))
*/

$my_args = my_archive_queries();
$is_vergangene_touren = $my_args['vergangene_touren'];
$is_aktuell_archiv = false;

// Wird mit get_template_part von page-katuel-archive.php mitgegeben
if(array_key_exists('aktuell_archiv', $args)==true):
	if($args['aktuell_archiv']==true): 
		$is_aktuell_archiv = true;
	endif;
endif;

/* =============================================================== *\ 
 	 Variablen von search-result.php 
\* =============================================================== */ 
$search_result_from = "";
if(isset($args)):
	if(count($args)>0):
		if(array_key_exists("search_result_from", $args)):
			$search_result_from = $args["search_result_from"];
		endif;
	endif;
endif;


/* =============================================================== *\ 
	H1 bei Single
	H2 bei Archive
\* =============================================================== */ 
$h_tag = "";
if (is_singular()):
	$h_tag = "h1";
else:
	$h_tag = "h2";
endif;

$post_id = get_the_ID();
if(isset($args['post_id'])):
	$post_id = $args['post_id'];
endif;

/* =============================================================== *\ 
   Front-Page: Touren
   Archive-Page: Touren
\* =============================================================== */ 
if( ((is_front_page())&&($args['post_type']=="touren"))  || ( (get_post_type()=="touren") && (is_archive()) ) ): 
	//echo "front_page: Touren";
	//echo "entry" .  $args["post_id"];
	
	?>
	<article id="post-<?php echo $post_id; ?>" <?php post_class(get_bereiche_und_art_der_tour_for_single_post($post_id)['classes']); ?> data-temp-filter="<?php echo get_bereiche_und_art_der_tour_for_single_post($post_id)['plain']; ?>">
		<div class="touren_card rounded shadow bordered">
			<div class="card_header chips_container centered"><?php echo get_all_bereiche_als_chip($post_id); ?></div>
				
			<div class="card_main">	
	  			<?php echo (get_current_tourdatum($post_id));  // Datumsausgabe ?>
				<?php echo "<" . $h_tag . " class='entry-title'>";  // Titel verlinkt ?>
				<a href="<?php echo get_the_permalink($post_id); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title($post_id); ?></a>
				<?php echo "</" . $h_tag . ">";
				echo "<div class='tourenleiter'>" . get_current_tourenleiter_name($post_id) . "</div>"; //Tourenleiter ?>
			</div>
			
			<div class="card_footer">
				<?php echo get_current_kurzinfo($post_id); ?>
				<a href="<?php echo get_the_permalink($post_id); ?>" title="<?php the_title_attribute(); ?>" class="more_button go_grey rounded">Mehr&nbsp;<i class="fa-solid fa-chevron-right"></i></a>
			</div>
						
		</div>
	</article>

<?php 
/* =============================================================== *\ 
 	 Front-Page: Tourenberichte 
\* =============================================================== */ 
elseif(is_front_page() && ($args['post_type']=="tourenbericht")):
	$my_post_type = "tourenbericht"; ?>

	<article id="post_<?php echo $post_id; ?>" class="<?php print_r(get_bereiche_und_art_der_tour_for_single_post_tourenbericht($post_id, $my_post_type)['classes']); ?>"<?php /*post_class(get_bereiche_und_art_der_tour_for_single_post_tourenbericht($post_id, $my_post_type)['classes']);*/ ?> data-temp-filter="<?php echo get_bereiche_und_art_der_tour_for_single_post_tourenbericht($post_id, $my_post_type)['plain']; ?>">
			<div class="touren_card rounded shadow bordered">
			<div class="card_header chips_container centered"><?php echo get_all_bereiche_als_chip_tourenbericht($post_id, $my_post_type); ?></div>
				
			<div class="card_main">	
				<?php 
				$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
				foreach ( $blocks as $block ):
					//var_dump($block);
					if("acf/slick-slider"==$block['blockName']):
						echo render_block($block);
						$default_bild = false;
					endif;
					
				endforeach;
				
				// OPTIMIZE: Default-Bild hinzufügen, wenn kein Bild hinterlegt wurde
				
				
				
				?>
				<?php echo "<" . $h_tag . " class='entry-title'>";  // Titel verlinkt ?>
					<a href="<?php echo get_the_permalink($post_id); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title($post_id); ?></a>
				<?php echo "</" . $h_tag . ">";
				echo get_current_tourdatum_tourenbericht($post_id, $my_post_type);

				echo "<div class='tourenleiter'>" . get_current_autor($post_id) . "</div>"; //Tourenleiter ?>
			</div>
			
			<div class="card_footer">
				<?php echo get_current_kurzinfo_tourenbericht($post_id, $my_post_type); ?>
				<a href="<?php echo get_the_permalink($post_id); ?>" title="<?php the_title_attribute(); ?>" class="more_button go_grey rounded">Mehr&nbsp;<i class="fa-solid fa-chevron-right"></i></a>
			</div>
						
		</div>
	</article>
<?php
/* =============================================================== *\ 
   Archive-Page: Tourenberichte 
   Search-Results: Tourenberichte
\* =============================================================== */ 
elseif((is_archive() && (get_post_type()=="tourenbericht")) || ($search_result_from == "tourenbericht")):
	//echo "archive_page: Tourenbericht";

	$post_id = get_the_ID();
	if($search_result_from == "tourenbericht"):
		$post_id = $args['post_id'];
	endif;
	if(isset($args)):
		if(isset($args['post_id'])):
			$post_id = $args['post_id'];
		endif;
	endif; 
	?>
	
	<article id="post-<?php echo $post_id; ?>" <?php post_class(get_bereiche_und_art_der_tour_for_single_post($post_id)['classes']); ?> data-temp-filter="<?php print_r(get_bereiche_und_art_der_tour_for_single_post($post_id)['plain']); ?>">
		<div class="touren_card rounded bordered shadow">	
			<div class="card_header chips_container centered"><?php echo get_all_bereiche_als_chip($post_id); ?></div>
			<div class="card_main">	
				<?php 

				
				// Hauptbild
				$default_bild = true;
				$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
				foreach ( $blocks as $block ):
					//var_dump($block);
					if("acf/slick-slider"==$block['blockName']):
						echo render_block($block);
						$default_bild = false;
					endif;
				endforeach;

				if($default_bild == true): ?>
					<figure class="wp-block-image size-large hauptbild">
						<img src="<?php echo get_stylesheet_directory_uri() . '/images/default_image_web_800x533.jpg'; ?>" />
					</figure>
				<?php endif;
				
				//Titel ?>
				<h3><a href="<?php the_permalink($post_id); ?>" title="<?php echo get_the_title($post_id); ?>"><?php echo get_the_title($post_id); ?></a></h3>
				
				<?php 
				// Tourdatum
				echo get_current_tourdatum($post_id);
				// Tourenleiter
				echo "<div class='tourenleiter'>" . get_current_autor($post_id) . "</div>"; ?>
			</div>
			
			<div class="card_footer">
				<?php echo get_current_kurzinfo($post_id); ?>
				<a href="<?php the_permalink($post_id); ?>" title="<?php echo get_the_title(); ?>" class="more_button go_grey rounded">Mehr&nbsp;<i class="fa-solid fa-chevron-right"></i></a>
			</div>
						
		</div>
	</article>

<?php
/* =============================================================== *\ 
   Archiv-Page: Archivierte Touren (post_status="archiv")
   Search-Results: Archivierte Touren
\* =============================================================== */ 

elseif(($is_vergangene_touren==true) || ($search_result_from=="vergangene_touren")): 
	//echo " vergangene_Touren";

	$post_id = get_the_ID();
	if(isset($args)):
		
		if(isset($args['post_id'])):
			$post_id = $args['post_id'];
			$my_post_id = $args['post_id'];
		endif;
	endif; ?>
	
	<article id="post-<?php echo $post_id; ?>" class="touren type-touren status-archiv hentry post-<?php echo $post_id . ' ' . get_bereiche_und_art_der_tour_for_single_post($post_id)["classes"]; ?>" data-temp-filter="<?php print_r(get_bereiche_und_art_der_tour_for_single_post($post_id)['plain']); ?>">
		<div class="touren_card touren_list_card rounded shadow bordered">	
			<div class="card_main">	
				<div class="row head">
					<?php 
					$the_title_attribute = get_the_title($post_id); ?>
						<div class="titel_container">
							<a class="more_button animated_button grey go_green rounded" href="<?php echo get_the_permalink($post_id); ?>" title="<?php echo get_the_title($post_id); ?>"><h2><?php echo get_the_title($post_id); ?></h2></a>
						</div>
				
					
					<div class="container info">
						<div class="info_button chip go_green">
							<i class="fa-solid fa-circle-info"></i>
						</div>
					</div>
					
					<?php if(bericht_id_der_verknuepften_tour($post_id)!=""):?>
						<a class="chip bordered go_green" href="<?php the_permalink(bericht_id_der_verknuepften_tour($post_id)); ?>" title="Tourenbericht <?php echo $the_title_attribute; ?>">
							<i class="fa-solid fa-circle-camera"></i> <i class="fa-solid fa-chevron-right"></i>
						</a>
					<?php endif; ?>
				</div>
				
				<div class="row details">
					<div class="chips_container"><?php echo get_all_bereiche_als_chip($post_id); ?></div>
					<?php echo get_current_kurzinfo($post_id);
					echo "<div class='tourenleiter'>" . get_current_tourenleiter_name($post_id) . "</div>"; //Tourenleiter ?>
				</div>
				
				<!--<a class="more_button grey" href="<?php echo $the_permalink; ?>" title="SAC Sektion Mythen: <?php echo $the_title_attribute; ?>">Zur ausschreibung <i class="fa-solid fa-chevron-right"></i></a>-->
			</div>			
		</div>
	</article>
<?php
/* =============================================================== *\ 
   Single-Page: Tourenbericht 
\* =============================================================== */ 
elseif(is_single()&& get_post_type()=="tourenbericht"): 
	//echo "is_single && tourenbericht";

	// grid_item entfernen
	$my_classes = get_bereiche_und_art_der_tour_for_single_post($post_id)["classes"];
	$my_classes = str_replace("grid_item", "", $my_classes); 
	$autor = "";
	
	global $post;
	$blocks = parse_blocks( get_the_content(NULL, false, $post_id));
	$hauptbild = "";
	$remaining_content = "";
	foreach ( $blocks as $block ):
		if("acf/verknuepfung-zur-tour"==$block['blockName']):		
			$tour_id = $block['attrs']['data']['verknuepfung-zur-tour']; // ID
			$autor = $block['attrs']['data']['autorin']; // Autorenname
		elseif(("core/image"==$block['blockName'])):
			if(isset($block['attrs']['className'])):
				if("hauptbild" == $block['attrs']['className']):
					$hauptbild .= render_block($block); // Hauptbild
				endif;
			endif;
		endif;
	endforeach;
	
	// übriger Inhalt
	foreach($blocks as $block):
		if("acf/verknuepfung-zur-tour"==$block['blockName']):			
		elseif("core/image"==$block['blockName']):	
			if(isset($block['attrs']['className'])):
				if("hauptbild" == $block['attrs']['className']):
				else:
					$remaining_content .= render_block($block); // Bild ohne "hauptbild"
				endif;
			endif;
		else:
			$remaining_content .= render_block($block); // übriger Inhalt
		endif;
	endforeach; ?>
	
	
	<article id="post-<?php echo $post_id; ?>" class="single_tourenbericht post-<?php echo $post_id . ' ' . $my_classes; ?>" data-temp-filter="<?php print_r(get_bereiche_und_art_der_tour_for_single_post($post_id)['plain']); ?>">
		<?php
		/* =============================================================== *\ 
 	 		Ausgabe 
		\* =============================================================== */ 
  		// Titel
			echo "<h1 class='entry-title'>" . get_the_title() . "</h1>";
		
		// Bericht-Datum
		echo get_the_date("j. F Y");

	  
	  ?>

	  <?php if($autor !=""): ?>
	<div>Verfasst von: <?php echo $autor; ?></div>	
	<?php endif; ?>
	  	<div class="rounded white bordered box_shadow">
  			<?php 
			// Hauptbild
			if($hauptbild!=""):
				echo $hauptbild;
			endif;
			echo $remaining_content;
			//get_template_part( 'entry',  'content'  ); ?>
		</div>
		
		<div>Tourdatum: <?php echo get_current_tourdatum($post_id); ?></div>
		<?php 
	  // Bereiche ?>
	  <div class="chips_container"><?php echo get_all_bereiche_als_chip($post_id); ?></div>
	  <?php echo get_current_kurzinfo($post_id);
	  echo "<div class='tourenleiter'>" . get_current_tourenleiter_name($post_id) . "</div>"; //Tourenleiter ?>
		

</article>
<div class="flex centered">
	<a class="more_button white animated_button_left rounded go_green" href="<?php echo get_post_type_archive_link("tourenbericht"); ?>">Alle Tourenberichte</a>
	<a class="more_button white animated_button rounded go_green" href="<?php echo get_the_permalink($tour_id); ?>">Zur Tourenausschreibung</a>
</div>

<?php
/* =============================================================== *\ 
   Aktuell-Archiv 
\* =============================================================== */ 
elseif($is_aktuell_archiv == true):
	//echo "aktuell_archiv";

	$content_post = get_post($args['post_id']);
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	echo $content;
		
		
		
/* =============================================================== *\ 
   Default 
\* =============================================================== */
elseif(!is_front_page()):
	echo get_the_title();

	 ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<?php 
		//echo get_the_content();
		//get_template_part( 'entry',  'content'  ); ?>
	</article>
	<?php 
	
?>
<?php  endif; ?>
<?php get_header(); ?>
<?php 
/* =============================================================== *\ 
 	 Archive-Template für 
	 - Default
	 - Touren 
	 - Aktuelles aus der Sektion
\* =============================================================== */ 


/* =============================================================== *\ 
     Query 
\* =============================================================== */ 
$args = my_archive_queries();
$custom_query = new WP_Query($args); 

/* =============================================================== *\ 
   Posts nur aus dem aktuellen Jahr, bzw. nächstem Jahr (Jahresübergang) auslesen 
   - nächste Touren
   - vergangene Touren
   - Tourenberichte
\* =============================================================== */ 
global $my_posts_array;
$if_search_result = false;
$is_tourenbericht = false;
$is_aktuelle_touren = false;
$is_vergangene_touren = false;
$my_posts_array = array();
if ( $custom_query -> have_posts() ) : 
	while($custom_query->have_posts()):
		$custom_query->the_post();
		
		// Vergangene Touren
		if($args["vergangene_touren"] == true):
			$is_vergangene_touren = true;
			if((date("Y")) <= make_year_date(get_post_meta(get_the_ID())['current_tour_date'][0])):
				array_push($my_posts_array, get_the_ID());
			endif;
		
		// Tourenberichte
		elseif($args["touren_bericht"] == true):
			$is_tourenbericht = true;
			if((date("Y")) <= make_year_date( make_short_date(get_current_tourdatum( get_the_ID() ) ) )):
				array_push($my_posts_array, get_the_ID());
			endif;
		
		// Aktuelle Touren
		elseif($args['aktuelle_touren']==true):
			$is_aktuelle_touren = true;
			array_push($my_posts_array, get_the_ID());
		
		endif;	
	endwhile;
endif;

/* =============================================================== *\ 
   Ausgabe
\* =============================================================== */ 
if(count($my_posts_array)>0): ?>    
    
	<section id="content" role="main">
    	<header class="header">
    		<?php
    		/* =============================================================== *\ 
    		   Titel 
    		\* =============================================================== */ 
            ?>
    		<h1 class="entry-title">
                <?php 
				if($is_tourenbericht == true):
					echo TITLE_TOURENBERICHTE_ARCHIV;
				elseif($is_aktuelle_touren==true):
					echo TITLE_AKTUELLE_TOUREN_ARCHIV;
				elseif($is_vergangene_touren==true):
					echo TITLE_VERGANGENE_TOUREN_ARCHIV;	
				elseif($args['my_page_title']!=""):
                    echo $args['my_page_title'];
                else:
                    echo get_the_title();
                endif; ?>
            </h1>
    			
        	<?php 
            /* =============================================================== *\ 
 	           Search-Form 
            \* =============================================================== */ 
            ?>
            <!--<div id="search"><?php get_search_form(); ?></div>-->
            
            <?php
        	/* =============================================================== *\ 
         	   Menu
               - Isotope bei aktuelle Touren, verganene Touren, Tourenberichte
               - Ajax bei vergangene Touren
               - Ajax bei Tourenberichte
        	\* =============================================================== */
            ?>
			<div class="archive_menu container">
				<?php
				// Ajax Menu
				$is_ajax_menu = false;
				if($args['vergangene_touren'] == true || $args["touren_bericht"]==true): 
					$is_ajax_menu = true; ?>
					<div id="ajax_container" class="ajax_container"><form><?php echo get_ajax_menu(); ?></form></div>
				<?php endif; ?> 

				<?php // Isotope Menu
				echo '<div id="isotope_container" class="isotope_container">';
				echo get_isotope_menu($custom_query); 
				echo '</div>'; ?>
            </div>    
    	</header>
        
        <?php 
        /* =============================================================== *\ 
 	       Loader-Img für Ajax-Suche 
        \* =============================================================== */ 
        if($is_ajax_menu == true): ?>
            <div class="loader_container">
                <img class="loader_gif" src="<?php echo get_stylesheet_directory_uri()."/images/cable-car-duotone.svg"; ?>" />
		    </div>
        <?php endif; ?>
        
        <?php 
		/* =============================================================== *\ 
 	 	   Content 
		\* =============================================================== */ 
		?>
        <div class="overview grid touren_archiv">    
			<div class="grid_sizer" style="width:<?php echo $my_grid_sizer_width; ?>"></div>
			<?php foreach($my_posts_array as $my_post):
				$post_id = $my_post;
				$my_args = array("post_id" => $my_post);
				get_template_part('entry', NULL, $my_args);
			endforeach; ?>    
		</div>
    </section>
<?php endif; //have_posts() ?>



<div class="chip_button_container flex centered">

	<?php 
	/* =============================================================== *\ 
   	   Buttons für Aktuelle Touren
	   Link zu 2.4 Tourenarchiv – Auflistung aller vergangener Touren
	   Link zu 2.5 Tourenberichte-Archiv – Auflistung aller Tourenberichte 
	   PDF's
   \* =============================================================== */ 
	if($args['aktuelle_touren'] == true):
		echo make_button_touren_archiv();
		echo make_button_touren_bericht_archiv();
		echo make_button_jahresprogramme();
	endif;

	/* =============================================================== *\ 
   	   Buttons für Touren-Archiv > Alle vergangenen Touren
   	   Link zu 2.2 Touren – Auflistung aller kommenden Touren
	   Link zu 2.5 Tourenberichte-Archiv – Auflistung aller Tourenberichte
   \* =============================================================== */ 
	if($args['vergangene_touren'] == true):
		echo make_button_aktuelle_touren();
		echo make_button_touren_bericht_archiv();
	endif;

	/* =============================================================== *\ 
	   Buttons für Tourenberichte-Archiv
	   Link zu 2.2 Touren – Auflistung aller kommenden Touren
	   Link zu 2.4 Touren-Archiv – Auflistung aller vergangener Touren
	\* =============================================================== */ 
	if($args['touren_bericht'] == true):
		echo make_button_aktuelle_touren();
		echo make_button_touren_archiv();
	endif;
	?>
</div>



<?php
 // OPTIMIZE:  
 
previous_posts_link('Zurück', $custom_query->max_num_pages);
next_posts_link('Weiter', $custom_query->max_num_pages);

wp_reset_postdata();
?>






<?php //get_sidebar(); ?>
<?php get_footer(); ?>

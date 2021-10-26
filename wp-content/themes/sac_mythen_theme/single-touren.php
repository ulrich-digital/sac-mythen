<?php 
/* =============================================================== *\ 
 	 Template für Single-Tour 
\* =============================================================== */ 
/*
Blocks an anderer Stelle ausgeben
https://florianbrinkmann.com/bestimmte-gutenberg-bloecke-eines-beitrags-im-theme-an-anderer-stelle-ausgeben-6602/

*/
get_header();


/* =============================================================== *\ 
   Diverse Blocks einzeln abfragen und ausgeben,
   damit Reihenfolge garantiert bleibt
\* =============================================================== */ 
$bereich = ""; // Sektion usw.
$bereich_class = "";
$bereich_html = "";
$datum_content = ""; // Datum-Zeile inkl. Verschiebe-Daten etc.
$touren_short_content = ""; // Kurzinfo-Block
$touren_details = "";
$remaining_content = ""; // Restlicher Inhalt
$header_bild = ""; //headerbild
$touren_programm = "";

if ( have_posts() ) :  ?>
	<?php while ( have_posts() ) :
        the_post(); 
        
		$blocks = parse_blocks( get_the_content() );
		$iterator = 0;
		foreach ( $blocks as $block ) {              
			$iterator++;
			
			// Datum
            if('acf/tourdatum'=== $block['blockName']){ 
                $datum_content .= render_block($block);                
            
			// Kurzinfo
			}elseif('acf/touren-kurzinfo' === $block['blockName']){
                if( (isset($block['attrs']['data']['bereich'])) && ($block['attrs']['data']['bereich'] > 0)): // Sektion usw.
                    foreach($block['attrs']['data']['bereich'] as $my_bereich):
						$my_class = $my_bereich . " chip bordered";
						$bereich_html .= '<div class="' . strtolower($my_class) . '">' . $my_bereich . '</div>';
                    endforeach;
                endif;
                $touren_short_content .= render_block($block);  
			
			// Headerbild
			}elseif(($iterator <= 3) && ("core/image" ==$block['blockName']) ){
				$header_bild = render_block($block);
			
			// Details              
			}elseif('acf/touren-details' === $block['blockName']){
			//	$touren_details .= "<h2>Programm</h2>";
				$touren_details .= render_block($block);
			
			// übriger Inhalt
			}else{
                $remaining_content .= render_block($block);      
            }
		}//End foreach
    endwhile;


    /* =============================================================== *\ 
     	 HTML-Ausgabe 
    \* =============================================================== */ 
    ?>        
    <section id="content">
		
		<div class="tour_header">
			<?php 
			if(is_archive_tour() == true): ?>
			<div class="white rounded">Diese Tour ist bereits vergangen</div>
			<?php endif;?>
			
        	<?php if($bereich_html!=""):?>
            	<div class="chips_container"><?php echo $bereich_html; ?></div>
        	<?php endif; ?>
        	<?php echo apply_filters('the_content', $datum_content); ?>
        	<h1 class="title"><?php echo get_the_title(); ?></h1>
        	<div class="leitung">Leitung: <?php echo get_current_tourenleiter_name(get_the_ID()); ?></div>
        	<div class="touren_short_info"><?php echo $touren_short_content; ?></div>
		</div>
		
		
		<?php 
		$tourbody_classes = "tour_body ";
		if(is_archive_tour()==true):
			$tourbody_classes .= "archive_tour ";
		endif;
		?>
		
		<div class="<?php echo $tourbody_classes; ?>">
			<?php 
			if($header_bild!=""):?>
			<div class="flex">
				<div class="header_image bordered"><?php echo $header_bild; ?></div><?php endif; ?>
        		<div class="touren_details flex column"><?php echo $touren_details; ?></div>
			</div>
        	<div class="remaining_content"><?php echo $remaining_content; ?></div>
    	</div>
        

    </section>


	<?php 
    /* =============================================================== *\ 
   	 Buttons für Single-Tour 
	 	Link zu 2.2 Touren – Auflistung aller kommenden Touren, wenn aktuelle (kommende) Tour
	 ? Link zu 2.3. Touren-Single-Page – nächste Tour
	 ? Link zu 2.3 Touren-Single-Page – vorherige Tour
	 	Link zu 2.4 Tourenarchiv – Auflistung aller vergangener Touren, wenn vergangene Tour
	 	Link zu 2.5 Tourenberichte-Archiv – Auflistung aller Tourenberichte
	 	Link zu 2.6 Tourenbericht-Single-Page – Entsprechender Tourenbericht, falls vorhanden  
  \* =============================================================== */ 

	if(is_archive_tour()==true):
		$temp_arr = alle_touren_mit_post_status_archiv();
		if(in_array(get_the_ID(), $temp_arr)==true):
			echo next($temp_arr);
			echo get_the_title(next($temp_arr));
			global $my_posts_array;
			//print_r($my_posts_array);
		else: 
			echo "nicht vorhanden";
		endif;

	endif;
    ?>
	<?php // OPTIMIZE:  ?>
    <div class="chip_button_container flex between">
		<div class="prev_posts more_button rounded white margin_10 go_green"><?php previous_post_link('%link', '<i class="fas fa-chevron-left"></i> ' . LABEL_PREVIOUS_TOUR) ?></div>
		<div class="next_posts more_button rounded white margin_10 go_green"><?php next_post_link('%link', LABEL_NEXT_TOUR . ' <i class="fas fa-chevron-right"></i>') ?></div>
	</div>

    <div class="chip_button_container flex centered">
  	    <?php 
		echo make_button_aktuelle_touren(); 
		if(is_archive_tour()==true):
			echo make_button_touren_archiv();
		endif;
		echo make_button_touren_bericht_archiv();
		?>
    </div>

<?php
endif; 
wp_reset_postdata();
wp_reset_query();
//get_sidebar(); ?>
<?php get_footer();?>

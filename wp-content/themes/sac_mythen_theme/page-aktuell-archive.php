<?php 
/**
*Template Name: Archiv für Aktuelles
*/
?>
<?php get_header(); ?>

<?php 
/* =============================================================== *\ 
 	 Page-Title 
\* =============================================================== */ 
?>
<h1><?php echo TITLE_NEUIGKEITEN_ARCHIV ?></h1>

<?php
/* =============================================================== *\ 
 	 Query 
\* =============================================================== */ 
$args = my_archive_queries();
$custom_query = new WP_Query($args); 
$aktuell_iterator = 0;

if ( $custom_query -> have_posts() ) : 
	while($custom_query->have_posts()):
		$custom_query->the_post();
		
        // Aktuell-Archiv 
        $post_id = get_the_ID();
        get_template_part('entry', NULL, array("post_id" => $post_id, "aktuell_archiv" => true));
        if($aktuell_iterator==0): ?>
			<h2 id="weitere_neuigkeiten"><?php echo LABEL_NEUIGKEITEN_ARCHIV; ?></h2><?php
		endif;
		$aktuell_iterator++;
	endwhile;
    
    /* =============================================================== *\ 
 	   Pagination 
    \* =============================================================== */ 
    $my_button_classes = "more_button white rounded";
    $paged = $args['paged'];
    
    if ( (get_previous_posts_link('', $custom_query->max_num_pages)) || (get_next_posts_link('', $custom_query->max_num_pages)) ):
        echo '<div class="flex centered">';
    endif; 
 
    if ( get_previous_posts_link('', $custom_query->max_num_pages)): ?>
        <a class="animated_button_left <?php echo $my_button_classes; ?>" href="<?php echo get_pagenum_link( $paged -1 ); ?>">Zurück</a><?php
    endif;    

    if ( get_next_posts_link('', $custom_query->max_num_pages) ): ?>
        <a class="animated_button <?php echo $my_button_classes; ?>" href="<?php echo get_pagenum_link( $paged +1 ); ?>">Weiter</a><?php
    endif;    
    
    if ( get_previous_posts_link('', $custom_query->max_num_pages) || get_next_posts_link('', $custom_query->max_num_pages) ) : 
        echo '</div>';
    endif; 
    
endif; // EndLoop
wp_reset_postdata(); ?>

<div class="chip_button_container flex centered">
	<?php 
	/* =============================================================== *\ 
	   Buttons für Tourenberichte-Archiv
	   Link zu 2.2 Touren – Auflistung aller kommenden Touren
	   Link zu 2.4 Touren-Archiv – Auflistung aller vergangener Touren
	\* =============================================================== */ 
	echo make_button_aktuelle_touren();
	echo make_button_touren_archiv();
	?>
</div>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
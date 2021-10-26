<?php

/**
 * Aktuelles auf Startseite Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'aktuelles_' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'aktuelles startseite_block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

/* =============================================================== *\ 

 	 Startseite 

\* =============================================================== */ 
  
//Aktuelles
$auf_startseite_anzeigen = get_field("aktualitaten_auf_startseite_anzeigen");
$titel= get_field("titel");
$button_anzeigen = get_field("button_anzeigen");
$button_text = get_field("button");

$args = array( 
    'post_type' => "aktuell",
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',    
    'posts_per_page' => '1',
    'paged' => 'paged',
    
);

/* =============================================================== *\ 

 	 Link-Target:
     da Aktuelles über eine Page ausgegeben wird (nicht archive),
     muss der Link zur Page ausgelesen werden.
     Dies geschieht dadurch, welche Seite das Page-Template "page-aktuell-archive.php" besitzt
    
\* =============================================================== */ 
$my_archive_page = get_page_by_template("page-aktuell-archive.php");
$my_archive_page_id = $my_archive_page[0]->ID;
$link_target = get_the_permalink($my_archive_page_id);


$startseiten_query = new WP_Query($args); 
if ( $startseiten_query -> have_posts() ) : 
	while($startseiten_query->have_posts()):
		$startseiten_query->the_post();
        $post_id = get_the_ID();
        
        if($auf_startseite_anzeigen && $post_id): ?>
        	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
        		<?php
        		//Titel
        		if($titel):?>
        			<h2><?php echo $titel; ?></h2>
        		<?php endif;
        		
        		// Content
                global $my_actual_date;
                $my_actual_date = get_the_date('j. F Y', $post_id);

                $blocks_aktuelles = parse_blocks(get_the_content(NULL, false, $post_id));
                foreach($blocks_aktuelles as $block_aktuelles):
                    if("acf/aktuelles"==$block_aktuelles['blockName']):
                        echo render_block($block_aktuelles);
                    endif;
                endforeach;
                
                // Button
        		if($button_anzeigen):                    
                    $link_target = $link_target . "#weitere_neuigkeiten";
        			$my_button_text = "Weitere Neuigkeiten aus der Sektion";
        			if($button_text):
        				$my_button_text = $button_text;
        			endif;
        			?>
        			<div class="chip_button_container">
        				<a class="more_button animated_button white go_green extreme_rounded section_button" href="<?php echo $link_target; ?>"><?php echo $my_button_text; ?></a>
        			</div>
        		<?php endif; ?>
        	</div>
        <?php endif; ?>
        
        <?php
    endwhile;
endif;
?>

<style>
.startseite_block{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 5vh auto;
    min-height: 50vh;
    justify-content: center;
}
</style>
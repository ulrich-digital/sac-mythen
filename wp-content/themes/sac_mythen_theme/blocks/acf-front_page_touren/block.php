<?php
/**
 * Aktuelle Touren auf Startseite Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'aktuelle_touren_' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'aktuelle_touren startseite_block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}

// Touren
$alle_touren_anzeigen = get_field("aktuelle_touren_auf_startseite_anzeigen");
$button_anzeigen = get_field("button_anzeigen");
$titel = get_field("titel");
$button_text = get_field("button");
$anzahl_touren_anzeigen = get_field("anzahl_der_anzuzeigenden_touren");

 if($alle_touren_anzeigen):?>
	<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

		<?php 
		// Titel
		if($titel): ?>
			<h2><?php echo $titel; ?> </h2>
		<?php endif; ?>
		
		<?php 
		// Touren anzeigen
		$my_grid_sizer_width = "400px";
		$my_posts_array = get_aktuelle_touren(); 
		//Wenn die Anzahl Touren grösser ist, als bei Touren anzeigen gewählt wurde, $my_posts_array begrenzen
		if((count($my_posts_array)>$anzahl_touren_anzeigen) &&($anzahl_touren_anzeigen !=0)):
			$my_posts_array = array_slice($my_posts_array, 0, $anzahl_touren_anzeigen);
		endif;
		?>
	
		<div class="overview grid touren_archiv">    
			<div class="grid_sizer" style="width:<?php echo $my_grid_sizer_width; ?>"></div>
			<?php 
			foreach($my_posts_array as $my_post):
				$post_id = $my_post;
				$my_args = array("post_id" => $my_post, "post_type" => "touren");
				get_template_part('entry', NULL, $my_args);
			endforeach; ?>
		</div>
		
		<?php
		// Button
		if($button_anzeigen):
			$link_target = get_post_type_archive_link("touren" );
			$my_button_text = "Zum Tourenportal";
			if($button_text):
				$my_button_text = $button_text;
			endif; ?>
			<div class="chip_button_container">
				<a class="more_button animated_button white go_green extreme_rounded centered section_button" href="<?php echo $link_target; ?>"><?php echo $my_button_text; ?></a>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
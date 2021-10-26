<?php

/**
 * Slick Slider Block Template
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'slick_slider_' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'slick_slider';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.

$size = "slick_slider";
if((is_archive() && get_post_type()=="tourenbericht") || (is_front_page() && get_post_type()=="tourenbericht")):
    $size = "card_main_slick_slider";
endif;

// Die beiden Blöcke slick-slider und slick-slider-front-page greifen auf dieses Template zu
// Beim Front-End-Slick-Sider gibt es zuästzlich den Wahr/Falsch-Button "Slider ausgeben",
// welcher hier abegefragt wird.
$slider_ausgeben = true;

if( (is_front_page()) && ("acf/slick-slider-front-page" == $block['name']) ):
    $slider_ausgeben = get_field("slider_anzeigen");
endif;

$image = get_field('image');

if( (have_rows('bilder_slider')) && $slider_ausgeben): ?>
    <ul class="bilder_slider <?php echo $className; ?>">
    <?php while( have_rows('bilder_slider') ): 
		the_row(); 
        $image = get_sub_field('bild'); ?>
        <li class="single_slick">
            <?php echo wp_get_attachment_image( $image, $size ); ?>
			<div class="caption_container">
			<?php if(get_sub_field("legende_titel")): ?>
				<div class="slick_caption"><?php the_sub_field('legende_titel'); ?></div>
			<?php endif; ?>
			
			<?php if(get_sub_field("legende_autor")): ?>
				<div class="slick_caption_autor"><?php the_sub_field('legende_autor'); ?></div>
			<?php endif; ?>
			</div>
		</li>
		
    <?php endwhile; ?>
    </ul>
<?php endif; ?>


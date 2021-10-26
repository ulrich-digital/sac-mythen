<?php
/* =============================================================== *\ 
 	 Block Template für Tourenbericht erfassen
\* =============================================================== */ 
/*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

$test = get_field('text');
// Load the form
//acf_form('new-tourenbericht');
	
	 
?>

<div class=""><?php echo "halo" . $test; ?></div>
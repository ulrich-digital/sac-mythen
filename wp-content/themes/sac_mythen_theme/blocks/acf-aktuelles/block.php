<?php
/**
 * Aktuelles Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'aktuell_beitrag_' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}
$my_block_id = $block['id'];
// Create class attribute allowing for custom "className" and "align" values.
$className = 'aktuell_beitrag';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}



  
  
/* =============================================================== *\ 
 	 Fields 
\* =============================================================== */ 
global $my_actual_date;
$bild = get_field("bild");
$titel = get_field("titel");
$text = get_field("text");
$download = get_field("download");
//$datum = $my_actual_date;
$datum = aktuell_beitraege_datum($my_block_id);
$size = 'card_image';


if($datum==""):
    $datum = get_the_date();
endif;

/* =============================================================== *\ 

 	 Prüfen
     - welche Seite das Page-Template page-aktuell-archive.php besitzt
     - link zu dieser Seite erzeugen
\* =============================================================== */ 
$my_archive_page = get_page_by_template("page-aktuell-archive.php");
$my_archive_page_id = $my_archive_page[0]->ID;
$link_target = get_the_permalink($my_archive_page_id);
$my_media_text_container_classes = "my_media_text_container ";
/* =============================================================== *\ 
     Context 
\* =============================================================== */ 

// Classes 
if(is_page() && !is_front_page()):
    $my_media_text_container_classes .= "white rounded bordered box_shadow is_page ";
endif;

if(is_archive()){
    $my_media_text_container_classes .= "rounded bordered box_shadow ";
}

if(is_front_page() || is_single() || is_archive()){
    $my_media_text_container_classes .= "rounded bordered box_shadow ";
}

// additional Elements like Buttons etc.
/*if(is_archive()){}
if(is_front_page()){}
*/    
/* =============================================================== *\ 
     Text für Startseite kürzen,
     wenn gekürzt, Button anhängen 
\* =============================================================== */ 
$is_trimmed = false;
if(is_front_page()):
    $trimmed_text = wp_trim_words($text, 30);
    if(strcmp($text,$trimmed_text)<0):
        $is_trimmed = true;
        $text = $trimmed_text;
    endif;
endif;
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="<?php echo $my_media_text_container_classes; ?>">
        <?php 
        // Page (not Front-Page)
        if(is_page() && !is_front_page()): 
            
            //Datum 
            /*echo get_the_date();
            echo "<pre>";
            var_dump(get_post($post_id));
            echo "</pre>";
            */
            ?>
            <div class="date"><?php echo $datum; ?></div>
            <?php 
            
            // Titel 
            if($titel): ?>
                <h3><?php echo $titel; ?></h3>
            <?php endif;
            
            // Bild
            if($bild) {
                echo wp_get_attachment_image( $bild, $size, false, array( "class" => "" ) );
            }
            
            // Text
            if($text): ?>
                <div class="text_content"><?php echo $text; ?></div>
                <?php
            endif;
            
            // Download
            if($download): ?>
            <div class="chip_button_container">
                <a class="more_button download animated_button grey extreme_rounded" href='<?php echo $download['url']; ?>'><?php echo $download['title']; ?></a>
            </div>
        <?php endif;
            
        // Front (+ Archive, + Single)
        else: 
            // Bild
            if($bild) {
                echo wp_get_attachment_image( $bild, $size );
            } ?>
            <div class="text_container">
                <div class="date"><?php echo $datum; ?></div>
                <?php 
                //Titel
                if($titel): ?>
                    <h3><?php echo $titel; ?></h3>
                <?php endif; ?>
                
                <?php 
                // Text
                if($text): ?>
                    <div class="text_content"><?php echo $text; ?></div>
                    <?php if($is_trimmed == true): ?>
                        <div class="chip_button_container">
                            <a class="more_button animated_button grey extreme_rounded" href="<?php echo $link_target; ?>">Mehr</a>
                        </div>
                    <?php endif;?>
                <?php endif; ?>
            </div>
            <?php    
        endif; ?>
    </div>
</div>


<?php if(is_single()){
    $link_target = home_url( $path = 'aktuelles_aus_der_sektion');
    $my_button_text = "Weitere Neuigkeiten aus der Sektion";

    ?>
    <div class="chip_button_container">
        <a class="more_button animated_button white go_green extreme_rounded section_button" href="<?php echo $link_target; ?>"><?php echo $my_button_text; ?></a>
    </div>
    <?php
    get_template_part( 'nav', 'below-single' );
}; ?>

<?php 
if(is_single()): ?>
<div> Button </div>
<?php endif; ?>
<style>
.my_media_text_container{
    display: flex;
    border-radius: 5px;
    overflow: hidden;
    background: white;
    max-width: 800px;
    }
    
.my_media_text_container img{
    max-width: 400px;
    object-fit: cover;
    }
    
.my_media_text_container h3{
    height: 32px;
    overflow: hidden;
    line-height: 1;
    }
    
.my_media_text_container .text_container{
    background: white;
    padding: 20px;
    position: relative;
    line-height: 1.3em;
    }
    
.aktuell_beitrag .chip_button_container{    
    margin: 0;
    bottom: 0;
    position: relative;
    right: 0;
    text-align: center;
    width: 100%;
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
    }
    
.my_media_text_container.is_page{
    flex-direction: column;
    max-width: 600px;
    padding: 25px;
        margin: 4em auto;
    }
        
.my_media_text_container.is_page img{
    margin-bottom: 2em;
    max-width: 100%;
    }
    
.my_media_text_container.is_page .text_content{
    line-height: 1.25em;
    }

.my_media_text_container.is_page .text_content p + p{
    margin-top: 1em;
    }
</style>
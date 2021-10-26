<?php get_header(); ?>


<?php 
$count = 1;
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
 $offset = ($paged - 1) * $count;
var_dump($paged);
$args = array( 
    'post_type' => 'aktuell',
    'posts_per_page' => "1",
    'paged' => $paged,
    'offset' => $offset,    
);

$custom_query = new WP_Query($args); 
?>


<main id="content">
<header class="header">
<h1 class="entry-title"><?php single_term_title(); ?></h1>
<div class="archive-meta"><?php if ( '' != the_archive_description() ) { echo esc_html( the_archive_description() ); } ?></div>
</header>
<?php if ( $custom_query->have_posts() ) : while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>
	<div>////// aktuelles //////</div>
	<?php echo "<h2>" . get_the_title() . "</h2>"; ?>

		<div>////// aktuelles //////</div>
<?php endwhile; endif; ?>
<?php //get_template_part( 'nav', 'below' ); 

previous_posts_link('Zurück', $custom_query->max_num_pages);
next_posts_link('Weiter', $custom_query->max_num_pages);?>

</main>
<?php global $wp_query;
 
$big = 999999999; // need an unlikely integer
$translated = __( 'Page', 'mytextdomain' ); // Supply translatable string
 /*
echo paginate_links( array(
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged') ),
    'total' => $custom_query->max_num_pages,
        'before_page_number' => '<span class="screen-reader-text">'.$translated.' </span>'
) );*/
//var_dump(paginate_links());
?>

<div style="height:300px">platz da</div>

<?php wp_reset_postdata(); ?>
<?php /*if(have_posts()): 
	while(have_posts()):
		the_post();
		echo "<h2>" . get_the_title() . "<h2>"; 
	endwhile;
endif;?>
<?php 
//previous_posts_link('Zurück', $custom_query->max_num_pages);
//next_posts_link('Weiter', $custom_query->max_num_pages);
echo "paginate";
echo paginate_links();


get_template_part( 'nav', 'below' ); */?>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
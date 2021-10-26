<?php 

$current_post_type = "";
$is_vergangene_touren = false;
$is_tourenbericht = false;
$is_jahr = false;
$is_bereich = false;
$is_art_der_tour = false;
$search_result_from = ""; // wird bei get_template_part mitgegeben: tourenbericht, vergangene_touren
/* =============================================================== *\ 

 	 $ausgabe_posts_array
     - hauptarray 

    $jahre_posts_array
    - array mit allen Posts im gewählten Daten-Range
    
    $bereiche_posts_array
    - array mit allen Posts des gewählten Bereiches
     
\* =============================================================== */ 
$ausgabe_posts_array = array();
$jahre_posts_array = array();
$art_der_tour_posts_array = array();
$bereiche_posts_array = array();
  
 // OPTIMIZE: 

// Ist Vergangene Touren
if(in_array("page-template-archive-touren-archive", $args[4])):
    //$is_vergangene_touren = true;
    $search_result_from = "vergangene_touren";
endif;

// Wenn es sich um Tourenbericht handelt
if(array_key_exists("post_type", $args[1])==true):
    $current_post_type = $args[1]['post_type'];
    //$is_tourenbericht = true;
    $search_result_from = "tourenbericht";
endif;

// Wenn Datum gewählt wurde: Datum-Range erstellen
$date_from = "";
$date_to = "";
if(array_key_exists("jahr", $args[0])==true):
    $jahr = $args[0]["jahr"];
    $date_from = $jahr . "0000";
    $date_to = $jahr . "9999";
    $is_jahr = true;
endif;

/* =============================================================== *\ 
   Query
\* =============================================================== */ 
$my_query_args = array( 
    'post_type' => "touren",
    'post_status' => 'archiv',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'meta_key' => 'current_tour_date',
);

if(array_key_exists("post_type", $args[1])==true):
    $my_query_args['post_type'] = $args[1]['post_type'];
    $my_query_args['post_status'] = "publish";
    $my_query_args['orderby'] = "id";
    $my_query_args['meta_key'] = "";
endif;


$custom_query = new WP_Query($my_query_args); 
if ( $custom_query -> have_posts() ) :
	while ( $custom_query -> have_posts() ) : 
        $custom_query -> the_post(); 
    	$post_id = get_the_ID();
        $linked_post_id = get_direct_or_linked_post_id($post_id);
        /* =============================================================== *\ 
           Haupt-Array befüllen
        \* =============================================================== */ 
        array_push($ausgabe_posts_array, $post_id);
        
        /* =============================================================== *\ 
           Jahre-Array befüllen
        \* =============================================================== */ 
        // Wenn Datumsrange gewählt wurde, diesen Post in Array
        if($is_jahr==true): 
        	if($search_result_from == "tourenbericht"):
        		$meta_current_tour_date = get_post_meta($linked_post_id, 'current_tour_date');
        	else:
        		$meta_current_tour_date = get_post_meta($post_id, 'current_tour_date');
        	endif;
        	
        	$current_tour_date = $meta_current_tour_date[0]; 
        	
        	if( ($current_tour_date>=$date_from) && ($current_tour_date<=$date_to) ):
        		array_push($jahre_posts_array, $post_id);
        	endif;
        else: 
        	// wenn kein Datum gewählt wurde, alle Posts in Array
        	array_push($jahre_posts_array, $post_id);
        endif;
	endwhile; 
endif;
wp_reset_postdata();

/* =============================================================== *\ 
   Ausgabe-Array erstellen 
   - Schnittmenge aus Ausgabe-Array mit den restlichen Arrays
   - Jeder Post nur 1 mal
\* =============================================================== */ 
$ausgabe_posts_array = array_intersect($ausgabe_posts_array, $jahre_posts_array);
$ausgabe_posts_array = array_unique($ausgabe_posts_array);

/* =============================================================== *\ 
 	 Post-Ausgabe über entry.php
\* =============================================================== */ 

foreach($ausgabe_posts_array as $single_post):
    get_template_part( 'entry', NULL, array('search_result_from' => $search_result_from, 'post_id' => $single_post) );    
endforeach;

/* =============================================================== *\ 
 	 Ausgabe bei 0 Ergebnissen 
\* =============================================================== */ 
if(count($ausgabe_posts_array)==0): ?>
    <style>
    .fa-secondary{
        fill:#887192;;
        }
    .fa-primary{
        fill:#bfa7c9;
        }
    .sad_image_container {
        display:flex;
        justify-content: center;
        align-items: center;
        margin-top: 2em;
        }
    .sad_image_container svg{
        max-width: 200px;
        width: 100%;
        }
    </style>
    
    <article>
        <div class="sad_image_container">
            <svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="face-sad-tear" class="svg-inline--fa fa-face-sad-tear" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g class="fa-group"><path class="fa-secondary" fill="#333333" d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM336 176c17.68 0 31.97 14.25 31.97 32s-14.29 32-31.97 32c-17.8 0-32.09-14.25-32.09-32S318.2 176 336 176zM159.1 416c-26.52 0-47.95-21-47.95-47c0-20 28.46-60.38 41.54-77.75c3.27-4.375 9.688-4.375 12.84 0C179.5 308.6 208 349 208 369C208 395 186.5 416 159.1 416zM175.1 240c-17.68 0-31.97-14.25-31.97-32s14.29-32 31.97-32c17.8 0 32.09 14.25 32.09 32S193.8 240 175.1 240zM346.2 394.3C323.8 367.4 290.9 352 256 352c-21.19 0-21.19-32 0-32c44.44 0 86.34 19.62 114.7 53.75C384.5 390.3 359.5 410.3 346.2 394.3z"></path><path class="fa-primary" fill="currentColor" d="M336 176c-17.8 0-32.09 14.25-32.09 32s14.29 32 32.09 32c17.68 0 31.97-14.25 31.97-32S353.7 176 336 176zM153.6 291.3C140.5 308.6 112 349 112 369c0 26 21.43 47 47.95 47s48.07-21 48.07-47c0-20-28.57-60.38-41.65-77.75C163.2 286.9 156.8 286.9 153.6 291.3zM175.1 176c-17.68 0-31.97 14.25-31.97 32s14.29 32 31.97 32c17.8 0 32.09-14.25 32.09-32S193.8 176 175.1 176z"></path></g></svg>
        </div>
     </article>
    
     <article>
        <h3 style="text-align:center;color:#bfa7c9;    text-align: center;
        color: #887292;
        font-family: 'Roboto';
        font-weight: 100;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 1.1em;">Nichts gefunden</h3>
    </article> 
     <?php    
    
endif;
 ?>
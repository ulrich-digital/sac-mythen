<?php 
/* =============================================================== *\ 

 	 Sämtliche Funktionen,
	 welche mit der Tour-Erfassung zu tun haben 

\* =============================================================== */ 
/* =============================================================== *\ 
 	 ACF-Blocks 
\* =============================================================== */ 
function make_verschiebe_datum (){
    $html_output='
        <div class="flip_card front_side ">
            <div class="flip_card_inner">
                <div class="flip_card_front rounded white bordered box_shadow flex">
                    <div class="card_content">
                        <div class="input_container">
                            <label for="start">Start date:</label>
                            <input type="date" name="eintaegig_start" value="">
                        </div>
                    </div>
                </div>
                
                <div class="flip_card_back rounded white bordered box_shadow">
                    <div class="card_content">
                        <div class="input_container">
                            <label for="start">Start date:</label>
                            <input type="date" name="mehrtagig_start" value="">
                        </div>
                        <div class="input_container">
                            <label for="start">Start date:</label>
                            <input type="date" name="mehrtagig_ende" value="">
                        </div>
                    </div>
                </div>	
                                            
            </div>
        </div>
        ';
return($html_output);
}
?>
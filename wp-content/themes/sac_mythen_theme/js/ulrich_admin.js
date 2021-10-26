jQuery(document).ready(function ($) {

var $zeitbedarf_und_hoehenmeter_felder = [
    "zeitbedarf_gesamte_tour",
    "zeitbedarf_aufstieg",
    "zeitbedarf_abstieg",
    "hohenmeter_aufstieg",
    "hohenmeter_abstieg"
];

var edit_from_ajax = ajaxcall_admin.my_edit;
//console.log(edit_from_ajax);
var obj = JSON.parse(edit_from_ajax);
var $is_touren = false;
var $is_editor = false;

for (eigenschaft in obj) {
    //console.log(eigenschaft);
    //console.log(obj[eigenschaft])
    if(obj[eigenschaft] == "touren"){ $is_touren = true; }
    if(eigenschaft == "is_block_editor"){ 
        if(obj[eigenschaft]==true){
            $is_editor = true; 
        }
    }
}

/* =============================================================== *\ 
   Tour erfassen
   
   Ein- und Ausblenden von 
      - Rückreise-Datum
      - Datum und Höhenmeter (eintägige Tour)
      - Datum- und Höhenmeter-Repeater (mehrtägige Tour)
      
      die Funktion wp.data.subscribe wird sehr oft aufgerufen und sollte nicht überfrachtet werden
      daher wird sie nur im edit.php-Screen beim Post-Type Touren aufgerufen.
\* =============================================================== */ 

if( ($is_touren == true) && ($is_editor == true)){

    wp.data.subscribe( function () { 
        if($('[data-name="mehrtagige_tour"] .acf-checkbox-list label').hasClass("selected")){
            //$('[data-name="rueckreise_datum"]').css("opacity", "1");
            $('[data-name="dauer_und_hohenmeter_rep"]').css("display", "block"); // Datum- und Höhenmeter-Repeater-Field für mehrtägige Touren
            $.each($zeitbedarf_und_hoehenmeter_felder, function(i, item){
                $('[data-name="' + item + '"]').css("display", "none");
            });
            
        }else if($('[data-name="mehrtagige_tour"] label').not(".selected")) {
            $('[data-name="dauer_und_hohenmeter_rep"]').css("display", "none"); // Datum- und Höhenmeter-Repeater-Field für mehrtägige Touren
            $.each($zeitbedarf_und_hoehenmeter_felder, function(i, item){
                $('[data-name="' + item + '"]').css("display", "block");
            });
        }   
    }); //wp.data.subscribe
} // if( ($is_touren == true) && ($is_editor == true))



// Checkbox "Tour verschieben" auf disabled setzen, wenn Feld hinzugefügt wird.
acf.addAction('append', function( $el ){
    $el.find('[value="tour_verschieben_auf"]').attr("disabled", true);
});

//  Checkbox "Tour verschieben" -> disabled entfernen, bei Click auf Datepicker
$('body').on("click", '[data-name="verschiebe_datum"]', function(evt){
    $(this).closest('.acf-row').find('[value="tour_verschieben_auf"]').attr("disabled", false);
});


// Verhalten bei Click auf Checkbox "Mehrtägige Tour"
//$('[data-name="mehrtagige_tour"] label input').on('click', function(e){
$('body').on("click", '[data-name="mehrtagige_tour"] label input',function(evt){
    
    if($(this).closest('label').hasClass("selected")){
    //    $('[data-name="rueckreise_datum"]').css("opacity", "0");
        $('[data-name="dauer_und_hohenmeter_rep"]').css("display", "none"); // Datum- und Höhenmeter-Repeater-Field für mehrtägige Touren
        $.each($zeitbedarf_und_hoehenmeter_felder, function(i, item){
            $('[data-name="' + item + '"]').css("display", "block");
        });
    }else{
        //$('[data-name="rueckreise_datum"]').css("opacity", "1");
        $('[data-name="dauer_und_hohenmeter_rep"]').css("display", "block"); // Datum- und Höhenmeter-Repeater-Field für mehrtägige Touren
    
        $.each($zeitbedarf_und_hoehenmeter_felder, function(i, item){
            $('[data-name="' + item + '"]').css("display", "none");
        });
    }        
});






/* =============================================================== *\
     Tour erfassen
     Tour verschieben Checkbox
        - Nur eine Checkbox im ganzen Repeater aktiv
\* =============================================================== */  
$('body').on("click", '.tour_verschieben_checkbox[data-name="tour_verschoben_auf"] ul.acf-checkbox-list label input',function(evt){
    // nur ausführen, wenn Datum ausgefüllt ist
    var $date_field = $(this).closest('.acf-row').find('[data-name="verschiebe_datum"] input[type=hidden]');
    $date_field_val = $date_field.val();
    if($date_field_val!=""){
        var $all_labels = $('[data-name="tour_verschoben_auf"] label input');
        $all_labels.not(this).closest('label').removeClass("selected"); 
        $all_labels.not(this).closest('.acf-input').find('input').prop("checked", false);
        
        if($(this).closest('.acf-input').find('label').hasClass("selected")){
            $(this).closest('label').removeClass("selected");
            $(this).prop('checked', false);
        }else{
            $(this).closest('label').addClass("selected");
            $(this).prop('checked', true);
        }   
    }// ifdate_field_val     
});


/* =============================================================== *\ 

 	 Tour erfassen 
     verschiebe Daten- Checkbox disabled, bis ein Datum ausgewählt wurde
\* =============================================================== */ 
  



}); //document(ready)
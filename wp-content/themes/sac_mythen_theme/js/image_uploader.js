
jQuery(document).ready(function ($) {

/* =============================================================== *\ 
   Tourenbericht erfassen
   - Scroll to Anchor 
\* =============================================================== */   
function scrollToAnchor(aid){
    var aTag = $(".form_section[data-target-id='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top},'slow', 'easeOutBack');
}

$(".anchor_link").click(function() {
	var $target = $(this).attr("data-id");
   	scrollToAnchor($target);
    console.log("clic");
});



/* =============================================================== *\ 
 	 Tourenbericht erfassen: Radio checked anfügen 
	 - nur für Validation 
\* =============================================================== */ 
$(".radio__input input").on("click", function(){
	$(this).closest(".fieldset_radio").find(".radio__input input").removeAttr("my_checked");
	$(this).attr("my_checked", "checked");	
});

/* =============================================================== *\ 
	   Upload-Image 
   //https://artisansweb.net/ajax-file-upload-in-wordpress/
\* =============================================================== */   
$('body').on('change', '.input_file', function() {
	var $this = $(this);
	var $fieldset = $(this).closest(".image_upload_fieldset");
	var my_form = $fieldset.find(".file_upload_form")[0];
	var formData = new FormData(my_form);
	formData.append('image', $fieldset.find(".input_file")[0].files[0]); 
	formData.append('action', 'file_upload');

	$(this).closest($fieldset).find(".response_container").append('	<div class="progress extreme_rounded"></div>');

    $.ajax({
		xhr: function(){
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function(evt){
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					//console.log(percentComplete);
					var $progress_breite = percentComplete * 100;
					$progress_breite = $progress_breite + "%";
					$this.closest($fieldset).find(".progress").css("width", $progress_breite);
				}
			}, false);
			return xhr;
		},
        url: aw.ajaxurl,
        type: 'POST',
		cache: false,
        contentType: false,
        processData: false,
        data: formData,
		beforeSend: function(){
			$fieldset.find(".choose_an_image_container").hide();
			$fieldset.find(".response_container").css("display", "flex");
		},
        success: function (response) {
			$fieldset.find(".response_container").html(response);
			$fieldset.find(".input_file").css("display", "none");
			
        },
		error: function(response){
			console.log(response);
		}
    });
});    

/* =============================================================== *\ 
	   Delete Image 
\* =============================================================== */ 
$('body').on( 'click', '.delete_me', function() {
	
	$this = $(this);
	var $fieldset = $(this).closest(".image_upload_fieldset");

	var $id = $(this).attr('data-id');
	$.ajax({
		url: aw.ajaxurl,
		type: 'POST',
		cache: false,
		data: {
			action: 'delete_image',
			id: $id
		},
		beforeSend: function(){
			$fieldset.find(".response_container").hide();
		},
		success: function( result ) {
			//$this.closest($fieldset).find(".input_file").empty();
			//$this.closest($fieldset).find(".file-upload-button").empty();
			$fieldset.find(".input_file").css("display", "block");
			
			$this.closest(".response_container").find(".attachment-thumbnail").remove();
			$this.closest($fieldset).find(".choose_an_image_container").css("display", "flex");
			$this.closest(".response_container").find(".delete_me").remove();
		},
		error:function(jqXHR, textStatus, errorThrown) {
			console.log("error " + textStatus);
			console.log("incoming Text " + jqXHR.responseText);
		},
	});
	$fieldset.remove();
	return false;
});

/* =============================================================== *\ 
	   Add Fieldset 
\* =============================================================== */ 
$('body').on('click', '.plus', function(){
	$.ajax({
	   url: aw.ajaxurl,
	   type: 'POST',
	   cache: false,
	   data: {
		   action: 'add_image_fieldset',
	   },
	   beforeSend: function(){
	   },
	   success: function( result ) {
		  $(result).addClass('animated faster animate__animated pulse').appendTo($(".fieldset_container"));
		  $(result).find(".delete_me").css("opacity", "0.5").show();
	   },
	   error:function(jqXHR, textStatus, errorThrown) {
		   console.log("error " + textStatus);
	   },
   });
  
});
$('.image_upload_fieldset').on("animationend", function(){
    $(this).css('display', 'none');
});


/* =============================================================== *\ 
	  	Create new Post
\* =============================================================== */ 
$(".my_submit_button").on("click", function(){
	var vorname = $("#vorname").val();
	  
	//Tourenbericht-Text
	var text_content = "";
	var myIFrame = document.getElementById('tourenbericht_text_ifr');
	var html = $(myIFrame).contents().find("html").html();
	var p_filter = $(html).filter("p");
	$(p_filter).each(function(){
		var raw_text = $(this).html();
		var texteinheit = "<!-- wp:paragraph --><p>" + raw_text + "</p><!-- /wp:paragraph -->";
		text_content = text_content.concat(texteinheit);
	});
	
	var $myTourenbericht = text_content;
	  
	// Galerie
	$my_galery_obj = {};
	
	
    $(document).find($(".image_upload_fieldset")).each(function(index, value){
        var img_id = $(this).find(".data_id").attr("data-id");
		var caption = $(this).find(".caption_text").val();
		$my_galery_obj[img_id] = caption;
    });
	// Verknüpfung zur Tour
	// ID der Tour
	$my_tour_id = $(".fieldset_radio").find("input[my_checked='checked']").val();
	// Vor und Nachname
	var $my_vorname = $("#vorname").val();
	var $my_nachname = $("#nachname").val();
	// Telefonnummer
	var $my_telefon_nr =$("#telefon").val();
	// Email
	var $my_email = $("#email").val();


	/* =============================================================== *\ 
	   Form-Validation 
	\* =============================================================== */ 
  	var form_is_valide = true;
  
	if($my_tour_id=="" || typeof $my_tour_id === "undefined"){
		form_is_valide = false;
		$("#radioError").addClass("visible");	
	}else{
		$("#radioError").removeClass("visible");	
	};

	if($myTourenbericht.length<=73){
		form_is_valide = false;
		$("#textareaError").addClass("visible");	
	}else{
		$("#textareaError").removeClass("visible");	
	}
	if($my_vorname==""){
		form_is_valide = false;
		$("#vornameError").addClass("visible");	
	}else{
		$("#vornameError").removeClass("visible");	
	}
	if($my_nachname==""){
		form_is_valide = false;
		$("#nachmameError").addClass("visible");	
	}else{
		$("#nachmameError").removeClass("visible");	
	}
    if($my_telefon_nr==""){
		form_is_valide = false;
		$("#telefonError").addClass("visible");	
	}else{
        $("#telefonError").removeClass("visible");	
	}
	if($my_email==""){
		form_is_valide = false;
		$("#emailError").addClass("visible");	
	}else{
		$("#emailError").removeClass("visible");	
	}

	if(form_is_valide==true){
		$.ajax({
			url: aw.ajaxurl,
			type: 'POST',
			cache: false,
			data: {
				action: 'create_new_tourenbericht',
				myTourID: $my_tour_id,
				myTourenbericht: $myTourenbericht,
				myGalleryObj: $my_galery_obj,
				myVorname: $my_vorname,
				myNachname: $my_nachname,
				myTelefonNr: $my_telefon_nr,
				myEmail: $my_email
			},
			success: function( result ) {
                console.log(result);
		//		console.log(aw.thank_you_url);
				window.location = aw.thank_you_url
				
			},
			error:function(jqXHR, textStatus, errorThrown) {
				console.log("error " + textStatus);
				console.log("incoming Text " + jqXHR.responseText);
			},
		});
	}
})



});//ready beenden

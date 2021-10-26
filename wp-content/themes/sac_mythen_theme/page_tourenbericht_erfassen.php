<?php
/*
Template Name: Tourenbericht erfassen
Template Post Type: page

*/
?>
<?php get_header(); ?>

<section id="content" role="main">
	
	<?php if ( have_posts() ) : 
		while ( have_posts() ) : 
			the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="header">
					<h1 class="entry-title"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
				</header>
	
				<section class="entry-content">
					<?php the_content(); ?>					
					<?php if(post_password_required()==false):
						?>
					
						<section data-target-id="#section_0" class="form_section">
							<h3 class="form_header">Willkommen!</h3>
							<div class="container white rounded box_shadow">
								<div class="container dashed rounded hello">
									<p>Schön, dass Du hier bist! Du hast sicher eine erlebnisreiche Tour in guter Gesellschaft erlebt!</p>
									<p>Auf dieser Seite kannst Du den Touren-Bericht eingeben. Dieser wird hier auf der Webseite und auch in den Clubnachrichten veröffentlicht.</p>
									<p><br /></p>
									<p><i class="fa-solid fa-circle-exclamation"></i> <strong>Doch bevor du beginnst - lege Dir ein paar Fotos parat.</strong> Du kannst diese dann gleich hochladen.</p>
									<p><br /></p>
									<p>Wir wünschen Dir viel Spass beim Schreiben,<br /> bedanken uns für Deine Mitarbeit,<br />und freuen uns auf deinen Bericht.</p>
									<p><br /></p>
									<p class="italic">SAC Sektion Mythen</p>
									<p class="italic">Webmaster & Chef Clubnachrichten</p>
								</div>
							</div>
							
							<div class="button_line">
								<a class="text_button box_shadow next anchor_link" data-id="#section_1" href="#section_1">Beginnen <i class="fa-solid fa-chevron-right"></i></a>	
							</div>
						</section>
						
						<?php // Tour auswählen ?>						
						<section data-target-id="#section_1" class="form_section">
							<h3 class="form_header">1. Wähle zuerst deine Tour aus</h3>
						
							<?php echo alle_touren_ohne_bericht_ausgeben(); ?>	
						
							<div class="button_line">
								<a class="text_button box_shadow prev anchor_link" data-id="#section_0" href="#section_0"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
								<a class="text_button box_shadow next anchor_link" data-id="#section_2" href="#section_2">weiter <i class="fa-solid fa-chevron-right"></i></a>	
							</div>
						</section>

						<?php // Tourenbericht ?>	
						<section data-target-id="#section_2" class="form_section">
							<h3 class="form_header">2. Schreibe hier Deinen Tourenbericht</h3>
							<div class="italic grey"><i class="fa-solid fa-circle-exclamation"></i> Titel, Datum und Autoren-Name werden automatisch hinzugefügt und müssen nicht angegeben werden.</div>
							<p><br /></p>
							<?php 
							//Tinymce
							echo "<script>
								jQuery(document).ready(function ($) {
									tinymce.init({
										selector: '#tourenbericht_text',
										content_css: '" . get_stylesheet_directory_uri() . "/style-editor.css',						
										skin: 'lightgray',
										theme: 'modern',
										textarea_rows: 40,
										height: '500px',
										editor_class: 'tinymce_textarea',
										teeny: false,
										wpautop: false,
										media_buttons: false,
										quicktags: false,
										toolbar: 'bold italic underline undo redo',
										statusbar: false,
									});						
								});
							</script>";
							?>
							<div class="tinymce_container box_shadow">
								<textarea id="tourenbericht_text" name="tourenbericht_text" placeholder="Es muss kein Titel oder Datum angegeben werden."></textarea>						
							</div>
							
							<div class="button_line">
								<a class="text_button box_shadow prev anchor_link" data-id="#section_1" href="#section_1"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
								<a class="text_button box_shadow next anchor_link" data-id="#section_3" href="#section_3">weiter <i class="fa-solid fa-chevron-right"></i></a>	
							</div>
						</section>

						<?php // Bilder ?>	
						<section data-target-id="#section_3" class="form_section">
							
							<h3 class="form_header">3. Füge Bilder hinzu (optional)</h3>
							<div class="dragndrop_container">
								<div class="fieldset_container">
									<?php add_image_fieldset_callback(); ?>
								</div>
									
							</div>	
							<div class="chip_button_container flex centered">	
								<div class="plus more_button animated_button white go_green extreme_rounded section_button" style="background: var(--alertRed); color:white"> <i class="fa-solid fa-circle-plus"></i>&nbsp;Bild hinzufügen </div>
							</div>
							
							
							<div class="button_line">
								<a class="text_button box_shadow prev anchor_link" data-id="#section_2" href="#section_2"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
								<a class="text_button box_shadow next anchor_link" data-id="#section_4" href="#section_4">weiter <i class="fa-solid fa-chevron-right"></i></a>	
							</div>
						</section>
						
						<?php // Kontakt-Angaben ?>						
						<section data-target-id="#section_4" class="form_section">
							<h3 class="form_header">4. Kontakt-Angaben</h3>
							<div class="kontakt container white rounded box_shadow">
								<div class="zeile">
									<div class="flex_container">
										<label for="vorname">Vorname: </label>
										<input type="text" id="vorname" name="vorname" placeholder="" class="rounded bordered" required>
									</div>
								
									<div class="flex_container">
										<label for="nachname">Nachname: </label>
										<input type="text" id="nachname" name="nachname" placeholder="" class="rounded bordered" required>
									</div>
								</div>
									
								<div class="zeile">
									<div class="flex_container">
										<label for="email">Email: </label>
										<input type="email" id="email" name="email" placeholder="wird nicht veröffentlicht" class="rounded bordered" required>
									</div>
									<div class="flex_container">
										<label for="telefon">Telefon: </label>
										<input type="tel" id="telefon" name="tel" placeholder="wird nicht veröffentlicht" class="rounded bordered" required>
									</div>
								</div>
							</div>
							
							<div id="radioError" class="error message alert_red rounded box_shadow">Bitte wähle eine Tour aus.</div>
							<div id="textareaError" class="error message alert_red rounded box_shadow">Bitte schreibe einen Touren-Bericht.</div>
							<div id="vornameError" class="error message alert_red rounded box_shadow">Bitte gib Deinen Vornamen an.</div>
							<div id="nachmameError" class="error message alert_red rounded box_shadow">Bitte gib Deinen Nachnamen an.</div>
							<div id="emailError" class="error message alert_red rounded box_shadow">Bitte gib Deine Email an.</div>
							<div id="telefonError" class="error message alert_red rounded box_shadow">Bitte gib Deine Telefonnummer an.</div>

							<div class="button_line">
								<a class="text_button box_shadow prev anchor_link" data-id="#section_3" href="#section_3"><i class="fa-solid fa-chevron-left"></i> zurück</a>	
								<button id="submit_my_image_upload" class="text_button box_shadow submit next my_submit_button">Tourenbericht einreichen <i class="fa-solid fa-paper-plane"></i></button>
							</div>
						</section>

					<?php endif; //password required ?>
				</section> <?php // entry_content ?>
			</article>
		<?php endwhile; ?>
	<?php endif; ?>
</section><?php //content ?>


<style>
.page-template-page_tourenbericht_erfassen #content{
    max-width: 1000px;
    margin: auto;
    }
    
/* Einloggen */
.page-template-page_tourenbericht_erfassen .post-password-form{
    border: 3px solid var(--alertGreen);
    border-radius: var(--borderRadius_5px);
    background: var(--white);
    padding: 20px;
    line-height: 1.5em;
    letter-spacing: 0.51px;
    margin-top: 2em;
    box-shadow: var(--boxShadow);
    }
    
.page-template-page_tourenbericht_erfassen .password_line{
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    min-height: 2em;
    margin: 1em -10px 0;
    } 

.page-template-page_tourenbericht_erfassen .password_line > *{
    margin:10px;
    }
    
.page-template-page_tourenbericht_erfassen .pass_label{
    margin-right: 1.5em;
    }
        

    
/* TinyMCE */
#mceu_5{
    border:1px dashed;
    border-radius: var(--borderRadius_5px);
    overflow: hidden;
    }
    
#mceu_5.mce-tinymce{
    box-shadow: none;
    }
    
#mceu_6.mce-top-part::before{
    box-shadow: none;
    }
    
#mceu_7-body{
    display: none;
    }
    
#mceu_12.mce-toolbar-grp{
    border-bottom: none;
    background: var(--body_bg); 
    }
    
#mceu_14-body .mce-btn{
    border-radius: var(--borderRadius_5px);
    background: var(--body_bg);
    }

.page-template-page_tourenbericht_erfassen .tinymce_container{
    padding:10px;
    border-radius: var(--borderRadius_5px);
    background: var(--white);
    }  

/* Drag&Drop */ 
.page-template-page_tourenbericht_erfassen .dragndrop_galerie{
    position: relative;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    min-height: 100px;
    padding: 10px;
    border: 2px dashed var(--alertGreen);
    border-radius: var(--borderRadius_5px);
    transition: var(--hoverTrans);
    }

.page-template-page_tourenbericht_erfassen .dragndrop_container.is-dragover,
.page-template-page_tourenbericht_erfassen .dragndrop_galerie:hover{
    background: var(--alertGreenTrans);
    }


.page-template-page_tourenbericht_erfassen .dragndrop_container .messages{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 10px;
    }

.page-template-page_tourenbericht_erfassen .page-template-page_tourenbericht_erfassen .dragndrop_container i{
    position: absolute;
    top:10px;
    right: 10px; 
    color:var(--alertRed);
    font-size: 24px;
    opacity: 0;
    }

.page-template-page_tourenbericht_erfassen .dragndrop_container .box__icon{
    max-width: 50px;
    max-height: 50px;
    margin: 20px;
    fill: grey;
    fill: var(--alertGreen);
    }

.page-template-page_tourenbericht_erfassen .dragndrop_container .message{
    font-family: 'Roboto'; 
    font-weight: 100;
    font-size: 16px;
    line-height: 1.5em;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--alertGreen);
    text-align: center;
    }

.page-template-page_tourenbericht_erfassen .dragndrop_container .choose{
    font-weight: bold;
    }

.page-template-page_tourenbericht_erfassen .my_image_upload{
    position: absolute;
    width: 100%;
    background: red;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    }

.page-template-page_tourenbericht_erfassen .preview_image{
    max-height: 300px;
    margin: auto;
    }
    
.page-template-page_tourenbericht_erfassen .galerie .preview_image{
    width: 100%;
    }

.page-template-page_tourenbericht_erfassen .is-dropped .messages{
    display: none;
    }

/* Kontakt */  
.page-template-page_tourenbericht_erfassen .kontakt .zeile{
    display: flex;
    flex-wrap: wrap;
    }
    
.page-template-page_tourenbericht_erfassen .kontakt .flex_container{
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    width: 50%;
    }

.page-template-page_tourenbericht_erfassen .kontakt .flex_container label{
    flex-basis: 100px;
    flex-shrink: 0;
    }

.page-template-page_tourenbericht_erfassen .kontakt .flex_container input{
    width: calc(100% - 120px);
    padding: 10px;
    margin: 10px 0;
    }  

/* Error Messages */
.error.message{
    padding: 10px 5px;
    margin:10px 0;
    color:white;
    text-align: center;
    display: none;
}

.error.message.visible{
    display: block;
}
.error.message:before{
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    font-family: 'Font Awesome 6 Pro';
    font-weight: 900;
    content: "\f06a";
    opacity: 1;
    color:white;
    margin-right: 5px;
    }  
/* Bilder */
.image_upload_fieldset.animated,
.image_upload_fieldset.animate__animated{
	-webkit-animation-fill-mode: none;
animation-fill-mode: none;
}
.file_upload_form{
	position:relative;
}
	
.response_container{
		display: flex;
align-items: center;
	width:100%;
	height:100%;
	display:none;
	
}
.response_container img{
	object-fit: contain;
	width: 100%;
	height:100%;
	
}
.delete_me{
	position:absolute;
    z-index: 1000;
    top: 0px;
    right: 0px;
    font-size: 24px;
    color: var(--alertRed);
cursor:pointer;
transition: var(--hoverTrans);
    transform: translate(23px, -21px);
}
.delete_me:hover{
	color: var(--alertRedHover);
}
.dragndrop_container{
	margin: -10px;
}

.page-template-page_tourenbericht_erfassen .image_upload_fieldset{
	display: flex;
flex-wrap: wrap;

padding: 10px;
background: var(--white);
border-radius: var(--borderRadius_5px);
width:calc(50% - 20px);
min-width: 400px;
margin: 10px;

}
	.message_container{
		display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 10px;
	width:100%;
	    max-height: calc(100% - 20px);
	}
	.choose_an_image_container{
		display: flex;
flex-direction: column;
justify-content: center;
align-items: center;
	}
.page-template-page_tourenbericht_erfassen .dragndrop_galerie{
	padding:0;
	width: calc(100% - 20px);
    height: 250px;
	margin:10px;

	}
	
.fieldset_container{
	width:100%;
	display:flex;
	flex-wrap: wrap;
	}

.image_upload_fieldset{
	display:flex;
	flex-direction: column;
	width:100%;
	}

.caption_text{
	padding: 10px;
	min-height: 4em;
    flex-grow: 2;
	border: 1px solid var(--formGreen);
	margin:10px;
	}
textarea.caption_text{
	font-family: 'Roboto';
	letter-spacing: 1px;
	font-size: 16px;	
}
.caption_text::-webkit-input-placeholder {
	font-family: 'Roboto';
	color: #33a56a;
	font-style: italic;
	letter-spacing: 1px;
	font-size: 16px;
	}

.caption_text:-ms-input-placeholder {
	font-family: 'Roboto';
    color: #33a56a;
    font-style: italic;
    letter-spacing: 1px;
    font-size: 16px;
	}

.caption_text:-moz-placeholder {
	font-family: 'Roboto';
    color: #33a56a;
    font-style: italic;
    letter-spacing: 1px;
    font-size: 16px;
	}

.caption_text::-moz-placeholder {
	font-family: 'Roboto';
    color: #33a56a;
    font-style: italic;
    letter-spacing: 1px;
    font-size: 16px;
	}
	
.progress {
	background: red;
	display: block;
	height: 20px;
	text-align: center;
	transition: width .3s;
	width: 0;
	min-width: 10px;
	}

.progress.hide {
	opacity: 0;
	transition: opacity 1.3s;
	}
	

	
input[type="file" i]::-webkit-file-upload-button,
.input_file #file-upload-button,
.input_file #file-upload-button span,
.input_file input[type="file" i] {
	opacity: 0;
	display:none;
	}
	
input.input_file{
	width: 100%;
	height: 100%;
	position: absolute;
	margin: 0;
	opacity: 0;
	background-color:red;
	}
	
</style>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
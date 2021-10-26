/*!
 * imagesLoaded PACKAGED v4.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return n.indexOf(t)==-1&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return n!=-1&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){i=i.slice(0),t=t||[];for(var n=this._onceEvents&&this._onceEvents[e],o=0;o<i.length;o++){var r=i[o],s=n&&n[r];s&&(this.off(e,r),delete n[r]),r.apply(this,t)}return this}},t.allOff=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){if(Array.isArray(e))return e;var t="object"==typeof e&&"number"==typeof e.length;return t?d.call(e):[e]}function o(e,t,r){if(!(this instanceof o))return new o(e,t,r);var s=e;return"string"==typeof e&&(s=document.querySelectorAll(e)),s?(this.elements=n(s),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(this.check.bind(this))):void a.error("Bad element for imagesLoaded "+(s||e))}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console,d=Array.prototype.slice;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&u[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var u={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});

jQuery(document).ready(function ($) {
/*document.onreadystatechange = function () {
if (document.readyState === 'complete') {
*/

/* 
 Tourenbericht erfassen > js/image_uploader.js 
 */ 
  
/*schöner Seite laden*/
$("#content_container").animate({
	opacity:1
   	},{
   	duration:500,
   	complete : function(){},
});

/* =============================================================== *\ 
   Hauptmenue
   Bei Scroll > Höhe anpassen
   Wenn header_slider vorhanden: Background anpassen
\* =============================================================== */ 
var $has_header_slider = false;
var $logo = $("#logo");
var $header = $("#header");
var offTop = $('body').offset().top;

if ($(".header_slider")[0]){
    $has_header_slider = true;
}else{
    $header.addClass("colored");
}


if ($(document).scrollTop() > 10 ) {
    $logo.addClass("klein");
    if($has_header_slider== true){            
        $header.addClass("colored");
    }
} else {
    $logo.removeClass("klein");
    if($has_header_slider== true){            
        $header.removeClass("colored");
    }
}

$(document).scroll(function() {
   scroll_start = $(this).scrollTop();
   if (scroll_start > 10) {
       $logo.addClass("klein");
    $header.addClass("colored");
        
	} else {
        $logo.removeClass("klein");
        if($has_header_slider== true){            
            $header.removeClass("colored");
        }
	}
});

//$(".drop_down").find("ul").show(200);
//
    
$(".drop_down_button .more_button").on("click",function(){
    $(this).closest(".drop_down_button").toggleClass("selected");
    $(this).closest(".drop_down_button").find("ul").toggle(200);
})

/* =============================================================== *\ 
 	 Hamburger 
\* =============================================================== */ 
/*
var $hauptmenue_listen_breite = $("#menu .main_menu_container").outerWidth();
var $rausschieben = 0 - $hauptmenue_listen_breite;
$("#menu.out").css('transform', "matrix(1,0,0,1," + $rausschieben +",0)");

$(".hamburger").on('click', function(){

	//Klassen verwalten
	$(this).toggleClass('is-active');
	$(".main_menu_container").toggleClass('bounceOutLeft');
	$(".main_menu_container").toggleClass('bounceInLeft');

	$("#menu").toggleClass("out");
	$hauptmenue_listen_breite = $("#menu .main_menu_container").outerWidth();
	$rausschieben = 0 - $hauptmenue_listen_breite;
	$("#menu").css('transform', "matrix(1,0,0,1,0,0)");
	$("#menu.out").css('transform', "matrix(1,0,0,1," + $rausschieben +",0)");
});


/* =============================================================== *\ 
 	 Home 
	 -- aktuelle Touren immer eingemittet
\* =============================================================== */ 
  
if($(".home .grid article")){
	var $anz_elemente = $(".home .grid article").length;
	var $myClass = "";
	ermittle_spaltenzahl($anz_elemente);

	function ermittle_spaltenzahl(anz_elemente){
		if($anz_elemente == 1){$myClass = "one_column";}else
		if($anz_elemente == 2){$myClass = "two_column";}else
		{$myClass = "three_column";}
	}	
	$(".home .grid").addClass($myClass);
}
$(window).resize(function(){
    if($("#content_container").width()>1200){
        $(".home .grid").removeClass("three_column two_column one_column").addClass("three_column");
    }    
    if($("#content_container").width()<1200){
        $(".home .grid").removeClass("three_column two_column one_column").addClass("two_column");
    }
    if($("#content_container").width()<800){
        $(".home .grid").removeClass("three_column two_column one_column").addClass("one_column");
    }});


/* =============================================================== *\ 
 	 Ajax-Search 
\* =============================================================== */ 

var search_array = []; // jahr:2021
$("#ajax_container button").on("click",function(event){
	event.preventDefault();
	var search_cat = $(this).attr("data-search-category"); // jahr
	var search_val = $(this).attr("value"); // 2021

	delete search_array[search_cat];
	search_array[search_cat] = search_val;
	
	var jObject={}; // Objekt erstellen
	for(i in search_array){
		jObject[i] = search_array[i]; // Elemente zu Objekt hinzufügen
	}

	var my_search_string = JSON.stringify(jObject); // {"bereich":"KiBe","jahr":"2020"}
    
    
	$.ajax({
		url: ajaxcall.ajaxurl, // functions.php
		type:"POST",
		datatype:"json",
		async:true,
        cache:false,
		
		data: {
			action:'my_ajax_search_function', // functions.php
			search_string:my_search_string,
			myqueryvars: ajaxcall.query_vars, 
			post_type: ajaxcall.post_type,
			post_status:ajaxcall.post_status,
			post_classes:ajaxcall.post_classes,
			post_id:ajaxcall.post_id,
		},
		beforeSend: function() {
			$(".loader_container").css("display", "flex");
			$(".grid").empty();  
		},
		success: function(html){
			$(".grid").addClass("search_results");
			$(".loader_container").hide();
			// Isotope neu Instanzieren
			$grid.isotope("destroy");
			$(".grid").append(html);
			var elems = $(".grid_item");
			$(".grid").isotope({
				appended: elems,
				itemSelector: '.grid_item',
				filter: function() {
					var $this = $(this);
					var searchResult = qsRegex ? $this.text().match(qsRegex) : true;
					var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
					return searchResult && buttonResult;
				},
			});
            $('.bilder_slider').slick();
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			console.log("error");
		}
        
	});	

});   


/*======================================*\
	Isotope-Suche: Funktion
\*======================================*/
var qsRegex;
var buttonFilter;
var searchResult;
var buttonFilters = {};
var buttonFilter = "";
var aktuellerFilter = "";
var testString = "";
var filterArray = [];

// init Isotope
var $grid = $('.grid').isotope({
	itemSelector: '.grid_item',
	filter: function() {
		var $this = $(this);
		var searchResult = qsRegex ? $this.text().match(qsRegex) : true;
		var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
		return searchResult && buttonResult;
	},
});


$('.my_isotope_filters').on('click', 'button', function() {
	var $this = $(this);
	var $buttonGroup = $this.parents('.button-group');
	var filterGroup = $buttonGroup.attr('data-filter-group');
	
	buttonFilters[filterGroup] = $this.attr('data-filter');
	aktueller_filter = $this.attr('data-filter');

	var aktuelle_data_filter_group = $this.parents().attr('data-filter-group');

	if ((buttonFilter.indexOf(aktueller_filter) > -1)) {
		var mystring = "";
		mystring = aktueller_filter;
		buttonFilter = buttonFilter.replace(mystring, "");

		// You can use the [] syntax to use an expression as the property name
		delete buttonFilters[aktuelle_data_filter_group];

	} else {
		buttonFilter = concatValues(buttonFilters);
	}

	testString = buttonFilter;
	filterArray = split(testString, ".");
	// Isotope arrange

	$(".grid").isotope();
	// access Isotope properties
});


function substring(str, start, end) {
	for (var j = start, tmpStr = ""; j < end; j++) {
		tmpStr += str[j];
	}
	return tmpStr;
}

function split(str, delimiter) {
	for (var i = 0, res = [], lastDelimiter = -1; i < str.length; i++) {
		if (str[i] === delimiter) {
			res[res.length] = substring(str, lastDelimiter + 1, i);
			lastDelimiter = i;
		}
	}
	// Füge den Rest bis zum Ende des Strings hinzu
	res[res.length] = substring(str, lastDelimiter + 1, str.length);
	return res;
}

// use value of search field to filter
var $quicksearch = $('.quicksearch').keyup(debounce(function() {
	qsRegex = new RegExp($quicksearch.val(), 'giu');
	$grid.isotope();
}));

// flatten object by concatting values
function concatValues(obj) {
	var value = '';
	for (var prop in obj) {
		value += obj[prop];
	}
	return value;
}

// debounce so filtering doesn't happen every millisecond
function debounce(fn, threshold) {
	var timeout;
	threshold = threshold || 100;
	return function debounced() {
		clearTimeout(timeout);
		var args = arguments;
		var _this = this;

		function delayed() {
			fn.apply(_this, args);
		}
		timeout = setTimeout(delayed, threshold);
	};
}


/* =============================================================== *\ 
 	 Ajax - Menu UI 
	 
	 Verhalten: 
	 - Button mit aktuellem Jahr opacity:1
	 - restliche Buttons opacity: 0.5
	 - kein show_all Toggle
	 - is_checked Toggle
\* =============================================================== */ 
var currentTime = new Date();
var year = currentTime.getFullYear();

// Init
$(".ajax.menu button").each(function(){
	if($(this).attr("value")==year){
		$(this).addClass("is_checked");
	}
});


$(".ajax.menu").on("click", "button", function(){
	$(this).closest(".container").find(".is_checked").not(this).removeClass("is_checked"); //Geschwister löschen
	if($(this).hasClass("is_checked")==true){
		//$(this).removeClass("is_checked");	
	}else{
		$(this).addClass("is_checked");
	}
});


// Isotope: Bereiche + Art der Tour
$(".isotope.menu").on("click", "button", function(){
	$(this).closest(".my_isotope_filters").find(".is_checked").not(this).removeClass("is_checked"); //Geschwister löschen
	if($(this).hasClass("is_checked")==true){
		$(this).removeClass("is_checked");	
	}else{
		$(this).addClass("is_checked");
	}
	//show-all toggle
	if (!$(this).closest(".my_isotope_filters").find("button").hasClass("is_checked")) {
		$(this).closest(".my_isotope_filters").find("button").addClass("show_all");
	}else{
		$(this).closest(".my_isotope_filters").find("button").removeClass("show_all");
	}
});


/* =============================================================== *\ 
   Vergangene Touren
   - slideUp/SlideDown-Toggle
\* =============================================================== */ 
$("#content").on("click", ".container.info", function(){
	if($(this).closest(".grid_item").hasClass("is_open")){
		$(this).closest(".grid_item").removeClass("is_open");
		$(this).closest(".grid_item").find(".info_button").removeClass("is_active");;
		$(this).closest(".card_main").find(".row.details").slideUp({
			duration:100, 
			done:function(){
				$(".grid").isotope();	
			}
		});
	}else{
		$(".grid_item").not($(this)).removeClass("is_open").find(".row.details").slideUp({
			duration:100, 
			done: function(){
				$(".grid").isotope();	
			}}
		);
		$(this).closest(".grid_item").addClass("is_open");
		$(".grid_item").not($(this)).find(".info_button").removeClass("is_active");
		$(this).closest(".grid_item").find(".info_button").addClass("is_active");;
		$(this).closest(".card_main").find(".row.details").css("display", "flex");
		$(this).closest(".card_main").find(".row.details").slideUp({duration:0});
		$(this).closest(".card_main").find(".row.details").slideDown({
			duration:100,			
			done: function(){
				$(".grid").isotope();	
			}
		});
	};
})




/* =============================================================== *\ 
   Confetti for ThankYou 
   https://github.com/catdad/canvas-confetti
\* =============================================================== */ 
if ( $( "#confetti" ).length ) {
	var count = 400;
    var defaults = {
      	origin: { y: 0.5 }
    };

    function fire(particleRatio, opts) {
      	confetti(Object.assign({}, defaults, opts, {
        	particleCount: Math.floor(count * particleRatio)
      	}));
    }

    fire(0.51, {
    	origin: { x: -0.25, y:-0.25 }, //oben links
    	angle:-45,
    	spread: 60,
    	startVelocity:80,
    	colors:["#5852F2","#F2CB05","#F29F05","#F26B5E","#F20C0C"],
    	});
    	
    fire(0.51, {
    	origin: { x: 1.25, y:-0.25 }, // oben rechts
    	angle:225,
    	spread: 60,
    	startVelocity:80,
    	colors:["#5852F2","#F2CB05","#F29F05","#F26B5E","#F20C0C"],
    	});
    	
    fire(0.51, {
    	origin: { x: -0.25, y:1.25 }, // unten links
    	angle:45,
    	spread: 60,
    	startVelocity:80,
    	colors:["#5852F2","#F2CB05","#F29F05","#F26B5E","#F20C0C"],
    	});

    fire(0.51, {
    	origin: { x: 1.25, y:1.25 }, // unten rechts
    	angle:135,
    	spread: 60,
    	startVelocity:80,
    	colors:["#5852F2","#F2CB05","#F29F05","#F26B5E","#F20C0C"],
    	}); 
}

/* =============================================================== *\ 
 	 Slick-Slider default
\* =============================================================== */ 
$(".bilder_slider").not(".header_slider").slick({
	infinite: true,
	speed: 300,
	fade: true,
	cssEase: 'linear',
	autoplaySpeed: 2000,
});  
/* =============================================================== *\ 
 	 Slick-Slider Home 
     der erste Slider auf Home soll 
     - immer mit einem anderen Bild beginnen
     - etwas langsamer
\* =============================================================== */ 
var $erster_bilder_slider = $(".home .bilder_slider").first();
var $anz_bilder = $erster_bilder_slider.find("img").length;
var $rand = Math.floor( Math.random() * $anz_bilder); // random number
 $(".bilder_slider.header_slider").first().slick({
     infinite: true,
     speed: 1500,
     fade: true,
     cssEase: 'linear',
     autoplaySpeed: 3000,
     autoplay: false,
     initialSlide: $rand,
 });

/* =============================================================== *\ 

 	 Slick-Slider Tourenbericht 
     isotope nochmals anstossen, wenn bilder geladen
\* =============================================================== */   
$('.grid').imagesLoaded( function() {
      $(".grid").isotope();
});

/* =============================================================== *\ 

 	 Footer-Menu 
- wenn Klasse teaser, dann button machen
\* =============================================================== */ 
/*$("footer .menu-footer-menu-3-container .button a").wrap('<div class="chip_button_container flex centered"></div>');
$("footer .menu-footer-menu-3-container .button a").addClass("more_button animated_button footer_light_grey footer_go extreme_rounded centered section_button");
*/

$("footer .footer_menu_1 a").addClass("animated_button");

/* =============================================================== *\ 

 	 Flip-Chart

\* =============================================================== */ 
$(document).on("click", '.flip_chard_button', function(){
    
    // Datums-Karte flip
    $(this).closest(".form_section").find(".flip_card").toggleClass("front_side").toggleClass("back_side");

    // Verschiebe-Daten-Karten flip 
    
    
    // Text bei Button ersetzen
    if($(this).html() == 'Eintägige Tour'){ 
        $(this).html('Mehrtägige Tour');
    }else{ 
        $(this).html('Eintägige Tour');
    }
});

/* =============================================================== *\ 

 	 Touren eingabe 

\* =============================================================== */ 
  

$(".plus_verschiebe_datum").on("click", function(){
    
})
/*
}//readyState
}//onreadystatechange
*/
});//ready beenden

var slides = new Array();


//document.addEventListener("DOMContentLoaded", function(){
$(document).ready(function() {
  //var slides = new Array();
          var xmlString = document.querySelector('#deckslidesta').value;
          parser = new DOMParser();
          doc = parser.parseFromString(xmlString, "text/html");
          
          //console.log(doc.body.children);
          
          for (var i = 0; i < doc.body.children.length; i++) {
          
             slides.push(doc.body.children[i].innerHTML);
             createNumNode(i + 1);
            //console.log(doc.body.children[i].innerHTML);
          }
         
              //higligt first slide
              $('div[num="1"]').css('border', "thick solid #0000FF");
              
              
              $( ".context-menu-one" ).contextmenu(function(e) {
              e.preventDefault();
                console.log(e);
                $("#cntnr").css("left",e.pageX);
                $("#cntnr").css("top",e.pageY);
 // $("#cntnr").hide(100);        
                $("#cntnr").fadeIn(200,startFocusOut());      
              
              
                $("#items > li").click(function(){
                        console.log($(this).text());
                        console.log(e.currentTarget);
                        
                        if ($(this).text() == 'Delete') {
                        
                             e.currentTarget.remove();
                             console.log(e.currentTarget.getAttribute("num"));
                             //deleteSlide();
                        }
                        
                
                });
              
              
              
              
                //alert( "Handler for .contextmenu() called." );
                });
                
                
                
              
              

 //console.log(slides);
  //console.log('HERE');
//});


// $(function() {
        /*
        console.log('HERE WE GO');
        $.contextMenu({
            selector: '.context-menu-one', 
            callback: function(key, options) {
                var m = "clicked: " + key;
                window.console && console.log(m) || alert(m); 
            },
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "cut": {name: "Cut", icon: "cut"},
               copy: {name: "Copy", icon: "copy"},
                "paste": {name: "Paste", icon: "paste"},
                "delete": {name: "Delete", icon: "delete"},
                "sep1": "---------",
                "quit": {name: "Quit", icon: function(){
                    return 'context-menu-icon context-menu-icon-quit';
                }}
            }
        });

        $('.context-menu-one').on('click', function(e){
            console.log('clicked', this);
        })
       */  
});


    //context menu
    function startFocusOut(){
      $(document).on("click",function(){
      $("#cntnr").hide();        
      $(document).off("click");
      });
    }

   /////////////////////////////////////////////////////reveal control
    
	var slides_num = 1;
	var edit_slide = false;
	function loadSlide(slide) {
//		CKEDITOR.instances["editor_kama"].setData( slides[slide] );
        
        saveSlide();
		tinymce.activeEditor.setContent( slides[slide] );
		document.getElementById('slidenumber').innerHTML = slide+1;
        //save current changes to slide
		
		
		
		edit_slide = slide;
		
		
	}
	function saveNext() {
		if (!edit_slide) {
			slides.push(tinyMCE.get('question_text').getContent());
			createNumNode(slides_num);
			slides_num++;
		} else {
			slides[edit_slide] = tinyMCE.get('question_text').getContent();
		}
		//CKEDITOR.instances["editor_kama"].setData( '' );
		
		
		var newslidetmpl = '<div class="mceTmpl">' +
                           '<h1 style="text-align: center;"><span style="font-size: 36pt;">TITLE</span></h1>' +
                           '<ul>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 1</span></li>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 2</span></li>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 3</span></li>' +
                           '</ul>' +
                           '</div>';
		
		
		
        tinymce.activeEditor.setContent( newslidetmpl );
        //get('question_text')
		document.getElementById('slidenumber').innerHTML = slides_num;
		edit_slide = false;
	}
	
	function saveSlide() {
	
	    console.log('edit_slide=' + edit_slide);
		if (edit_slide == 'false') {
			console.log('here slide not set');
		} else {
		    
			slides[edit_slide] = tinyMCE.get('question_text').getContent();
			console.log(tinyMCE.get('question_text').getContent());
			console.log($("[num="+(edit_slide+1)+"]"));
			$("[num="+(edit_slide+1)+"]").html(tinyMCE.get('question_text').getContent());
		}
		//CKEDITOR.instances["editor_kama"].setData( '' );		
		
	}
	
	
    function createNumNode(slides_num) {
		var oldslide = document.createElement('div');
		
		//oldslide.appendChild(document.createTextNode("Click to load Slide: "+slides_num));
		
		//oldslide.appendChild(slides[slides_num]);
        oldslide.innerHTML = slides[slides_num-1];

		oldslide.style.cursor = 'pointer';
		oldslide.setAttribute("num",slides_num);
		oldslide.setAttribute("class", "context-menu-one");
		oldslide.onclick = function() {
			loadSlide(parseInt(this.getAttribute("num"))-1);
			 $(this).children("*").css('opacity', 1);
			$('#slides_edit').children("*").css('border', "dashed");
			this.style.border = "thick solid #0000FF";
		};
		
		$maxWidth = $("#slides_edit").width();
		var originy = (slides_num - 1) * -98;
		var fScaleAmount = 0.3;
		$(oldslide).css({
		"height" : "400px",
		"width" : "500px",
		"border-style" : "dashed",
		"display" : "flex",
        "-moz-transform": "scale(" + fScaleAmount + ")",
        "-moz-transform-origin": "0% " + originy + "%",
        "-webkit-transform": "scale(" + fScaleAmount + ")",
        "-webkit-transform-origin": "0% " + originy + "%",
        "-ms-transform": "scale(" + fScaleAmount + ")",
        "-ms-transform-origin": "0% " + originy + "%",

       });
				
		document.querySelector('#slides_edit').appendChild(oldslide);
	}
	
	/*
	function createNumNode(slides_num) {
		var oldslide = document.createElement('div');
		oldslide.appendChild(document.createTextNode("Click to load Slide: "+slides_num));
		oldslide.style.cursor = 'pointer';
		oldslide.setAttribute("num",slides_num);
		oldslide.onclick = function() {
			loadSlide(parseInt(this.getAttribute("num"))-1);
		};
		
		document.querySelector('#slides_edit').appendChild(oldslide);
	}
	*/
	function saveGenerate() {
		var main_div = document.querySelector('#deckslides');
		//var y = document.createElement('textarea');
		var y = document.querySelector('#deckslidesta');
		//y.id = 'mytextarea';
		var d = document.createElement('div');
		var m_title = document.querySelector('#deck_title').value;
		var m_theme = document.querySelector('#themename').value;
		var m_trans = document.querySelector('#transition').value;
		d.appendChild(document.createTextNode("This is your generated code! Copy and paste it into a html page"));
		var sections = ''
		//y.value = 'just testing this';
		//var completeCode = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>'+m_title+'</title><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /><link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/reset.css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/main.css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/print.css" type="text/css" media="print"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/lib/css/zenburn.css"></head><body><div class="reveal"><div class="state-background"></div><div class="slides">';
		for (i in slides) {
		    console.log(i);
			var section = '<section>';
			section += slides[i];
			section += '</section>';
			//slides[i]
			sections += section;
		}
		//completeCode += '</div><style type="text/css"> #kodyright { position: fixed; bottom: 10px; left: 10px; font-size: 12px; } </style><div id="kodyright">generated with <a target="_blank" href="http://kodkod.org">kodkod auto RevealJS generator</a></div><aside class="controls"><a class="left" href="#">&#x25C4;</a><a class="right" href="#">&#x25BA;</a><a class="up" href="#">&#x25B2;</a><a class="down" href="#">&#x25BC;</a></aside><div class="progress"><span></span></div></div><script src="http://lab.hakim.se/reveal-js/lib/js/head.min.js"><\/script>';
		//completeCode += '<script> head.js( !document.body.classList ? "lib/js/classList.js" : null ).js( "http://lab.hakim.se/reveal-js/js/reveal.js", function() {  var query = {}; location.search.replace( /[A-Z0-9]+?=(\w*)/gi, function(a) { query[ a.split( "=" ).shift() ] = a.split( "=" ).pop(); } ); Reveal.initialize({ controls: true, progress: true, history: true, theme: query.theme || "'+m_theme+'", transition: query.transition || "'+m_trans+'" }); } ); head.js( "http://lab.hakim.se/reveal-js/lib/js/highlight.js", function() { hljs.initHighlightingOnLoad(); } ); <\/script></body></html>';
		y.value = sections;
		console.log(sections);
		main_div.innerHTML = '';
		main_div.appendChild(d);
		main_div.appendChild(y);
		//var main_ta = document.querySelector('#deckslidesta');
		//main_ta.value = sections;
		
	}
	
	
	
	
    
    
    
    
    
    
    
    //reveal controls

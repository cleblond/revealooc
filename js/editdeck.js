var slides = new Array();

var slides_num = 1;

var edit_slide = 1;

var currenttarget = '';
var currentindex = '';

var newslidetmpl = '<div class="mceTmpl">' +
                           '<h1 style="text-align: center;"><span style="font-size: 36pt;">BLANK SLIDE</span></h1>' +
                           '<ul>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 1</span></li>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 2</span></li>' +
                           '<li style="text-align: left;"><span style="font-size: 18pt;">Point 3</span></li>' +
                           '</ul>' +
                           '</div>';



//document.addEventListener("DOMContentLoaded", function(){
$(document).ready(function() {
  //var slides = new Array();
  
  

  
  
  
  
  
   $( ".slides_edit" ).sortable(
        {
            start: function(e, ui)
            {
               console.log('Helloe fork here');
               console.log($(this).data());
               
               var items = $(this).data()['uiSortable'].items;
                items.forEach(function(item) {
                    item.height *= 0.3;
                    item.width *= 0.3;
                });
            },
               
               
               
               
            
            sort: function(e, ui)
            {
            
              //console.log(ui.position.top)
                //var changeLeft = ui.position.left - ui.originalPosition.left;
      // For left position, the problem here is not only the scaling,
      // but the transform origin. Since the position is dynamic
      // the left coordinate you get from ui.position is not the one
      // used by transform origin. You need to adjust so that
      // it stays "0", this way the transform will replace it properly
              //var newLeft = ui.originalPosition.left + changeLeft / zoomScale - ui.item.parent().offset().left;

      // For top, it's simpler. Since origin is top, 
      // no need to adjust the offset. Simply undo the correction
      // on the position that transform is doing so that
      // it stays with the mouse position
               var newTop = ui.position.top / 0.3 - 600;
               ui.helper.css({
                top: newTop
                });
            
                       
            }
        });
    //$( "#sortable" ).disableSelection();
  
  
  
  
              parseSlidesTA();
         
              //higligt first slide
              $('div[num="1"]').css('border', "thick solid #0000FF");
              
              
              
              
              //context menue stuff
              $(document).on('contextmenu', '.context-menu-one', function (e) {
              //$( ".context-menu-one" ).contextmenu(function(e) {
              
                  console.log(this);
                  
                    e.preventDefault();
                    console.log(e);
                    $("#cntnr").css("left",e.pageX);
                    $("#cntnr").css("top",e.pageY);
     // $("#cntnr").hide(100);        
                    $("#cntnr").fadeIn(200,startFocusOut());      
                  
                  
                   currenttarget =  e.currentTarget;
                   currentindex = $(e.currentTarget).index();
                  
                    
                //return false;
                  
                    //alert( "Handler for .contextmenu() called." );
            });
            
            
             $("#items > li").click(function(){
                        console.log($(this).text());
                        console.log(currenttarget);
                        console.log("curindex", currentindex);
                        console.log("edit_slide", edit_slide);
                        
                        //delete a slide
                        
                        if ($(this).text() == 'Delete') {                             

                             currenttarget.remove();
                             
                             //console.log('slides after delete', slides);
                        }
                        
                        if ($(this).text() == '+ Before') {
                        
                        
                        
                        
                                if (currentindex + 1 <= edit_slide) {
                                    edit_slide = edit_slide + 1;
                                }
                        
                                console.log("edit_slide", edit_slide);
                                var oldslide = document.createElement('div');
		
                                oldslide.innerHTML = newslidetmpl;

		                        oldslide.style.cursor = 'pointer';
		                        oldslide.setAttribute("num",slides_num);
		                        oldslide.setAttribute("class", "context-menu-one item ui-sortable-handle");
                                $(oldslide).css({
		                            "height" : "400px",
		                            "width" : "500px",
		                            "border-style" : "dashed",
		                            "display" : "inline-block",
                                   });
                                                    
                                
                                
                                currenttarget.before(oldslide);
                                console.log('add slide before');
                        
                        } 
                        
                        if ($(this).text() == '+ After') {
                        
                        
                                if (currentindex + 1 < edit_slide) {
                                    edit_slide = edit_slide + 1;
                                }
                        
                        
                                var oldslide = document.createElement('div');
		
                                oldslide.innerHTML = newslidetmpl;

		                        oldslide.style.cursor = 'pointer';
		                        oldslide.setAttribute("num",slides_num);
		                        oldslide.setAttribute("class", "context-menu-one item ui-sortable-handle");
                                $(oldslide).css({
		                            "height" : "400px",
		                            "width" : "500px",
		                            "border-style" : "dashed",
		                            "display" : "inline-block",
                                   });
                                                    
                                
                                
                                currenttarget.after(oldslide);
                                console.log('add slide before');
                        
                        } 
                        
                        if ($(this).text() == 'Clone') {
                                //console.log(e.currentTarget);            
                                if (currentindex + 1 < edit_slide) {
                                    edit_slide = edit_slide + 1;
                                }
                                
                                //newcurrenttarget = currenttarget;           
                                //currenttarget.after(newcurrenttarget);
                                var copy = currenttarget.cloneNode(true);
                                
                                $(copy).css({
		                            "height" : "400px",
		                            "width" : "500px",
		                            "border-style" : "dashed",
		                            "display" : "inline-block",
                                   });
                                
                                currenttarget.after(copy);
                                
                                
                                //console.log('clone');
                        
                        } 
                     
                
                });
            

                   $('#slides_edit').on('dblclick', ".ui-sortable-handle", function(e){
                   
                        
                    
                        $( ".ui-sortable-handle" ).css('border', "dashed"); 
                    
                        //save current slide
                        console.log($(".ui-sortable-handle:eq("+(edit_slide-1)+")"));
                        
                        
                        //var elements = $(tinyMCE.get('question_text').getContent());

                        console.log(tinyMCE.get('question_text').getContent());
                        //disable script tags
                        var newtext = tinyMCE.get('question_text').getContent();
                        //elements.find("script").attr("type","application/json");
                        
                        var finaltext = newtext.replace(/type=\"text\/javascript\"/g, 'type="application/json"');
                        
                        //$('script').elements.attr('type', 'application/json');
                        //var  = elements.html();
                        
                        console.log(finaltext);
                        $(".ui-sortable-handle:eq("+(edit_slide-1)+")").html(finaltext);
                        //console.log($("#slides_edit:nth-child(3)"))
                        //console.log($('.ui-sortable-handle').index(this));
                        
                        
                        //console.log(e);
                        //console.log(e.currentTarget);
                        //loadSlide(e.currentTarget.innerHTML)
                        
                        //update current
                        $(e.currentTarget).css('border', "thick solid #0000FF");
                        
                        
                        tinymce.activeEditor.setContent( e.currentTarget.innerHTML );
                        edit_slide = $('.ui-sortable-handle').index(this) + 1; 
                        
                        document.getElementById('slidenumber').innerHTML = edit_slide;
  
                    });
});









    function parseSlidesTA() {
              var xmlString = document.querySelector('#deckslidesta').value;
              parser = new DOMParser();
              doc = parser.parseFromString(xmlString, "text/html");
              
              //console.log(doc.body.children);
              
              for (var i = 0; i < doc.body.children.length; i++) {
              
                 slides.push(doc.body.children[i].innerHTML);
                 createNumNode(i + 1);
                //console.log(doc.body.children[i].innerHTML);
              }
              
              console.log('Slides read in', slides);
    }
    
    
   
          


    //context menu
    function startFocusOut(){
      $(document).on("click",function(){
      $("#cntnr").hide();        
      $(document).off("click");
      });
    }

   /////////////////////////////////////////////////////reveal control
    

	
	
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
			//console.log(tinyMCE.get('question_text').getContent());
			//console.log($("[num="+(edit_slide+1)+"]"));
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
		oldslide.setAttribute("class", "context-menu-one item ui-sortable-handle");
		/*
		oldslide.onclick = function() {
			loadSlide(parseInt(this.getAttribute("num"))-1);
			 $(this).children("*").css('opacity', 1);
			$('#slides_edit').children("*").css('border', "dashed");
			this.style.border = "thick solid #0000FF";
		};
		*/
		//$maxWidth = $("#slides_edit").width();
		var originy = (slides_num - 1) * -98;
		
		var fScaleAmount = 0.3;
		
		
		$(oldslide).css({
		"height" : "400px",
		"width" : "500px",
		"border-style" : "dashed",
		"display" : "inline-block",
        //"-moz-transform": "scale(" + fScaleAmount + ")",
        //"-moz-transform-origin": "0% " + originy + "%",
        //"-webkit-transform": "scale(" + fScaleAmount + ")",
        //"-webkit-transform-origin": "0% " + originy + "%",
        //"-ms-transform": "scale(" + fScaleAmount + ")",
        //"-ms-transform-origin": "0% " + originy + "%",
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
	
	
	$('#slideEditForm').submit(function() {
    
    
    
        var sections = ''
	    $( "#slides_edit" ).children('div').each(function( index ) {
	    
            console.log("here");
            console.log( index + ": " + $( this ).html() );
            var section = '<section>';
            
            var newtext = $( this ).html();            
            var finaltext = newtext.replace(/type=\"application\/json\"/g, 'type="text/javascript"');
            
            
			section += finaltext;
			section += '</section>';
			//slides[i]
			sections += section;
        
        });
        
    
        var y = document.querySelector('#deckslidesta');
		//y.id = 'mytextarea';
		//var d = document.createElement('div');
		var m_title = document.querySelector('#deck_title').value;
		var m_theme = document.querySelector('#themename').value;
		var m_trans = document.querySelector('#transition').value;
		//d.appendChild(document.createTextNode("This is your generated code! Copy and paste it into a html page"));
		//var sections = '';

		y.value = sections;
		console.log("saved sections",sections);
    
  
    
    return true; // return false to cancel form action
    });
	
	
	
	
	
	/*
	
	function saveGenerate() {
	
	    var sections = ''
	    $( "#slides_edit" ).children('div').each(function( index ) {
	    
            console.log("here");
            console.log( index + ": " + $( this ).html() );
            var section = '<section>';
            var newtext = $( this ).html();            
            var finaltext = newtext.replace(/type=\"application\/json\"/g, 'type="text/javascript"');

            section += finaltext; 

            //var finaltext = elements.html();
			//section += $( this ).html();
			section += '</section>';
			//slides[i]
			sections += section;
        
        });
	
	
	
	
		//var main_div = document.querySelector('#deckslides');
		//var y = document.createElement('textarea');
		var y = document.querySelector('#deckslidesta');
		//y.id = 'mytextarea';
		//var d = document.createElement('div');
		var m_title = document.querySelector('#deck_title').value;
		var m_theme = document.querySelector('#themename').value;
		var m_trans = document.querySelector('#transition').value;
		//d.appendChild(document.createTextNode("This is your generated code! Copy and paste it into a html page"));
		//var sections = ''
		//y.value = 'just testing this';
		//var completeCode = '<!doctype html><html lang="en"><head><meta charset="utf-8"><title>'+m_title+'</title><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /><link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/reset.css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/main.css"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/css/print.css" type="text/css" media="print"><link rel="stylesheet" href="http://lab.hakim.se/reveal-js/lib/css/zenburn.css"></head><body><div class="reveal"><div class="state-background"></div><div class="slides">';
		
		//console.log('SGslides saved', slides);
		//for (i in slides) {
		    //console.log(i);
		//	var section = '<section>';
		//	section += slides[i];
		//	section += '</section>';
			//slides[i]
		//	sections += section;
		//}
		//completeCode += '</div><style type="text/css"> #kodyright { position: fixed; bottom: 10px; left: 10px; font-size: 12px; } </style><div id="kodyright">generated with <a target="_blank" href="http://kodkod.org">kodkod auto RevealJS generator</a></div><aside class="controls"><a class="left" href="#">&#x25C4;</a><a class="right" href="#">&#x25BA;</a><a class="up" href="#">&#x25B2;</a><a class="down" href="#">&#x25BC;</a></aside><div class="progress"><span></span></div></div><script src="http://lab.hakim.se/reveal-js/lib/js/head.min.js"><\/script>';
		//completeCode += '<script> head.js( !document.body.classList ? "lib/js/classList.js" : null ).js( "http://lab.hakim.se/reveal-js/js/reveal.js", function() {  var query = {}; location.search.replace( /[A-Z0-9]+?=(\w*)/gi, function(a) { query[ a.split( "=" ).shift() ] = a.split( "=" ).pop(); } ); Reveal.initialize({ controls: true, progress: true, history: true, theme: query.theme || "'+m_theme+'", transition: query.transition || "'+m_trans+'" }); } ); head.js( "http://lab.hakim.se/reveal-js/lib/js/highlight.js", function() { hljs.initHighlightingOnLoad(); } ); <\/script></body></html>';
		y.value = sections;
		console.log("SGsaved sections",sections);
		//main_div.innerHTML = '';
		//main_div.appendChild(d);
		//main_div.appendChild(y);
		//var main_ta = document.querySelector('#deckslidesta');
		//main_ta.value = sections;
		
	}
	
	
	*/
	
    
    
    
    
    
    
    
    //reveal controls

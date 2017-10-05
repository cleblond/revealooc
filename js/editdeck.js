   /////////////////////////////////////////////////////reveal control
    var slides = new Array();
	var slides_num = 1;
	var edit_slide = false;
	function loadSlide(slide) {
//		CKEDITOR.instances["editor_kama"].setData( slides[slide] );
		tinymce.activeEditor.setContent( slides[slide] );
		document.getElementById('slidenumber').innerHTML = slide+1;
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
        tinymce.activeEditor.setContent( '' );
        //get('question_text')
		document.getElementById('slidenumber').innerHTML = slides_num;
		edit_slide = false;
		
		
	}
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
		main_div.innerHTML = '';
		main_div.appendChild(d);
		main_div.appendChild(y);
		//var main_ta = document.querySelector('#deckslidesta');
		//main_ta.value = main_div.innerHTML;
		
	}
    
    
    
    
    
    
    
    //reveal controls

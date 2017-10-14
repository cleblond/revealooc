<?php
require_once "config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;


if (isset($_GET['slidedeck_id'])) {

$id = intval($_GET['slidedeck_id']);
} else {

die;
}


$sql = "SELECT * FROM {$p}eo_slidedecks WHERE id = $id";
$sliderow = $PDOX->rowDie($sql);


$slides = json_decode($sliderow['slides']);

$sections = '';
$section = ''; 
    foreach ($slides  as $slide) {            
        $slide = str_replace("text\/javascript","application\/json", $slide);
        $section .= "<section>" . $slide . "</section>";
                    
                
        //$slidestext = $slidetext . ;
        $sections .= $section;
        $section = '';
    }



$options = json_decode($sliderow['options']);
$OUTPUT->header();
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<title><? echo $sliderow['description']; ?></title>

		<link rel="stylesheet" href="css/reveal.css">
		<link rel="stylesheet" href="css/theme/<?php echo $options->theme ?>.css">
	    <link rel="stylesheet" href="css/custom.css">

		<!-- Theme used for syntax highlighting of code -->
		<link rel="stylesheet" href="lib/css/zenburn.css">

		<!-- Printing and PDF exports -->
		<script>
			var link = document.createElement( 'link' );
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = window.location.search.match( /print-pdf/gi ) ? 'css/print/pdf.css' : 'css/print/paper.css';
			document.getElementsByTagName( 'head' )[0].appendChild( link );
		</script>
	</head>
	<body>
	    <input type="hidden" id="ajaxgetquestionurl" value = "<?php echo addSession('ajax/getquestionreveal.php'); ?>"/>
	    <input type="hidden" id="ajaxcheckcorrect" value = "<?php echo addSession('ajax/checkcorrectreveal.php'); ?>"/>
		<div class="reveal">
			<div class="slides">
<?= $sections ?>
			</div>
		</div>

		<script src="lib/js/head.min.js"></script>
		<script src="js/reveal.js"></script>
				                    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
		                    <link rel="stylesheet" href="chemdoodle/ChemDoodleWeb.css" type="text/css">
                    <link rel="stylesheet" href="chemdoodle/uis/jquery-ui-1.11.4.css" type="text/css">
		            <script type="text/javascript" src="chemdoodle/ChemDoodleWeb.js"></script>
                    <script type="text/javascript" src="chemdoodle/uis/ChemDoodleWeb-uis.js"></script>
		
		<!-- expected question_ph_div
		<div style="height: 400px;" data-qid="51">Question Block</div>
		
		-->
		
		
		
		
		
		
		

		<script>
			// More info about config & dependencies:
			// - https://github.com/hakimel/reveal.js#configuration
			// - https://github.com/hakimel/reveal.js#dependencies
			Reveal.initialize({
			    transition: "<?php echo $options->transition ?>",
			    embedded: false,
			    minScale: 1,
	            maxScale: 1,
				dependencies: [
					{ src: 'plugin/markdown/marked.js' },
					{ src: 'plugin/markdown/markdown.js' },
					{ src: 'plugin/notes/notes.js', async: true },
					{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } }
				]
			});
		</script>
		
		<script type="text/javascript">
		
		   var canobj = {};
		
		   Reveal.addEventListener( "mousedown", function( event ) {
		   
		        if ( $( event.target ).is( ":button.revealtogcorrect" ) ) {
		   
		                 if($(event.target).html() == "Show Correct") {
		                    $(event.target).html('Show My Response');
		                 } else {
		                    $(event.target).html('Show Correct');
		                 }
		                 
		                 
		                 
		                if (event.target.dataset.qtype == 'structure') {
                          
                            var mol = canobj['can'+event.target.id.substring(3)].getMolecule();
                            var usrresmolfile = ChemDoodle.writeMOL(mol);

                            
                            structure = ChemDoodle.readMOL($('#ta'+ event.target.id.substring(3)).val());
                            
                            canobj['can'+event.target.id.substring(3)].loadMolecule(structure);
                            $('#ta'+ event.target.id.substring(3)).val(usrresmolfile);
                            
                        }
                        
                        if (event.target.dataset.qtype == 'mechanism') {
                            
                            var usrresjson = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(canobj['can'+event.target.id.substring(3)].molecules, canobj['can'+event.target.id.substring(3)].shapes));
                            
                            
                            var structure = ChemDoodle.readJSON($('#ta'+ event.target.id.substring(3)).val());
                            canobj['can'+ event.target.id.substring(3)].loadContent(structure.molecules, structure.shapes);
                                
                            $('#ta'+ event.target.id.substring(3)).val(usrresjson);
                            
                        }
                        
                        if (event.target.dataset.qtype == 'numeric' || event.target.dataset.qtype == 'alphanumeric') {
                        
                           temp = $('#'+event.target.id.substring(3)).val();
                           
                           $('#'+ event.target.id.substring(3)).val( $('#ta'+ event.target.id.substring(3)).val() );
                           $('#ta'+ event.target.id.substring(3)).val(temp);

                        
                        }
                        
                        
                         if (event.target.dataset.qtype == 'mcq') {
                        
                           //temp = $('#'+event.target.id.substring(3)).val();
                           
                           //read current mcqs
                            var prefix = '';
                            var usrres = '';
                            $('input[type="checkbox"]:checked').each(function() {
                               usrres += prefix + this.value;
                               prefix = ',';     
                               //console.log(this.value);
                            });
                           
                           //show mcq from textarea
                           tamcq = $('#ta'+ event.target.id.substring(3)).val().split(",");
                           
                           //for (var i = 0; i < tamcq.length; i++) {
                           $('input[type="checkbox"]').each(function() {
                           
                                if ($.inArray(this.value, tamcq) == -1) {
                                $('input[value='+this.value+']').prop('checked', false);
                                } else {
                                $('input[value='+this.value+']').prop('checked', true);
                                }
                                //console.log($scope.answerEntries[i].answer_id);
                                //console.log($scope.answerEntries[i].correct);
                                //if ($scope.answerEntries[i].correct == '1') {
                                //console.log('Why Not'+$scope.answerEntries[i].answer_id);
                                
                                //} else {
                                //$('input[value='+tamcq[i]+']').prop('checked', false);
                                //}
                           });
                           
                           $('#ta'+ event.target.id.substring(3)).val(usrres);
                           
                          

                        
                        }
		   
		   
		   
		   
		   
		   
		   
		        }
		   
		   
                
                
                if ( $( event.target ).is( ":button.revealsubmit" ) ) {

                  console.log(event.target.id);
                  
                  
                  
                  console.log(event.target.dataset.qid);
                  console.log(event.target.dataset.qtype);
                  
                  
                  //get responses from page
                        if (event.target.dataset.qtype == 'mcq') {
                            //get csv of answers
                            var prefix = '';
                            var usrres = '';
                            $('input[type="checkbox"]:checked').each(function() {
                               usrres += prefix + this.value;
                               prefix = ',';     
                               //console.log(this.value);
                            });
                            //console.log(response);
                        }
                        
                        /*
                        if (event.target.dataset.qtype == 'slide') {
                            //get csv of answers
                            var response = '';
                        }
                        */
                        
                        
                        
                        if (event.target.dataset.qtype == 'numeric' || event.target.dataset.qtype == 'alphanumeric') {
                            //get csv of answers
                            //var prefix = '';
                            var usrres = $('#'+event.target.id.substring(3)).val();
                            console.log(event);
                            console.log(usrres);

                        }
                  
                        if (event.target.dataset.qtype == 'structure') {

                            //console.log(canobj['can'+event.target.id.substring(3)]);
                          
                            var mol = canobj['can'+event.target.id.substring(3)].getMolecule();
                            var usrres = ChemDoodle.writeMOL(mol);

                        }
                        
                        
                        if (event.target.dataset.qtype == 'mechanism') {
                        //get csv of answers
                            //canobj = event.target.id.substring(3);
                            usrres = JSON.stringify(new ChemDoodle.io.JSONInterpreter().contentTo(canobj['can'+event.target.id.substring(3)].molecules, canobj['can'+event.target.id.substring(3)].shapes));
                       
                        }
                  

                     //compare response with correct answers
                                      
                     var data = {qid: event.target.dataset.qid, qtype: event.target.dataset.qtype, usrresponse: usrres, btnid: event.target.id };
                     var ajaxcorurl = $('#ajaxcheckcorrect').val();
                     //console.log(ajaxurl);
                    
                     $.ajax({
                        type: 'POST',
                        url: ajaxcorurl,
                        data: data,
                        success: function(response) {
                           //console.log(response);
                           response = JSON.parse(response);
                           
                           //add correct/my response toggel button to dom
                           
                           if (response.correct == 0) {
                           
                                 if (response.feedback == '') {response.feedback = "Sorry that's incorrect"};
                           
                                 feedback = "<div class='feedback_neg'>" + response.feedback + "</div>";
                           } else {
                                 if (response.feedback == '') {response.feedback = "Correct!"};
                                 feedback = "<div class='feedback_pos'>" + response.feedback + "</div>";
                           }
                           
                           
                            
                           
                           
                           if (data.qtype == 'structure') {
                           
                           
                           html = "<button data-qtype="+data.qtype+" class='revealtogcorrect' id='tog"+data.btnid.substring(3)+"' >Show Correct</button>";
                           html += "<textarea style='display:none' id='ta"+data.btnid.substring(3)+"'>"+response.correctmolfile+"</textarea>";
                           html += feedback;
                           
                           $('#'+data.btnid).replaceWith(html);
                           
                           
                           }
                           
                           
                           if (data.qtype == 'mechanism') {
                           
                           
                           html = "<button data-qtype="+data.qtype+" class='revealtogcorrect' id='tog"+data.btnid.substring(3)+"' >Show Correct</button>";
                           html += "<textarea style='display:none' id='ta"+data.btnid.substring(3)+"'>"+response.correctjson+"</textarea>";
                           html += feedback;
                           $('#'+data.btnid).replaceWith(html);
                           
                           
                           }
                           
                           if (data.qtype == 'numeric' || data.qtype == 'alphanumeric') {
                           
                           
                           html = "<button data-qtype="+data.qtype+" class='revealtogcorrect' id='tog"+data.btnid.substring(3)+"' >Show Correct</button>";
                           html += "<textarea style='display:none' id='ta"+data.btnid.substring(3)+"'>"+response.correctother+"</textarea>";
                           html += feedback;
                           $('#'+data.btnid).replaceWith(html);
                           
                           
                           }
                           
                           if (data.qtype == 'mcq') {
                           
                           
                           html = "<button data-qtype="+data.qtype+" class='revealtogcorrect' id='tog"+data.btnid.substring(3)+"' >Show Correct</button>";
                           html += "<textarea style='display:none' id='ta"+data.btnid.substring(3)+"'>"+response.correctother+"</textarea>";
                           html += feedback;
                           $('#'+data.btnid).replaceWith(html);
                           
                           
                           }
              
                        }
                     
                     
                     });
                  
                 

                }


           } );
           
           
           Reveal.addEventListener( 'ready', function( event ) {
	            //setupQuestionHtml();
	            setupQuestionsHtmlAll();
           } );
           
           Reveal.addEventListener( 'slidechanged', function( event ) {
             
           //setupQuestionHtml();
                
           });
            
            
            function setupQuestionsHtmlAll() {
            
              
              $('.question_ph_div').each(function() {
              
                            //var qdiv = event.currentSlide.querySelector('.question_ph_div');
                            //var currentslide = event.currentSlide;
                            //console.log(qdiv.dataset.qid);
                            //console.log(qdiv.dataset.aid);
                            
                            //need to replace with .question_div
                            
                            //$(this).attr("data-qid");
                            
                            
                            //if($(qdiv).length !== 0) {

                                var data = {qid: $(this).attr("data-qid")};
                                var ajaxurl = $('#ajaxgetquestionurl').val();
                                
                                var curqdiv = this;
                                
                                //console.log('curqdiv', curqdiv);
                                
                                 $.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: data,
                                    success: function(response) {
                                       //console.log(response);
                                       response = JSON.parse(response);
                                         
                                       if (response.question_type == 'structure') {
                                       
                                            //$('.question_ph_div').append(response.question_html);
                                            
                                            $(curqdiv).replaceWith(response.question_html)
                                            //$(qdiv).replaceWith(response.question_html)
                                            
                                            canobj['can'+ response.unique_id] = new ChemDoodle.SketcherCanvas(response.unique_id, 500, 300, {useServices:false, oneMolecule:false});
                                            canobj['can'+ response.unique_id].emptyMessage = 'No Data Loaded!';
                                            //molfile = "molfile as text";
                                            //var caffeine = ChemDoodle.readMOL(response);
                                            //myCanvas.loadMolecule(caffeine);
                                            canobj['can'+ response.unique_id].repaint();
                                             
                                       }
                                                   
                                       if (response.question_type == 'mechanism') {
                                       
                                            //$('.question_ph_div').append(response.question_html);
                                            $(curqdiv).replaceWith(response.question_html)
                                            canobj['can'+ response.unique_id] = new ChemDoodle.SketcherCanvas(response.unique_id, 500, 300, {useServices:false, oneMolecule:false});
                                            canobj['can'+ response.unique_id].emptyMessage = 'No Data Loaded!';
                                            moljson = response.strippedanswerjson;
                                            
                                            //console.log(moljson);
                                            
                                            var structure = ChemDoodle.readJSON(moljson);
                                            canobj['can'+ response.unique_id].loadContent(structure.molecules, structure.shapes);
                                            
                                            //var caffeine = ChemDoodle.readMOL(response);
                                            //myCanvas.loadMolecule(caffeine);
                                            canobj['can'+ response.unique_id].repaint();
                                             
                                      }
                                       
                                      if (response.question_type == 'mcq') {
                                       
                                            //$('.question_ph_div').append(response.question_html);
                                            $(curqdiv).replaceWith(response.question_html)
                                         
                                             
                                      }
                                      
                                      if (response.question_type == 'numeric'  || response.question_type == 'alphanumeric' ) {
                                       
                                            //$('.question_ph_div').append(response.question_html);
                                            $(curqdiv).replaceWith(response.question_html)
                                         
                                             
                                      }
                                      
                                      
                                      //console.log(event.currentSlide);
                                      
                                      //var btn = currentslide.querySelector("#btn" + response.unique_id);
                                      //console.log(response.unique_id);
                                       //$("#btn" + response.unique_id).on("click", function () {
                                       // console.log ('Button clicked');
                                      //});
                                      
                          
                          
                          
      
                             
                        }
                     });  //end ajax
                                  
                //}  //eif qdiv
              });
            
            
            
            
            }
            
            
            /*
            function setupQuestionHtml() {
                //console.log("cur slide", event.currentSlide);
                var qdiv = event.currentSlide.querySelector('.question_ph_div');
                var currentslide = event.currentSlide;
                //console.log(qdiv.dataset.qid);
                //console.log(qdiv.dataset.aid);
                
                //need to replace with .question_div
                
                if($(qdiv).length !== 0) {

                    var data = {qid: qdiv.dataset.qid};
                    var ajaxurl = $('#ajaxgetquestionurl').val();
                    console.log(ajaxurl);
                    
                     $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: data,
                        success: function(response) {
                           //console.log(response);
                           response = JSON.parse(response);
                             
                           if (response.question_type == 'structure') {
                           
                                //$('.question_ph_div').append(response.question_html);
                                
                                $(qdiv).replaceWith(response.question_html)
                                //$(qdiv).replaceWith(response.question_html)
                                
                                canobj['can'+ response.unique_id] = new ChemDoodle.SketcherCanvas(response.unique_id, 500, 300, {useServices:false, oneMolecule:false});
                                canobj['can'+ response.unique_id].emptyMessage = 'No Data Loaded!';
                                //molfile = "molfile as text";
                                //var caffeine = ChemDoodle.readMOL(response);
                                //myCanvas.loadMolecule(caffeine);
                                canobj['can'+ response.unique_id].repaint();
                                 
                           }
                                       
                           if (response.question_type == 'mechanism') {
                           
                                //$('.question_ph_div').append(response.question_html);
                                $(qdiv).replaceWith(response.question_html)
                                canobj['can'+ response.unique_id] = new ChemDoodle.SketcherCanvas(response.unique_id, 500, 300, {useServices:false, oneMolecule:false});
                                canobj['can'+ response.unique_id].emptyMessage = 'No Data Loaded!';
                                moljson = response.strippedanswerjson;
                                
                                console.log(moljson);
                                
                                var structure = ChemDoodle.readJSON(moljson);
                                canobj['can'+ response.unique_id].loadContent(structure.molecules, structure.shapes);
                                
                                //var caffeine = ChemDoodle.readMOL(response);
                                //myCanvas.loadMolecule(caffeine);
                                canobj['can'+ response.unique_id].repaint();
                                 
                          }
                           
                          if (response.question_type == 'mcq') {
                           
                                //$('.question_ph_div').append(response.question_html);
                                $(qdiv).replaceWith(response.question_html)
                             
                                 
                          }
                          
                          if (response.question_type == 'numeric'  || response.question_type == 'alphanumeric' ) {
                           
                                //$('.question_ph_div').append(response.question_html);
                                $(qdiv).replaceWith(response.question_html)
                             
                                 
                          }
                          
                          
                          console.log(event.currentSlide);
                          
                          //var btn = currentslide.querySelector("#btn" + response.unique_id);
                          console.log(response.unique_id);
                           $("#btn" + response.unique_id).on("click", function () {
                            console.log ('Button clicked');
                          });
                          
                          
                          
                          
      
                             
                        }
                     });
                                  
                }
                
                 //$('#slides_edit').on('dblclick', ".ui-sortable-handle", function(e){

                
             }  //end function   
           
           */
	
        </script>
	</body>
</html>


<?php




$OUTPUT->footerStart();




$OUTPUT->footerEnd();



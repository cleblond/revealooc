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
		
		   Reveal.addEventListener( 'ready', function( event ) {
	                // event.currentSlide, event.indexh, event.indexv
	                console.log("cur slide", event.currentSlide); 
	                
           } );
           
           
           Reveal.addEventListener( 'slidechanged', function( event ) {
           
                console.log("cur slide", event.currentSlide);
                var qdiv = event.currentSlide.querySelector('.questiondiv');
                
                //console.log(qdiv.dataset.qid);
                //console.log(qdiv.dataset.aid);
                if($(qdiv).length !== 0) {
                    var data = {qid: qdiv.dataset.qid}
                    var ajaxurl = $('#ajaxgetquestionurl').val();
                    console.log(ajaxurl);
                    
                     $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: data,
                        success: function(response) {
                             console.log(response);
                        
                      

                        }
                     });
                
                
                

                    $('.questiondiv').append('<canvas id="initializeMeLater" width="150" height="150" style="border:1px solid black;">This is a message that shows if the browser doesn\'t support HTML5 canvas, which all modern browsers should support now.</canvas>');
                    
                    myCanvas = new ChemDoodle.SketcherCanvas('initializeMeLater', 500, 300, {useServices:false, oneMolecule:false});
                    
                    myCanvas.emptyMessage = 'No Data Loaded!';
                    
                    var caffeineMolFile = 'Molecule Name\n  CHEMDOOD08070920033D 0   0.00000     0.00000     0\n[Insert Comment Here]\n 14 15  0  0  0  0  0  0  0  0  1 V2000\n   -0.3318    2.0000    0.0000   O 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318    1.0000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -1.1980    0.5000    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    0.5342    0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -1.1980   -0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -2.0640    1.0000    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n    1.4804    0.8047    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    0.5342   -0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -2.0640   -1.0000    0.0000   O 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318   -1.0000    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    2.0640   -0.0000    0.0000   C 0  0  0  2  0  0  0  0  0  0  0  0\n    1.7910    1.7553    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n    1.4804   -0.8047    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318   -2.0000    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n  1  2  2  0  0  0  0\n  3  2  1  0  0  0  0\n  4  2  1  0  0  0  0\n  3  5  1  0  0  0  0\n  3  6  1  0  0  0  0\n  7  4  1  0  0  0  0\n  4  8  2  0  0  0  0\n  9  5  2  0  0  0  0\n 10  5  1  0  0  0  0\n 10  8  1  0  0  0  0\n  7 11  1  0  0  0  0\n  7 12  1  0  0  0  0\n 13  8  1  0  0  0  0\n 13 11  2  0  0  0  0\n 10 14  1  0  0  0  0\nM  END\n> <DATE>\n07-08-2009\n';
                    
                    var caffeine = ChemDoodle.readMOL(caffeineMolFile);
                    
                    myCanvas.loadMolecule(caffeine);
                    
                    myCanvas.repaint();
                
                }
                
                
                
                console.log(event.indexh);
	            // event.previousSlide, event.currentSlide, event.indexh, event.indexv
            } );
           
           
		
		
		
            Reveal.addEventListener( 'myslide1', function() {

               if(!window.myCanvas){
    // we don't provide a width or height, as those values will be absorbed from the existing element
    // place the object in the global scope so we can access it outside of this function by excluding the var keyword
                    //myCanvas = new ChemDoodle.ViewerCanvas('initializeMeLater');
                    myCanvas = new ChemDoodle.SketcherCanvas('initializeMeLater', 500, 300, {useServices:false, oneMolecule:false});
                    myCanvas.emptyMessage = 'No Data Loaded!';
                    var caffeineMolFile = 'Molecule Name\n  CHEMDOOD08070920033D 0   0.00000     0.00000     0\n[Insert Comment Here]\n 14 15  0  0  0  0  0  0  0  0  1 V2000\n   -0.3318    2.0000    0.0000   O 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318    1.0000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -1.1980    0.5000    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    0.5342    0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -1.1980   -0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -2.0640    1.0000    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n    1.4804    0.8047    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    0.5342   -0.5000    0.0000   C 0  0  0  1  0  0  0  0  0  0  0  0\n   -2.0640   -1.0000    0.0000   O 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318   -1.0000    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n    2.0640   -0.0000    0.0000   C 0  0  0  2  0  0  0  0  0  0  0  0\n    1.7910    1.7553    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n    1.4804   -0.8047    0.0000   N 0  0  0  1  0  0  0  0  0  0  0  0\n   -0.3318   -2.0000    0.0000   C 0  0  0  4  0  0  0  0  0  0  0  0\n  1  2  2  0  0  0  0\n  3  2  1  0  0  0  0\n  4  2  1  0  0  0  0\n  3  5  1  0  0  0  0\n  3  6  1  0  0  0  0\n  7  4  1  0  0  0  0\n  4  8  2  0  0  0  0\n  9  5  2  0  0  0  0\n 10  5  1  0  0  0  0\n 10  8  1  0  0  0  0\n  7 11  1  0  0  0  0\n  7 12  1  0  0  0  0\n 13  8  1  0  0  0  0\n 13 11  2  0  0  0  0\n 10 14  1  0  0  0  0\nM  END\n> <DATE>\n07-08-2009\n';
                    var caffeine = ChemDoodle.readMOL(caffeineMolFile);
                    myCanvas.loadMolecule(caffeine);
                    
                     myCanvas.repaint();
  }

            } );
        </script>
	</body>
</html>


<?php




$OUTPUT->footerStart();

$OUTPUT->footerEnd();



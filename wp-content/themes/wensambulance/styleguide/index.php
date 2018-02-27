<?php
	// Build out URI to reload from form dropdown
	// Need full url for this to work in Opera Mini
	$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";

	if (isset($_POST['sg_uri']) && isset($_POST['sg_section_switcher'])) {
		 $pageURL .= $_POST[sg_uri].$_POST[sg_section_switcher];
		 header("Location: $pageURL");
	}

	// Display title of each markup samples as a select option
	function listMarkupAsOptions ($type) {
		$files = array();
		$handle=opendir('markup/'.$type);
		while (false !== ($file = readdir($handle))):
				if(stristr($file,'.html')):
						$files[] = $file;
				endif;
		endwhile;

		sort($files);
		foreach ($files as $file):
				$filename = preg_replace("/\.html$/i", "", $file); 
				$title = preg_replace("/\-/i", " ", $filename);
				$title = ucwords($title);
				echo '<option value="#sg-'.$filename.'">'.$title.'</option>';
		endforeach;
	}

	// Display markup view & source
	function showMarkup($type) {
		$files = array();
		$handle=opendir('markup/'.$type);
		while (false !== ($file = readdir($handle))):
				if(stristr($file,'.html')):
						$files[] = $file;
				endif;
		endwhile;

		sort($files);
		foreach ($files as $file):
				$filename = preg_replace("/\.html$/i", "", $file);
				$title = preg_replace("/\-/i", " ", $filename);
				echo '<div class="sg-markup sg-section">';
				echo '<div class="sg-display">';
				echo '<h3 class="sg-h3"><a id="sg-'.$filename.'" class="sg-anchor">'.$title.'</a></h3>';
				echo '<div class="sg-section-wrap">';
				include('markup/'.$type.'/'.$file);
				echo '<div class="sg-markup-controls"><a class="sg-btn sg-btn--source" href="#">View Source</a> <a class="sg-btn--top" href="#top">Back to Top</a> </div>';
				echo '<div class="sg-source sg-animated">';
				echo '<a class="sg-btn sg-btn--select" href="#">Get the code</a>';
				echo '<pre class="prettyprint linenums"><code>';
				echo htmlspecialchars(file_get_contents('markup/'.$type.'/'.$file));
				echo '</code></pre>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
		endforeach;
	}
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html lang="en" class="ie ie8"> <![endif]-->
<!--[if lte IE 9]>     <html lang="en" class="ie"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
	<title>Volta Style Guide</title>
	<meta name="viewport" content="width=device-width">
	<!-- Force latest IE rendering engine & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<!-- Make IE recognise HTML5 elements for styling -->
	<!--[if lte IE 8]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<!-- Make IE recognise media queries for styling -->
	<script src="dev/assets/libraries/css3-mediaqueries.js"></script>

	<!-- Styleguide webfont & fontawesome -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,800,700,600' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Quando' rel='stylesheet' type='text/css'>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	

	<!-- Your webfonts & iconfont -->

	<!-- Open Sans -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600' rel='stylesheet' type='text/css'>

	<!-- Your stylesheet -->
	<link rel="stylesheet" href="/dist/css/style-guide.css">


	<!-- jQuery -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head>
<body style="padding-left: 0">
		
<div id="top" class="sg-header sg-container">
	<div class="sg-logo">
		<a href="/" rel="home" class="svg-logo-link site-logo">
			
		</a>
	</div>

	<form id="js-sg-nav" action=""  method="post" class="sg-nav">
		<i class="fa fa-bars fa-2x"></i>
		<select id="js-sg-section-switcher" class="sg-section-switcher" name="sg_section_switcher">
			<option>Menu</option>
			<optgroup label="Intro">
				<option value="#sg-about">About</option>
				<option value="#sg-colors">Colors</option>
				<option value="#sg-fontStacks">Font-Stacks</option>
			</optgroup>
			<optgroup label="Base Styles">
				<?php listMarkupAsOptions('base'); ?>
			</optgroup>
			<optgroup label="Layout Styles">
				<?php listMarkupAsOptions('layout'); ?>
			</optgroup>
			<optgroup label="Pattern Styles">
				<?php listMarkupAsOptions('patterns'); ?>
			</optgroup>
		</select>
		<input type="hidden" name="sg_uri" value="<?php echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>">
		<button type="submit" class="sg-submit-btn">Go</button>
	</form><!--/.sg-nav-->
</div><!--/.sg-header-->

<div class="sg-body sg-container">
	<h1 class="sg-h1">Website Style guide</h1>
	<hr />
	<div class="sg-info">               
		<div class="sg-about sg-section">
			<h3 class="sg-h3"><a id="sg-about" class="sg-anchor">About</a></h3>
			<div class="sg-section-wrap">
				<p>De Waele website style guide: basic design principles for creating this website.</p>
			</div>
		</div><!--/.sg-about-->
		<h2 class="sg-h2">Colors & Typography</h2>
		<div class="sg-colors sg-section">
			<h3 class="sg-h3"><a id="sg-colors" class="sg-anchor">Colors</a></h3>
			
		</div><!--/.sg-colors-->
		
		<div class="sg-font-stacks sg-section">
			<h3 class="sg-h3"><a id="sg-fontStacks" class="sg-anchor">Font Stacks</a></h3>
			<div class="sg-section-wrap">
				
				
			</div>
		</div><!--/.sg-font-stacks-->
	</div><!--/.sg-info-->    

	<div class="sg-base-styles">    
		<h2 class="sg-h2">Base Styles</h2>
		<?php showMarkup('base'); ?>
	</div><!--/.sg-base-styles-->

	<div class="sg-layout-styles">
			<h2 class="sg-h2">Layout Styles</h2>
			<?php showMarkup('layout'); ?>
	</div><!--/.sg-layout-styles-->

	<div class="sg-pattern-styles">
			<h2 class="sg-h2">Pattern Styles<small> - Design and mark-up patterns unique to this site.</small></h2>
			<?php showMarkup('patterns'); ?>
	</div><!--/.sg-pattern-styles-->
	

	<script src="dev/assets/libraries/sg-plugins.js"></script>
	<script src="dev/assets/libraries/sg-scripts-ck.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/less.js/1.7.0/less.min.js"></script>
	<script src="dev/assets/libraries/sg-script.js"></script>
	<script src="/dist/js/main.js"></script>
</body>
</html>
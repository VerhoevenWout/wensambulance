# volta-theme
The base Wordpress theme used by Volta web

Opzetten WP met volta theme

	1.	WP downloaden en installeren
	2.	volta-theme git clone in themes folder
	3.  verander in gulpfile.js de variabele 'proxy' naar proxy van website in MAMP
	4.	npm install
	5.	bower install
	6.	gulp

Functionaliteiten:
	-	Autoprefixer: http://pleeease.io/docs/#autoprefixer
	-	Filters (OldIE: true): http://pleeease.io/docs/#filters
	-	REM (1rem = 10px): http://pleeease.io/docs/#rem
	-	Opacity: http://pleeease.io/docs/#opacity
	-	Calc (Resolves calc() references whenever possible) : http://pleeease.io/docs/#calc
	-	Mqpacker (Packs same CSS media-queries into one media-query rule): http://pleeease.io/docs/#mqpacker

===========
	Speciale code
===========


In header.php:
<!-- build:css dist/css/vendor.css -->
<!-- bower:css -->

<!-- endbower -->
<!-- endbuild -->

WireDep gaat css van bower components hier injecteren
bij runnen van php-taak worden alle css files hierin samengevoegd en naar dist/css/vendor.css weggeschreven.

In footer.php:
<!-- build:js dist/js/vendor.js -->
<!-- bower:js -->
<!-- endbower -->
<!-- endbuild -->

Gelijkaardig aan hierboven

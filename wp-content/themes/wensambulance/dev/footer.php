<?php
/**
 * The template for displaying the footer
 *
 */
?>
</div>

<footer>
	<div class="row">
		<div class="small-12">
			<div class="left">
				<img src="<?= get_bloginfo('template_url') ?>/dist/img/logo-1.png ?>" alt="logo">
			</div>
			<div class="right">
				<?php wp_nav_menu( array('menu' => 'main-menu')); ?>
				<p>Volimus-VZW &copy; - <?php echo get_the_date('Y');?></p> 
			</div>

		</div>

	</div>
	<div class="row">
		<div class="small-12">
			<!-- <a href="">Policy</a> -->
		</div>
	</div>
</footer>

<!-- build:js dist/js/vendor.js -->
<!-- bower:js -->
<!-- endbower -->
<!-- endbuild -->

<script>
	var mapstyle = [{"elementType": "geometry", "stylers": [{"color": "#f5f5f5"} ] }, {"elementType": "labels.icon", "stylers": [{"visibility": "off"} ] }, {"elementType": "labels.text.fill", "stylers": [{"color": "#616161"} ] }, {"elementType": "labels.text.stroke", "stylers": [{"color": "#f5f5f5"} ] }, {"featureType": "administrative.land_parcel", "elementType": "labels", "stylers": [{"visibility": "off"} ] }, {"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#bdbdbd"} ] }, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#eeeeee"} ] }, {"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"} ] }, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"} ] }, {"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"} ] }, {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#ffffff"} ] }, {"featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{"color": "#757575"} ] }, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#dadada"} ] }, {"featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{"color": "#616161"} ] }, {"featureType": "road.local", "elementType": "labels", "stylers": [{"visibility": "off"} ] }, {"featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"} ] }, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#e5e5e5"} ] }, {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#eeeeee"} ] }, {"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#eceff7"} ] }, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#9e9e9e"} ] } ]
	function initMap() {
		var location = {lat: 51.229704, lng: 4.548123};
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 13,
			center: location,
			styles: mapstyle
		});
		var marker = new google.maps.Marker({
			position: location,
			map: map,
			icon: '/wp-content/themes/wensambulance/dist/img/marker.png'
		});
	}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNR7XHe3pByLxS0cH9NYkFvT_aHqho4Fo&callback=initMap"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/dist/js/main.js" type="text/javascript"></script>

</body>
</html>
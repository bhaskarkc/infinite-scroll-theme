<?php
get_header();
?>
<div id="infinite" infinite-scroll>
		<div id="wrapper">
			<main id="main">
				<header ng-style="{ 'height' : viewportHeight*2 + 'px' }">
					<div>
						<h1>{{ message }}</h1>
						<h3 class="sub">Scroll like the wind...</h3>
						<p>Just a fun little demo for monitoring user scrolling with angularjs directives. Scroll up and down to see
							animation as the elements enter and leave the viewport.</p>
						<p>Playfair Display &amp; Source Sans Pro are what makes this look pretty.</p>
						<p>Stock photos courtesy of <a href="https://unsplash.com/">unsplash</a>.</p>
					</div>
				</header>
				<article visible visible-model="article.visible" ng-class="{ 'visible' : article.visible }" ng-repeat="article in forests" ng-class-odd="'odd'">
					<figure ng-style="{ 'background-image' : 'url(' + article.image + ')' }"></figure>
					<main>
						<h3>{{ article.name }}</h3>
						<h5 class="sub">{{ article.location }}</h5>
						<p>{{ article.summary }}</p>
					</main>
				</article>
				<footer ng-style="{ 'height' : viewportHeight*2 + 'px', 'margin-bottom' : 0 - viewportHeight + 'px' }">
					<div>
						<h1>{{ message }}</h1>
						<h3 class="sub">Scroll like the wind...</h3>
						<h4>Infinite Scrolling Concept</h4>
						<p>Scroll up and down to see animation as the elements enter and leave the viewport.</p>
						<p>Playfair Display &amp; Source Sans Pro are what makes this look pretty.</p>
						<p>Stock photos courtesy of <a href="https://unsplash.com/">unsplash</a>.</p>
					</div>
				</footer>
			</main>
		</div>
	</div>
<?php
get_footer();

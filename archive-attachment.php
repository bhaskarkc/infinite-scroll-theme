<?php get_header(); ?>
<main>
	<h1>Infinite Scroll + Pagination Experiment</h1>
	<p>After a poll by <a href="https://twitter.com/smashingmag">@smashingmag</a>, <a href="https://twitter.com/TimSeverien/status/693186708494536704">I got the idea to combine pagination and infinite scroll.</a> The former gives the user control, but needs more effort. The latter lacks control, but requires no special interaction.</p>
	<div class="article-list" id="article-list"></div>
	<ul class="article-list__pagination article-list__pagination--inactive" id="article-list-pagination"></ul>
</main>
<?php
get_footer();

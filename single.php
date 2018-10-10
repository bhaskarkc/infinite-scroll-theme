<?php
get_header();

while ( have_posts() ) {
	the_post(); ?>
<h1><a href="<?php echo get_home_url(); ?>">Home</a></h1>
<h2><?php the_title(); ?> </h2>

<?php the_post_thumbnail(); ?>

<p><?php the_content(); ?>
<?php
}

get_footer();

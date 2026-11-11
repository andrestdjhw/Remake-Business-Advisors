<?php

get_header(); ?>

<main id="main">
  <?php if (have_posts()) {
    while (have_posts()) {
      the_post(); ?>
      <article>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
    <?php }
  } ?>
</main>

<?php get_footer();

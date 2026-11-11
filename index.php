<?php

get_header(); ?>

<main id="main">
  <?php if (have_posts()) {
    while (have_posts()) {
      the_post(); ?>
      <article>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_content(); ?>
      </article>
    <?php }
  } ?>
</main>

<?php get_footer();

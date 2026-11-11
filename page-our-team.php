<?php
/**
 * Our Team (brief §6.5): listado verificable sin módulos vacíos.
 * Sin perfiles individuales hasta tener biografías aprobadas; /team/{slug}/ redirige aquí (#slug).
 */

get_header();
?>

<main id="main">
  <?php get_template_part('template-parts/page-hero', null, array(
    'eyebrow' => 'Our Team',
    'title' => 'The People Behind Your Progress',
    'intro' => 'Meet the business consultants and educators who work with entrepreneurs every day to diagnose, structure, and grow their companies.',
    'trail' => array(array('Our Team', null)),
  )); ?>

  <section class="py-20 lg:py-28" aria-labelledby="team-list-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Members of Our Team</p>
        <h2 id="team-list-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">A team committed to your success</h2>
        <p class="mt-4 text-lg text-ink/75" data-reveal>A group with a shared mission: helping entrepreneurs build stronger, more structured, and lasting businesses.</p>
      </div>
      <div class="mt-14">
        <?php get_template_part('template-parts/team-grid'); ?>
      </div>
    </div>
  </section>

  <section class="bg-cream py-20 lg:py-24" aria-labelledby="team-values-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>How We Work With You</p>
        <h2 id="team-values-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">The values behind every conversation</h2>
      </div>
      <ul class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
        <?php foreach (rba_values() as $value) : ?>
          <li class="rounded-2xl bg-white p-6 ring-1 ring-brand-100" data-reveal>
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-500"><?php echo rba_icon($value['icon'], 'h-5 w-5'); ?></span>
            <h3 class="mt-4 font-semibold text-navy-900"><?php echo esc_html($value['title']); ?></h3>
            <p class="mt-2 text-sm leading-relaxed text-ink/70"><?php echo esc_html($value['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <?php get_template_part('template-parts/cta-band', null, array(
    'title' => 'Work With Our Team',
    'text' => 'Book a consultation and tell us about your business. We will connect you with the right person on our team.',
  )); ?>
</main>

<?php get_footer();

<?php
/**
 * Landing de servicio (/service/{slug}/). Patrón del brief §6.3:
 * H1 único → problema → solución → destinatarios → qué incluye → FAQs → CTA → servicios relacionados.
 * El contenido vive en inc/pages-content.php (rba_service_details()).
 */

get_header();

$service = rba_service_by_slug(get_post_field('post_name'));
$is_bca = $service['slug'] === 'business-coaching-academy';
$cta_url = add_query_arg('service', $service['slug'], rba_contact_url());
$cta_label = $is_bca ? 'Request Program Information' : 'Book a Consultation';
$group_label = $service['group'] === 'training' ? 'Training & Education' : 'Consulting Service';

// Datos estructurados del servicio (coinciden con el contenido visible).
$schema = array(
  '@context' => 'https://schema.org',
  '@type' => $is_bca ? 'Course' : 'Service',
  'name' => $service['title'],
  'description' => $service['summary'],
  'url' => get_permalink(),
  ($is_bca ? 'provider' : 'provider') => array('@type' => 'Organization', 'name' => 'Remake Business Advisors', 'url' => home_url('/')),
);
if (!$is_bca) $schema['serviceType'] = $service['title'];
?>

<main id="main">
  <script type="application/ld+json"><?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES); ?></script>

  <?php get_template_part('template-parts/page-hero', null, array(
    'eyebrow' => $group_label,
    'title' => $service['title'],
    'intro' => $service['summary'],
    'trail' => array(array('Our Services', home_url('/services/')), array($service['title'], null)),
    'image' => (int) get_theme_mod('rba_service_img_' . $service['slug']),
    'actions' => true,
    'cta_url' => $cta_url,
    'cta_label' => $cta_label,
  )); ?>

  <!-- Problema y solución -->
  <section class="py-20 lg:py-28" aria-labelledby="overview-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <h2 id="overview-title" class="js-split max-w-4xl text-3xl leading-tight font-bold text-navy-900 sm:text-4xl"><?php echo esc_html($service['headline']); ?></h2>
      <div class="mt-12 grid gap-6 md:grid-cols-2">
        <article class="rounded-[1.75rem] bg-cream p-8 ring-1 ring-brand-100 sm:p-10" data-reveal>
          <p class="eyebrow">The Challenge</p>
          <p class="mt-4 text-lg leading-relaxed text-ink/80"><?php echo esc_html($service['problem']); ?></p>
        </article>
        <article class="relative isolate overflow-hidden rounded-[1.75rem] bg-navy-900 p-8 text-white sm:p-10" data-reveal>
          <div class="rba-grid-pattern" aria-hidden="true"></div>
          <p class="font-heading text-xs font-semibold tracking-[0.25em] text-brand-400 uppercase">How We Help</p>
          <p class="mt-4 text-lg leading-relaxed text-white/85"><?php echo esc_html($service['solution']); ?></p>
        </article>
      </div>
    </div>
  </section>

  <!-- Para quién es / Qué incluye -->
  <section class="bg-cream py-20 lg:py-24" aria-label="Who it is for and what is included">
    <div class="mx-auto grid max-w-site gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:px-10">
      <?php
      $lists = array(
        array('title' => 'Who It\'s For', 'icon' => 'users', 'items' => $service['for']),
        array('title' => $is_bca ? 'What You\'ll Learn' : 'What\'s Included', 'icon' => 'check', 'items' => $is_bca ? array_map(function ($m) { return $m['title']; }, $service['modules']) : $service['includes']),
      );
      foreach ($lists as $list) : ?>
        <article class="rounded-[1.75rem] bg-white p-8 shadow-sm ring-1 ring-black/5 sm:p-10" data-reveal>
          <div class="flex items-center gap-4">
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500"><?php echo rba_icon($list['icon'], 'h-6 w-6'); ?></span>
            <h2 class="text-2xl font-bold text-navy-900"><?php echo esc_html($list['title']); ?></h2>
          </div>
          <ul class="mt-6 space-y-4">
            <?php foreach ($list['items'] as $item) : ?>
              <li class="flex gap-3 leading-relaxed text-ink/80">
                <?php echo rba_icon('check', 'mt-1 h-5 w-5 shrink-0 text-brand-500'); ?>
                <span><?php echo esc_html($item); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if ($is_bca) : ?>
    <!-- Módulos del BCA (acordeón). Fechas, horarios y precios: solo tras verificación (brief §6.4). -->
    <section class="py-20 lg:py-28" aria-labelledby="modules-title">
      <div class="mx-auto grid max-w-site gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-16 lg:px-10">
        <div class="lg:col-span-4">
          <div class="lg:sticky lg:top-[calc(var(--wp-admin--admin-bar--height,0px)_+_7rem)]">
            <p class="eyebrow" data-reveal>Program</p>
            <h2 id="modules-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Seven modules, one structured path</h2>
            <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>Each module focuses on a key area of your business. Dates, schedules, and enrollment options are shared for each new group.</p>
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-primary mt-8" data-reveal><?php echo esc_html($cta_label); ?> <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?></a>
          </div>
        </div>
        <div class="rba-faq lg:col-span-8">
          <?php foreach ($service['modules'] as $i => $module) : ?>
            <details class="rba-faq__item" name="rba-modules" data-reveal <?php echo $i === 0 ? 'open' : ''; ?>>
              <summary class="rba-faq__q">
                <span class="rba-faq__num" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                <span class="flex-1">Module <?php echo (int) $i + 1; ?>: <?php echo esc_html($module['title']); ?></span>
                <span class="rba-faq__icon" aria-hidden="true"><?php echo rba_icon('plus', 'h-5 w-5'); ?></span>
              </summary>
              <div class="rba-faq__a">
                <ul class="space-y-2">
                  <?php foreach ($module['items'] as $item) : ?>
                    <li class="flex gap-3"><?php echo rba_icon('check', 'mt-1 h-4 w-4 shrink-0 text-brand-500'); ?><span><?php echo esc_html($item); ?></span></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php get_template_part('template-parts/faq', null, array(
    'faqs' => $service['faqs'],
    'title' => 'Questions About ' . $service['title'],
    'intro' => 'Answers to common questions about this ' . ($service['group'] === 'training' ? 'program' : 'service') . '.',
    'bg' => $is_bca ? 'bg-cream' : 'bg-white',
  )); ?>

  <!-- Servicios relacionados (enlazado interno) -->
  <section class="<?php echo $is_bca ? 'bg-white' : 'bg-cream'; ?> py-20 lg:py-24" aria-labelledby="related-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="eyebrow" data-reveal>Keep Exploring</p>
          <h2 id="related-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Related services</h2>
        </div>
        <a href="<?php echo esc_url(home_url('/services/')); ?>" class="inline-flex items-center gap-2 font-heading text-sm font-semibold text-brand-700 hover:text-brand-800" data-reveal>View all services <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?></a>
      </div>
      <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($service['related'] as $slug) : ?>
          <li data-reveal><?php get_template_part('template-parts/service-card', null, array('service' => rba_service_by_slug($slug))); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <?php get_template_part('template-parts/cta-band', null, array(
    'title' => $is_bca ? 'Ready to Join the Next Group?' : 'Let’s Talk About Your Business',
    'text' => $is_bca ? 'Request information and our team will share the current dates, schedule, and enrollment options.' : 'Book a consultation and we will help you decide if ' . $service['title'] . ' is the right next step.',
    'label' => $cta_label,
    'url' => $cta_url,
  )); ?>
</main>

<?php get_footer();

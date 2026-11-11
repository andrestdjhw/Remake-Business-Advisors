<?php
/**
 * Our Services (hub): los siete servicios agrupados en consultoría y formación, con enlace a cada landing.
 */

get_header();

$services = rba_services();
$groups = array(
  'consulting' => array('title' => 'Consulting Services', 'intro' => 'One-on-one support to understand your business, organize it, and make better decisions.', 'cols' => 'lg:grid-cols-3'),
  'training' => array('title' => 'Training & Education', 'intro' => 'Programs that give you and your team the knowledge and tools to lead with structure.', 'cols' => 'lg:grid-cols-2'),
);
?>

<main id="main">
  <?php get_template_part('template-parts/page-hero', null, array(
    'eyebrow' => 'Our Services',
    'title' => 'Solutions Tailored for Your Business',
    'intro' => 'From diagnosis and strategic planning to hands-on advisory and leadership training, choose the support that fits where your business is today.',
    'trail' => array(array('Our Services', null)),
    'actions' => true,
  )); ?>

  <?php foreach ($groups as $key => $group) : ?>
    <?php $items = array_values(array_filter($services, function ($s) use ($key) { return $s['group'] === $key; })); ?>
    <section class="<?php echo $key === 'training' ? 'bg-cream' : ''; ?> py-20 lg:py-24" aria-labelledby="group-<?php echo esc_attr($key); ?>">
      <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
          <p class="eyebrow" data-reveal><?php echo esc_html(count($items) . ' ' . ($key === 'training' ? 'programs' : 'services')); ?></p>
          <h2 id="group-<?php echo esc_attr($key); ?>" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl"><?php echo esc_html($group['title']); ?></h2>
          <p class="mt-4 text-lg text-ink/75" data-reveal><?php echo esc_html($group['intro']); ?></p>
        </div>
        <ul class="mt-12 grid gap-6 sm:grid-cols-2 <?php echo esc_attr($group['cols']); ?>">
          <?php foreach ($items as $service) : ?>
            <li data-reveal><?php get_template_part('template-parts/service-card', null, array('service' => $service)); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
  <?php endforeach; ?>

  <!-- ¿No sabe por dónde empezar? -->
  <section class="py-20 lg:py-24" aria-labelledby="start-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="relative isolate grid items-center gap-8 overflow-hidden rounded-[1.75rem] bg-navy-900 p-8 text-white sm:p-12 lg:grid-cols-[1.4fr_1fr]" data-reveal>
        <div class="rba-grid-pattern" aria-hidden="true"></div>
        <div>
          <p class="font-heading text-xs font-semibold tracking-[0.25em] text-brand-400 uppercase">Not sure where to start?</p>
          <h2 id="start-title" class="mt-3 text-3xl font-bold">Start with a clear picture of your business.</h2>
          <p class="mt-4 text-lg leading-relaxed text-white/75">A Business Diagnosis is the best starting point for most companies. Or book a consultation and we will recommend the right service for your situation.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
          <a href="<?php echo esc_url(rba_contact_url()); ?>" class="btn btn-primary"><?php echo rba_icon('calendar', 'h-5 w-5'); ?> Book a Consultation</a>
          <a href="<?php echo esc_url(rba_service_url('business-diagnosis')); ?>" class="btn border-2 border-white/40 text-white hover:border-white hover:bg-white hover:text-navy-900">Explore Business Diagnosis <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?></a>
        </div>
      </div>
    </div>
  </section>

  <?php get_template_part('template-parts/faq', null, array('bg' => 'bg-cream')); ?>
  <?php get_template_part('template-parts/cta-band'); ?>
</main>

<?php get_footer();

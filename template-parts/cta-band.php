<?php
/**
 * Banda final de llamada a la acción.
 * Args: title, text, label, url.
 */
$title = isset($args['title']) ? $args['title'] : 'Let’s Talk About Your Business';
$text = isset($args['text']) ? $args['text'] : 'Schedule a consultation and discover how we can help you reimagine and grow your business.';
$label = isset($args['label']) ? $args['label'] : 'Book a Consultation';
$url = isset($args['url']) ? $args['url'] : rba_contact_url();
?>
  <section class="px-4 py-20 sm:px-6 lg:px-10 lg:py-24" aria-labelledby="cta-title">
    <div data-reveal class="mx-auto flex max-w-site flex-col items-start gap-6 rounded-2xl bg-gradient-to-r from-brand-50 via-brand-100 to-brand-50 p-8 ring-1 ring-brand-100 sm:p-10 md:flex-row md:items-center">
      <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white text-brand-500 shadow-sm">
        <?php echo rba_icon('calendar', 'h-7 w-7'); ?>
      </span>
      <div class="flex-1">
        <h2 id="cta-title" class="text-2xl font-bold text-navy-900"><?php echo esc_html($title); ?></h2>
        <p class="mt-1 text-ink/75"><?php echo esc_html($text); ?></p>
      </div>
      <a href="<?php echo esc_url($url); ?>" class="btn btn-navy">
        <?php echo esc_html($label); ?>
        <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?>
      </a>
    </div>
  </section>


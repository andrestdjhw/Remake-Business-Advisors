<?php
/**
 * Contacto: qué pasa después de enviar el formulario (rba_next_steps() en inc/pages-content.php).
 */
$steps = rba_next_steps();
?>
  <section class="py-20 lg:py-24" aria-labelledby="next-steps-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>What Happens Next</p>
        <h2 id="next-steps-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">From your message to a clear next step</h2>
      </div>
      <ol class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($steps as $i => $step) : ?>
          <li class="relative rounded-2xl border border-gray-200 bg-white p-7" data-reveal>
            <div class="flex items-center justify-between">
              <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-700"><?php echo rba_icon($step['icon'], 'h-6 w-6'); ?></span>
              <span class="font-heading text-3xl font-bold text-gray-200" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
            </div>
            <h3 class="mt-5 text-lg font-semibold text-navy-900"><span class="sr-only">Step <?php echo (int) $i + 1; ?>: </span><?php echo esc_html($step['title']); ?></h3>
            <p class="mt-2 text-sm leading-relaxed text-ink/70"><?php echo esc_html($step['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

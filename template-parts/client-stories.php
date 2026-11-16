<?php
/**
 * Historias de clientes y reseñas (rba_client_stories() en inc/pages-content.php).
 * PENDIENTE: casos y reseñas reales de Laura. Mientras no haya, se muestran 3 espacios marcados
 * (correo del 11-nov-2026: "deje los espacios marcados y no ponga números de ejemplo").
 */
$stories = rba_client_stories();
?>
  <section class="py-20 lg:py-28" aria-labelledby="stories-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Client Stories</p>
        <h2 id="stories-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">What business owners say about working with us</h2>
        <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>Real experiences from entrepreneurs who decided to give their business more structure.</p>
      </div>

      <ul class="mt-12 grid gap-6 md:grid-cols-3">
        <?php if ($stories) : ?>
          <?php foreach ($stories as $story) : ?>
            <li data-reveal>
              <figure class="flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-8">
                <?php echo rba_icon('quote', 'h-8 w-8 text-brand-200'); ?>
                <blockquote class="mt-5 flex-1 text-lg leading-relaxed text-ink/85"><?php echo esc_html($story['quote']); ?></blockquote>
                <figcaption class="mt-6 border-t border-gray-100 pt-5">
                  <span class="block font-heading font-semibold text-navy-900"><?php echo esc_html($story['name']); ?></span>
                  <?php if (!empty($story['business'])) : ?><span class="block text-sm text-ink/60"><?php echo esc_html($story['business']); ?></span><?php endif; ?>
                </figcaption>
              </figure>
            </li>
          <?php endforeach; ?>
        <?php else : ?>
          <?php for ($i = 1; $i <= 3; $i++) : ?>
            <li data-reveal>
              <div class="flex h-full min-h-64 flex-col justify-between rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-8">
                <?php echo rba_icon('quote', 'h-8 w-8 text-gray-300'); ?>
                <p class="mt-5 font-heading text-sm font-semibold tracking-wide text-ink/50 uppercase">[Client story <?php echo (int) $i; ?> pending: provided by Remake]</p>
                <p class="mt-2 text-sm text-ink/50">Client quote, name, business, and the service they used.</p>
              </div>
            </li>
          <?php endfor; ?>
        <?php endif; ?>
      </ul>
    </div>
  </section>

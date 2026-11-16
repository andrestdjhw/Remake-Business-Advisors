<?php
/**
 * "Does this sound familiar?": situaciones frecuentes de los dueños de negocio, cada una enlazada
 * al servicio que la atiende (rba_challenges() en inc/pages-content.php).
 */
?>
  <section class="bg-cream py-20 lg:py-28" aria-labelledby="challenges-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Where to Start</p>
        <h2 id="challenges-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Does this sound familiar?</h2>
        <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>These are some of the situations business owners bring to us most often. Find yours and see how we can help.</p>
      </div>

      <ul class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach (rba_challenges() as $item) : ?>
          <?php $service = rba_service_by_slug($item['service']); ?>
          <?php if (!$service) continue; ?>
          <li data-reveal>
            <article class="group relative flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-7 transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl">
              <?php echo rba_icon('quote', 'h-7 w-7 text-brand-200'); ?>
              <p class="mt-4 flex-1 font-heading text-lg leading-snug font-medium text-navy-900">&ldquo;<?php echo esc_html($item['text']); ?>&rdquo;</p>
              <div class="mt-6 flex items-center gap-3 border-t border-gray-100 pt-5">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700 transition-colors duration-300 group-hover:bg-navy-900 group-hover:text-white">
                  <?php echo rba_icon($service['icon'], 'h-5 w-5'); ?>
                </span>
                <a href="<?php echo esc_url(rba_service_url($service['slug'])); ?>" class="flex-1 font-heading text-sm font-semibold text-navy-900 after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none group-focus-within:after:ring-2 group-focus-within:after:ring-brand-500">
                  <span class="block text-xs font-medium tracking-wide text-ink/60 uppercase">We can help with</span>
                  <?php echo esc_html($service['title']); ?>
                </a>
                <?php echo rba_icon('arrowRight', 'h-4 w-4 text-brand-700 transition group-hover:translate-x-1'); ?>
              </div>
            </article>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

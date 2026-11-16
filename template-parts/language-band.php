<?php
/**
 * Atención en inglés o español y formas de reunirse (datos ya publicados en Contacto).
 */
$company = rba_company();
$ways = array(
  array('icon' => 'mapPin', 'title' => 'In person', 'text' => 'At our office in ' . $company['locality'] . ', ' . $company['region'] . '.'),
  array('icon' => 'video', 'title' => 'On Zoom', 'text' => 'Meet from anywhere, wherever your business is.'),
  array('icon' => 'phone', 'title' => 'By phone', 'text' => 'A quick call when it fits your schedule.'),
);
?>
  <section class="bg-navy-900 py-20 text-white lg:py-24" aria-labelledby="language-title">
    <div class="mx-auto grid max-w-site items-center gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:px-10">
      <div class="lg:col-span-5">
        <p class="eyebrow text-brand-400" data-reveal>English &middot; Español</p>
        <h2 id="language-title" class="js-split mt-3 text-3xl font-bold sm:text-4xl">Business guidance in the language you prefer</h2>
        <p class="mt-4 text-lg leading-relaxed text-white/75" data-reveal>Talking about your numbers, your team, and your plans is easier in your own language. Our consultations are available in English or Spanish. <span lang="es">Se habla español.</span></p>
      </div>
      <ul class="grid gap-4 sm:grid-cols-3 lg:col-span-7">
        <?php foreach ($ways as $way) : ?>
          <li class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10" data-reveal>
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-brand-300"><?php echo rba_icon($way['icon'], 'h-5 w-5'); ?></span>
            <h3 class="mt-4 text-lg font-semibold"><?php echo esc_html($way['title']); ?></h3>
            <p class="mt-1 text-sm leading-relaxed text-white/70"><?php echo esc_html($way['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

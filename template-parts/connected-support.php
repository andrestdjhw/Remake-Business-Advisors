<?php
/**
 * Apoyo conectado: Remake + sitios hermanos (Apex y Laura Elena Martínez).
 * Descripciones tomadas de cada sitio (16-nov-2026). URLs editables en el Personalizador.
 * Args: bg (clase de fondo, por defecto bg-cream).
 * PENDIENTE: confirmar el nombre oficial de Apex.
 */
$bg = isset($args['bg']) ? $args['bg'] : 'bg-cream';
$apex = get_theme_mod('rba_sister_apex_url', 'https://apexaccountinginc.com/');
$lem = get_theme_mod('rba_sister_lem_url', 'https://lem.828marketingsolutions.com/');
$items = array(
  array('icon' => 'compass', 'name' => 'Remake Business Advisors', 'text' => 'Business consulting and training: diagnosis, structure, planning, and leadership.', 'url' => '', 'current' => true),
  array('icon' => 'calculator', 'name' => 'Apex Accounting & Advisory', 'text' => 'Tax and accounting that builds the financial structure behind your business, in the same Irvine office.', 'url' => $apex),
  array('icon' => 'mic', 'name' => 'Laura Elena Martínez', 'text' => 'Talks, workshops, and the “Al Grano con los Negocios” podcast for Hispanic business owners.', 'url' => $lem),
);
?>
  <section class="<?php echo esc_attr($bg); ?> py-20 lg:py-28" aria-labelledby="connected-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Connected Support</p>
        <h2 id="connected-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Your business, your numbers, and your growth, working together</h2>
        <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>Remake is part of a group of businesses that share the same mission. When your business needs more than consulting, you have a team you already trust.</p>
      </div>

      <ul class="mt-12 grid gap-6 md:grid-cols-3">
        <?php foreach ($items as $item) : ?>
          <li data-reveal>
            <article class="group relative flex h-full flex-col rounded-2xl p-8 transition duration-300 <?php echo !empty($item['current']) ? 'bg-navy-900 text-white' : 'border border-gray-200 bg-white hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl'; ?>">
              <span class="flex h-12 w-12 items-center justify-center rounded-xl <?php echo !empty($item['current']) ? 'bg-white/10 text-brand-300' : 'bg-brand-50 text-brand-700'; ?>"><?php echo rba_icon($item['icon'], 'h-6 w-6'); ?></span>
              <h3 class="mt-5 text-lg font-semibold <?php echo !empty($item['current']) ? 'text-white' : 'text-navy-900'; ?>"><?php echo esc_html($item['name']); ?></h3>
              <p class="mt-2 flex-1 text-sm leading-relaxed <?php echo !empty($item['current']) ? 'text-white/75' : 'text-ink/70'; ?>"><?php echo esc_html($item['text']); ?></p>
              <?php if (!empty($item['current'])) : ?>
                <p class="mt-5 font-heading text-sm font-semibold text-brand-300">You are here</p>
              <?php elseif ($item['url']) : ?>
                <a href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 font-heading text-sm font-semibold text-brand-700 after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none group-focus-within:after:ring-2 group-focus-within:after:ring-brand-500">
                  Visit site<span class="sr-only">: <?php echo esc_html($item['name']); ?> (opens in a new tab)</span>
                  <?php echo rba_icon('external', 'h-4 w-4'); ?>
                </a>
              <?php endif; ?>
            </article>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

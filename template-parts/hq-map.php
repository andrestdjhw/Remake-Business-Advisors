<?php
/**
 * Sede en Irvine con mapa de Google y tarjeta de contacto.
 */
$company = rba_company();
?>
  <section class="bg-cream py-20 lg:py-24" aria-labelledby="hq-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="relative overflow-hidden rounded-[1.75rem] shadow-xl ring-1 ring-black/5" data-reveal>
        <iframe
          class="block h-[420px] w-full grayscale-[35%] lg:h-[520px]"
          src="<?php echo esc_url(rba_maps_embed_url()); ?>"
          title="Map showing Remake Business Advisors headquarters in Irvine, California"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen></iframe>

        <div class="relative bg-white p-6 sm:p-8 lg:absolute lg:top-8 lg:right-8 lg:w-[24rem] lg:rounded-2xl lg:bg-white/85 lg:shadow-2xl lg:ring-1 lg:ring-white/70 lg:backdrop-blur-xl">
          <p class="eyebrow">Our Headquarters</p>
          <h2 id="hq-title" class="mt-2 text-2xl font-bold text-navy-900">Visit Us in Irvine, California</h2>
          <address class="mt-5 space-y-4 not-italic">
            <p class="flex gap-3 text-ink/80">
              <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-500"><?php echo rba_icon('mapPin', 'h-5 w-5'); ?></span>
              <span><?php echo esc_html($company['street']); ?><br><?php echo esc_html($company['locality'] . ', ' . $company['region'] . ' ' . $company['postal']); ?></span>
            </p>
            <p class="flex items-center gap-3">
              <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-500"><?php echo rba_icon('phone', 'h-5 w-5'); ?></span>
              <a href="tel:<?php echo esc_attr($company['phone_e164']); ?>" class="font-medium text-navy-900 hover:text-brand-700"><?php echo esc_html($company['phone']); ?></a>
            </p>
          </address>
          <p class="mt-5 text-sm text-ink/70">Sessions are also available by phone and Zoom.</p>
          <a href="<?php echo esc_url(rba_maps_directions_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-navy mt-6 w-full">
            <?php echo rba_icon('navigation', 'h-4 w-4'); ?>
            Get Directions
            <span class="sr-only">(opens Google Maps in a new tab)</span>
          </a>
        </div>
      </div>
    </div>
  </section>


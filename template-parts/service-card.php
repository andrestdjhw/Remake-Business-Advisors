<?php
/**
 * Tarjeta de servicio con imagen (carrusel del home y hub de servicios).
 * Args: service (elemento de rba_services()), heading (h2|h3).
 */
$service = $args['service'];
$heading = isset($args['heading']) ? $args['heading'] : 'h3';
?>
<article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white transition duration-300 hover:-translate-y-1 hover:border-brand-300 hover:shadow-xl">
  <div class="relative aspect-[4/3] overflow-hidden bg-cream">
    <?php echo rba_image('rba_service_img_' . $service['slug'], 'h-full w-full object-cover transition duration-700 ease-out group-hover:scale-105', 'medium_large', array('loading' => 'lazy', 'sizes' => '(min-width: 1280px) 340px, (min-width: 640px) 45vw, 85vw', 'draggable' => 'false')); ?>
    <span class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-navy-950/45 to-transparent" aria-hidden="true"></span>
    <span class="absolute top-4 left-4 rounded-full bg-white/80 px-3 py-1 font-heading text-xs font-semibold text-navy-900 ring-1 ring-white/70 backdrop-blur-md">
      <?php echo $service['group'] === 'training' ? 'Training' : 'Consulting'; ?>
    </span>
  </div>
  <div class="relative flex flex-1 flex-col p-6 pt-9">
    <span class="absolute -top-6 left-6 flex h-12 w-12 items-center justify-center rounded-xl bg-white text-brand-500 shadow-lg ring-1 ring-brand-100 transition-colors duration-300 group-hover:bg-brand-500 group-hover:text-white">
      <?php echo rba_icon($service['icon'], 'h-6 w-6'); ?>
    </span>
    <<?php echo $heading; ?> class="text-lg leading-snug font-semibold text-navy-900"><?php echo esc_html($service['title']); ?></<?php echo $heading; ?>>
    <p class="mt-2 flex-1 text-sm leading-relaxed text-ink/70"><?php echo esc_html($service['summary']); ?></p>
    <a href="<?php echo esc_url(rba_service_url($service['slug'])); ?>" class="mt-5 inline-flex items-center gap-2 font-heading text-sm font-semibold text-brand-700 after:absolute after:inset-0 after:rounded-2xl focus-visible:outline-none group-focus-within:after:ring-2 group-focus-within:after:ring-brand-500" draggable="false">
      Explore service<span class="sr-only">: <?php echo esc_html($service['title']); ?></span>
      <?php echo rba_icon('arrowRight', 'h-4 w-4 transition group-hover:translate-x-1'); ?>
    </a>
  </div>
</article>

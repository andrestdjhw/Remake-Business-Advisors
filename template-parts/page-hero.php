<?php
/**
 * Encabezado de páginas internas (navy con patrón de cuadrícula).
 * Args: eyebrow, title (H1), intro, trail (breadcrumbs sin "Home"), image (ID de medios), actions (bool: CTA principal).
 */
$eyebrow = isset($args['eyebrow']) ? $args['eyebrow'] : '';
$title = isset($args['title']) ? $args['title'] : get_the_title();
$intro = isset($args['intro']) ? $args['intro'] : '';
$trail = isset($args['trail']) ? $args['trail'] : array(array($title, null));
$image = isset($args['image']) ? (int) $args['image'] : 0;
$actions = !empty($args['actions']);
?>
<header class="relative isolate overflow-hidden bg-navy-900 text-white">
  <div class="rba-grid-pattern" aria-hidden="true"></div>
  <div class="pointer-events-none absolute -right-24 -bottom-40 h-96 w-96 rounded-full border-[2.5rem] border-brand-500/20" data-parallax="-30" aria-hidden="true"></div>

  <div class="relative mx-auto grid max-w-site items-center gap-12 px-4 py-14 sm:px-6 lg:px-10 lg:py-20 <?php echo $image ? 'lg:grid-cols-2' : ''; ?>">
    <div class="max-w-3xl">
      <?php rba_breadcrumbs($trail); ?>
      <?php if ($eyebrow) : ?>
        <p class="eyebrow mt-8 flex items-center gap-3 text-brand-400"><span class="h-px w-8 bg-brand-400" aria-hidden="true"></span><?php echo esc_html($eyebrow); ?></p>
      <?php endif; ?>
      <h1 class="js-split mt-4 text-4xl leading-[1.08] font-bold sm:text-5xl lg:text-[3.4rem]"><?php echo esc_html($title); ?></h1>
      <?php if ($intro) : ?>
        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/75" data-reveal><?php echo esc_html($intro); ?></p>
      <?php endif; ?>
      <?php if ($actions) : ?>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row" data-reveal>
          <a href="<?php echo esc_url(isset($args['cta_url']) ? $args['cta_url'] : rba_contact_url()); ?>" class="btn btn-primary">
            <?php echo rba_icon('calendar', 'h-5 w-5'); ?>
            <?php echo esc_html(isset($args['cta_label']) ? $args['cta_label'] : 'Book a Consultation'); ?>
            <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?>
          </a>
          <a href="tel:<?php echo esc_attr(rba_company()['phone_e164']); ?>" class="btn border-2 border-white/40 text-white hover:border-white hover:bg-white hover:text-navy-900 focus-visible:outline-white">
            <?php echo rba_icon('phone', 'h-4 w-4'); ?>
            <?php echo esc_html(rba_company()['phone']); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($image) : ?>
      <div class="js-img-reveal relative overflow-hidden rounded-[1.75rem] shadow-2xl">
        <?php echo wp_get_attachment_image($image, 'large', false, array('class' => 'aspect-[4/3] w-full object-cover', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 45vw, 100vw')); ?>
      </div>
    <?php endif; ?>
  </div>
</header>

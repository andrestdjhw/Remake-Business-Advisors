<?php
/**
 * Sección de preguntas frecuentes (acordeón <details> exclusivo).
 * Args: faqs (array q/a, por defecto las del home), title, intro, bg.
 */
$faqs = isset($args['faqs']) ? $args['faqs'] : rba_home_faqs();
$title = isset($args['title']) ? $args['title'] : 'Frequently Asked Questions';
$intro = isset($args['intro']) ? $args['intro'] : 'Clear answers about how we work, who we help, and how to get started.';
$bg = isset($args['bg']) ? $args['bg'] : 'bg-white';
$company = rba_company();
?>
  <section class="<?php echo esc_attr($bg); ?> py-20 lg:py-28" aria-labelledby="faq-title">
    <div class="mx-auto grid max-w-site gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-16 lg:px-10">
      <div class="lg:col-span-4">
        <div class="lg:sticky lg:top-[calc(var(--wp-admin--admin-bar--height,0px)_+_7rem)]">
          <p class="eyebrow" data-reveal>FAQs</p>
          <h2 id="faq-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl"><?php echo esc_html($title); ?></h2>
          <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal><?php echo esc_html($intro); ?></p>

          <div class="mt-8 rounded-2xl bg-cream p-6 ring-1 ring-brand-100" data-reveal>
            <p class="font-heading text-base font-semibold text-navy-900">Still have questions?</p>
            <p class="mt-1 text-sm leading-relaxed text-ink/70">Talk with our team and we&rsquo;ll help you find the right next step for your business.</p>
            <div class="mt-5 flex flex-wrap gap-3">
              <a href="<?php echo esc_url(rba_contact_url()); ?>" class="btn btn-primary px-5">Contact Us</a>
              <a href="tel:<?php echo esc_attr($company['phone_e164']); ?>" class="btn btn-outline px-5">
                <?php echo rba_icon('phone', 'h-4 w-4'); ?>
                <?php echo esc_html($company['phone']); ?>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="rba-faq lg:col-span-8">
        <?php foreach ($faqs as $i => $faq) : ?>
          <details class="rba-faq__item" name="rba-faq" data-reveal <?php echo $i === 0 ? 'open' : ''; ?>>
            <summary class="rba-faq__q">
              <span class="rba-faq__num" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
              <span class="flex-1"><?php echo esc_html($faq['q']); ?></span>
              <span class="rba-faq__icon" aria-hidden="true"><?php echo rba_icon('plus', 'h-5 w-5'); ?></span>
            </summary>
            <div class="rba-faq__a">
              <p><?php echo esc_html($faq['a']); ?></p>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:font-semibold focus:text-navy-900 focus:shadow-lg">Skip to content</a>

    <!-- Topbar: contacto a la izquierda; ubicación y redes a la derecha. Se desplaza con la página (el navbar queda fijo). -->
    <?php $company = rba_company(); ?>
    <div class="bg-navy-950 text-white/80">
      <div class="mx-auto flex h-10 max-w-site items-center justify-between gap-4 px-4 text-[13px] sm:px-6 lg:px-10">
        <ul class="flex items-center gap-4 sm:gap-6" aria-label="Contact">
          <li>
            <a href="tel:<?php echo esc_attr($company['phone_e164']); ?>" class="inline-flex items-center gap-2 transition-colors hover:text-brand-300">
              <?php echo rba_icon('phone', 'h-3.5 w-3.5 text-brand-400'); ?>
              <span><?php echo esc_html($company['phone']); ?></span>
            </a>
          </li>
          <li>
            <a href="mailto:<?php echo esc_attr($company['email']); ?>" class="inline-flex items-center gap-2 transition-colors hover:text-brand-300">
              <?php echo rba_icon('mail', 'h-3.5 w-3.5 text-brand-400'); ?>
              <span class="sr-only sm:not-sr-only"><?php echo esc_html($company['email']); ?></span>
            </a>
          </li>
        </ul>

        <div class="flex items-center gap-4 sm:gap-5">
          <a href="<?php echo esc_url($company['maps_url']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-brand-300">
            <?php echo rba_icon('mapPin', 'h-3.5 w-3.5 text-brand-400'); ?>
            <span class="hidden md:inline"><?php echo esc_html(rba_company_address_line()); ?></span>
            <span class="md:hidden"><?php echo esc_html($company['locality'] . ', ' . $company['region']); ?></span>
            <span class="sr-only">(opens Google Maps in a new tab)</span>
          </a>

          <?php $socials = array_filter(rba_social_links(), function ($s) { return in_array($s['network'], array('facebook', 'instagram'), true); }); ?>
          <?php if ($socials) : ?>
            <span class="h-4 w-px bg-white/20" aria-hidden="true"></span>
            <ul class="flex items-center gap-1" aria-label="Social media">
              <?php foreach ($socials as $social) : ?>
                <li>
                  <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer" class="flex h-7 w-7 items-center justify-center rounded-full transition-colors hover:bg-brand-500 hover:text-white">
                    <?php echo rba_icon($social['network'], 'h-3.5 w-3.5'); ?>
                    <span class="sr-only"><?php echo esc_html($social['label']); ?> (opens in a new tab)</span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Navbar (React). El contenido interno es el respaldo mientras carga el JS. -->
    <div id="rba-navbar" class="sticky top-[var(--wp-admin--admin-bar--height,0px)] z-50">
      <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex h-20 max-w-site items-center px-4 sm:px-6 lg:px-10">
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php if (rba_logo_url()) : ?>
              <img src="<?php echo esc_url(rba_logo_url()); ?>" alt="Remake Business Advisors" class="h-[55px] w-auto" width="165" height="55">
            <?php else : ?>
              <span class="font-heading text-xl font-bold text-navy-900">Remake Business Advisors</span>
            <?php endif; ?>
          </a>
        </div>
      </header>
    </div>

<?php
/**
 * Página de inicio.
 * Estructura según brief §05/§6.1: Hero → a quién ayudamos → servicios → proceso → equipo → BCA → CTA.
 */

get_header();

$contact_url = rba_contact_url();

$highlights = array(
  array('icon' => 'users', 'title' => 'Hispanic-Focused', 'text' => 'Guidance in your language, with your context and community in mind.'),
  array('icon' => 'wrench', 'title' => 'Practical Solutions', 'text' => 'Tools, processes, and structures for real business challenges.'),
  array('icon' => 'barChart', 'title' => 'Structure for Growth', 'text' => 'Clear priorities and decisions to move your business forward.'),
  array('icon' => 'graduation', 'title' => 'Education & Training', 'text' => 'Build your knowledge and leadership through our programs.'),
);

// Proceso sugerido en el brief: pendiente de validación por Remake antes de presentarlo como metodología oficial.
$process = array(
  array('icon' => 'message', 'title' => 'Understanding your <em>business</em>', 'text' => 'We start by listening: your goals, your challenges, and how your business runs today.'),
  array('icon' => 'search', 'title' => 'A clear <em>diagnosis</em>', 'text' => 'We review the key areas of your business to identify gaps and opportunities.'),
  array('icon' => 'map', 'title' => 'An action <em>roadmap</em>', 'text' => 'We define clear priorities and a realistic plan of next steps.'),
  array('icon' => 'compass', 'title' => 'Guidance &amp; <em>implementation</em>', 'text' => 'We support you as you put the plan into action and adjust along the way.'),
);
?>

<main id="main">

  <!-- ===================== HERO ===================== -->
  <section class="relative overflow-hidden bg-cream" aria-labelledby="hero-title">
    <div class="pointer-events-none absolute -top-40 -right-40 h-[36rem] w-[36rem] rounded-full border-[3rem] border-brand-100/70" data-parallax="30" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-site items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:gap-8 lg:px-10 lg:py-24">
      <div class="border-l-4 border-brand-500 pl-6 sm:pl-8" data-hero-bar>
        <h1 id="hero-title" class="js-split text-4xl leading-[1.1] font-bold tracking-tight text-navy-900 sm:text-5xl lg:text-6xl" data-split="hero">
          Your Business,<br>
          <span class="font-semibold text-brand-500 italic">Reimagined</span><br>
          for Success
        </h1>
        <p class="mt-6 max-w-xl text-lg leading-relaxed text-ink/80" data-hero-in>
          We help entrepreneurs and business owners, especially in the Hispanic community, diagnose, structure, and grow their companies through practical consulting and business education.
        </p>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap" data-hero-in>
          <a href="<?php echo esc_url($contact_url); ?>" class="btn btn-primary">
            <?php echo rba_icon('calendar', 'h-5 w-5'); ?>
            Book a Consultation
            <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?>
          </a>
          <a href="<?php echo esc_url(home_url('/services/')); ?>" class="btn btn-outline">Explore Our Services</a>
        </div>
      </div>

      <div class="relative mx-auto w-full max-w-md lg:max-w-none" data-hero-media>
        <div class="absolute -inset-4 rounded-[2.5rem] bg-gradient-to-br from-brand-400 to-brand-500 opacity-90 sm:-inset-6" style="clip-path: polygon(30% 0, 100% 0, 100% 100%, 0 100%);" data-hero-backdrop aria-hidden="true"></div>
        <?php $hero_slides = rba_hero_slide_ids(); ?>
        <?php if (count($hero_slides) > 1) : ?>
          <!-- Slideshow del hero (src/scripts/heroSlideshow.js). Imágenes en Personalizar > Remake: Home y redes. -->
          <div class="relative overflow-hidden rounded-[2rem] shadow-2xl" data-slideshow aria-roledescription="carousel" aria-label="Remake workshops and events">
            <div class="relative aspect-[4/3] lg:aspect-[6/5]" data-hero-img>
              <?php foreach ($hero_slides as $i => $slide_id) : ?>
                <div class="rba-slide <?php echo $i === 0 ? 'is-active' : ''; ?>" data-slide role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr(($i + 1) . ' of ' . count($hero_slides)); ?>" <?php echo $i === 0 ? '' : 'aria-hidden="true"'; ?>>
                  <?php echo wp_get_attachment_image($slide_id, 'large', false, array(
                    'class' => 'h-full w-full object-cover object-[40%_center]',
                    'sizes' => '(min-width: 1024px) 50vw, 100vw',
                    'loading' => $i === 0 ? 'eager' : 'lazy',
                    'fetchpriority' => $i === 0 ? 'high' : 'low',
                    'decoding' => 'async',
                  )); ?>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="absolute bottom-4 left-4 z-10 flex items-center gap-1 rounded-full bg-navy-950/45 py-1 pr-1 pl-2 ring-1 ring-white/25 backdrop-blur-md">
              <?php foreach ($hero_slides as $i => $slide_id) : ?>
                <button type="button" class="rba-dot <?php echo $i === 0 ? 'is-active' : ''; ?>" data-slide-dot <?php echo $i === 0 ? 'aria-current="true"' : ''; ?>>
                  <span class="rba-dot__track" aria-hidden="true"><span class="rba-dot__fill"></span></span>
                  <span class="sr-only">Show slide <?php echo (int) $i + 1; ?></span>
                </button>
              <?php endforeach; ?>
              <button type="button" class="rba-slide-toggle" data-slide-toggle>
                <svg class="rba-slide-toggle__pause h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                <svg class="rba-slide-toggle__play h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 4.5v15a1 1 0 0 0 1.5.86l12-7.5a1 1 0 0 0 0-1.72l-12-7.5A1 1 0 0 0 7 4.5Z"/></svg>
                <span class="sr-only" data-slide-toggle-label>Pause slideshow</span>
              </button>
            </div>
          </div>
        <?php else : ?>
          <div class="relative overflow-hidden rounded-[2rem] shadow-2xl">
            <?php echo rba_image('rba_hero_image', 'aspect-[4/3] w-full object-cover object-[40%_center] lg:aspect-[6/5]', 'large', array('loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 50vw, 100vw', 'data-hero-img' => '')); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ===================== A QUIÉN AYUDAMOS ===================== -->
  <section aria-label="Why Remake" class="relative z-10 -mt-8 px-4 sm:px-6 lg:px-10">
    <ul class="mx-auto grid max-w-site gap-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-lg sm:grid-cols-2 lg:grid-cols-4 lg:gap-0 lg:divide-x lg:divide-gray-200 lg:p-8">
      <?php foreach ($highlights as $item) : ?>
        <li class="flex gap-4 lg:px-6 lg:first:pl-0 lg:last:pr-0" data-reveal>
          <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-500">
            <?php echo rba_icon($item['icon'], 'h-6 w-6'); ?>
          </span>
          <div>
            <h2 class="text-base font-semibold text-navy-900"><?php echo esc_html($item['title']); ?></h2>
            <p class="mt-1 text-sm leading-relaxed text-ink/70"><?php echo esc_html($item['text']); ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <!-- ===================== SERVICIOS (carrusel; src/scripts/carousel.js) ===================== -->
  <section class="overflow-hidden py-20 lg:py-28" aria-labelledby="services-title">
    <div class="mx-auto flex max-w-site flex-col gap-8 px-4 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Our Services</p>
        <h2 id="services-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Comprehensive Solutions for Entrepreneurs and Business Owners</h2>
        <p class="mt-4 text-lg text-ink/75" data-reveal>From business structure and strategic planning to hands-on advisory and leadership training, we offer the support you need to run a stronger, better-organized business.</p>
      </div>
      <div class="flex shrink-0 items-center gap-3" data-reveal>
        <button type="button" class="rba-carousel__btn" data-carousel-prev aria-controls="services-track">
          <?php echo rba_icon('arrowRight', 'h-5 w-5 rotate-180'); ?>
          <span class="sr-only">Previous services</span>
        </button>
        <button type="button" class="rba-carousel__btn" data-carousel-next aria-controls="services-track">
          <?php echo rba_icon('arrowRight', 'h-5 w-5'); ?>
          <span class="sr-only">Next services</span>
        </button>
      </div>
    </div>

    <div class="rba-carousel mt-12" data-carousel role="region" aria-roledescription="carousel" aria-labelledby="services-title">
      <ul id="services-track" class="rba-carousel__track" data-carousel-track tabindex="0">
        <?php $services = rba_services(); ?>
        <?php foreach ($services as $i => $service) : ?>
          <li class="rba-carousel__item" data-reveal aria-roledescription="slide" aria-label="<?php echo esc_attr(($i + 1) . ' of ' . count($services)); ?>">
            <?php get_template_part('template-parts/service-card', null, array('service' => $service)); ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="mx-auto mt-10 max-w-site px-4 sm:px-6 lg:px-10" aria-hidden="true">
        <div class="h-1 overflow-hidden rounded-full bg-gray-200">
          <div class="h-full origin-left rounded-full bg-brand-500 transition-transform duration-300" data-carousel-progress></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== PROCESO (columna fija + tarjetas apiladas) ===================== -->
  <section class="relative isolate bg-navy-900 py-24 text-white lg:py-32" aria-labelledby="process-title">
    <div class="rba-grid-pattern" aria-hidden="true"></div>
    <div class="rba-dots-pattern" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-site gap-14 px-4 sm:px-6 lg:grid-cols-12 lg:px-10">

      <!-- Columna fija -->
      <div class="lg:col-span-5">
        <div class="lg:sticky lg:top-[calc(var(--wp-admin--admin-bar--height,0px)_+_7rem)]">
          <p class="eyebrow flex items-center gap-3 text-brand-400" data-reveal><span class="h-px w-8 bg-brand-400" aria-hidden="true"></span>How We Work</p>
          <h2 id="process-title" class="js-split mt-5 text-[clamp(2rem,3.6vw,3.25rem)] leading-[1.05] font-bold">
            From <span class="rba-strike">guesswork</span> to a <em class="font-semibold text-brand-400">clear path</em> for your business.
          </h2>
          <p class="mt-6 max-w-lg text-lg leading-relaxed text-white/70" data-reveal>
            A practical, personalized process designed to <strong class="rba-marker font-semibold text-white">understand your business, define the right priorities,</strong> and help you move forward with confidence.
          </p>

          <div class="js-img-reveal relative mt-10 hidden aspect-[4/3] overflow-hidden rounded-[1.75rem] lg:block">
            <?php echo rba_image('rba_process_image', 'h-full w-full object-cover', 'large', array('loading' => 'lazy', 'sizes' => '40vw', 'data-inner-parallax' => '')); ?>
            <span class="absolute bottom-4 left-4 inline-flex items-center gap-2 rounded-full bg-navy-950/70 px-3 py-1.5 font-heading text-xs font-medium text-white backdrop-blur" aria-hidden="true">
              <span class="h-2 w-2 rounded-full bg-brand-400"></span> Remake workshop
            </span>
          </div>
        </div>
      </div>

      <!-- Tarjetas apiladas -->
      <div class="lg:col-span-7">
        <p class="text-2xl text-white/80 italic md:text-3xl" data-reveal>Our process, step by step:</p>

        <ol class="mt-10 grid gap-6 pb-4">
          <?php foreach ($process as $i => $step) : ?>
            <li class="rba-stack-card js-stack-card" style="--i:<?php echo (int) $i; ?>">
              <div class="flex items-start justify-between gap-6">
                <span class="rba-stack-card__num" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
                <span class="rba-stack-card__art rba-draw" aria-hidden="true"><?php echo rba_icon($step['icon'], 'h-full w-full', true); ?></span>
              </div>
              <div class="mt-8">
                <h3 class="text-[clamp(1.5rem,2.4vw,2.1rem)] leading-[1.12] font-bold text-navy-900">
                  <span class="sr-only">Step <?php echo (int) $i + 1; ?>: </span><?php echo wp_kses($step['title'], array('em' => array())); ?>
                </h3>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-ink/75"><?php echo esc_html($step['text']); ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>
    </div>
  </section>

  <!-- ===================== EQUIPO ===================== -->
  <section class="py-20 lg:py-24" aria-labelledby="team-title">
    <div class="mx-auto grid max-w-site items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-10">
      <div>
        <p class="eyebrow" data-reveal>Our Team</p>
        <h2 id="team-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">A Team Committed to Your Success</h2>
        <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>A group of business consultants and educators with a shared mission: helping entrepreneurs build stronger, more structured, and lasting businesses.</p>
        <a href="<?php echo esc_url(home_url('/our-team/')); ?>" class="btn btn-outline mt-8" data-reveal>
          Meet Our Team
          <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?>
        </a>
      </div>
      <?php $team_photos = rba_team_photo_ids(); ?>
      <?php if ($team_photos) : ?>
        <ul class="grid grid-cols-3 gap-4 sm:grid-cols-4 sm:gap-6">
          <?php foreach ($team_photos as $i => $photo_id) : ?>
            <li class="relative hover:z-10 <?php echo $i % 2 ? 'sm:translate-y-6' : ''; ?>" data-reveal="pop">
              <!-- Retrato con efecto "glass" al pasar el mouse (estilos .rba-glass en src/index.css) -->
              <figure class="rba-glass">
                <div class="rba-glass__orb">
                  <?php echo wp_get_attachment_image($photo_id, 'medium', false, array(
                    'class' => 'aspect-square w-full object-cover',
                    'loading' => 'lazy',
                    'sizes' => '(min-width: 1024px) 150px, 30vw',
                  )); ?>
                  <span class="rba-glass__shine" aria-hidden="true"></span>
                </div>
                <figcaption class="rba-glass__label" aria-hidden="true"><?php echo esc_html(get_the_title($photo_id)); ?></figcaption>
              </figure>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        <?php echo rba_image('rba_team_image', 'aspect-[16/10] w-full rounded-2xl object-cover shadow-xl', 'large', array('loading' => 'lazy')); ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- ===================== BUSINESS COACHING ACADEMY ===================== -->
  <section class="relative overflow-hidden bg-navy-900 text-white" aria-labelledby="bca-title">
    <div class="pointer-events-none absolute -right-32 -bottom-48 h-96 w-96 rounded-full border-[2.5rem] border-brand-500/25" data-parallax="-40" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-site items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-10 lg:py-24">
      <div class="js-img-reveal overflow-hidden rounded-2xl shadow-2xl">
        <?php echo rba_image('rba_bca_image', 'aspect-[4/3] w-full object-cover', 'large', array('loading' => 'lazy', 'sizes' => '(min-width: 1024px) 50vw, 100vw')); ?>
      </div>

      <div>
        <p class="font-heading text-xs font-semibold tracking-[0.25em] text-brand-400 uppercase" data-reveal>Featured Program</p>
        <h2 id="bca-title" class="js-split mt-3 text-3xl font-bold sm:text-4xl">Business Coaching Academy</h2>
        <p class="mt-4 text-lg leading-relaxed text-white/80" data-reveal>A comprehensive training program for entrepreneurs and business leaders who want the knowledge, tools, and support to lead, manage, and grow their companies with confidence.</p>
        <ul class="mt-6 space-y-3 text-white/85" data-reveal>
          <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>Designed for business owners and leaders who want more structure</li>
          <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>A structured, seven-module learning path</li>
          <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>Practical tools you can apply in your business</li>
        </ul>
        <a href="<?php echo esc_url(rba_service_url('business-coaching-academy')); ?>" class="btn btn-primary mt-8" data-reveal>
          Explore the Academy
          <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== PREGUNTAS FRECUENTES / SEDE / CTA (template-parts) ===================== -->
  <?php get_template_part('template-parts/faq'); ?>
  <?php get_template_part('template-parts/hq-map'); ?>
  <?php get_template_part('template-parts/cta-band'); ?>

</main>

<?php get_footer();

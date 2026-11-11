<?php
/**
 * About Us (brief §6.2): quiénes somos, misión y visión, valores, cómo trabajamos, equipo y CTA.
 * Sin cifras ni credenciales sin validar ("50+ years", "15,000 trained", CPAs).
 */

get_header();

$steps = array(
  array('icon' => 'message', 'title' => 'We listen', 'text' => 'We start by understanding your goals, your challenges, and how your business runs today.'),
  array('icon' => 'search', 'title' => 'We diagnose', 'text' => 'We review the key areas of your business to identify gaps and opportunities.'),
  array('icon' => 'map', 'title' => 'We plan', 'text' => 'We define clear priorities and a realistic plan of next steps with you.'),
  array('icon' => 'graduation', 'title' => 'We teach and guide', 'text' => 'We support you while you implement, and we teach you the tools so you can keep growing.'),
);
?>

<main id="main">
  <?php get_template_part('template-parts/page-hero', null, array(
    'eyebrow' => 'About Us',
    'title' => 'Empowering Businesses, Transforming Futures',
    'intro' => 'We help entrepreneurs and business owners, especially in the Hispanic community, build stronger, more structured companies through practical consulting and business education.',
    'trail' => array(array('About Us', null)),
    'image' => 10,
  )); ?>

  <!-- Quiénes somos -->
  <section class="py-20 lg:py-28" aria-labelledby="who-title">
    <div class="mx-auto grid max-w-site gap-12 px-4 sm:px-6 lg:grid-cols-12 lg:gap-16 lg:px-10">
      <div class="lg:col-span-5">
        <p class="eyebrow" data-reveal>Who We Are</p>
        <h2 id="who-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Consulting and education for entrepreneurs who want to lead with structure.</h2>
      </div>
      <div class="space-y-5 text-lg leading-relaxed text-ink/80 lg:col-span-7">
        <p data-reveal>Remake Business Advisors supports Hispanic entrepreneurs and business owners with practical guidance for the decisions that shape their companies. We help them understand how their business operates today, build the structure they need, and move forward with clarity.</p>
        <p data-reveal>Our work combines one-on-one consulting, including business and financial diagnosis, strategic planning, corporate entity formation, and personalized advisory sessions, with training programs such as the Business Coaching Academy and our course for managers and supervisors.</p>
        <p data-reveal>We serve our clients in Spanish, from our office in Irvine, California, and through phone and Zoom sessions.</p>
      </div>
    </div>
  </section>

  <!-- Misión y visión -->
  <section class="pb-20 lg:pb-28" aria-label="Mission and vision">
    <div class="mx-auto grid max-w-site gap-6 px-4 sm:px-6 md:grid-cols-2 lg:px-10">
      <article class="relative isolate overflow-hidden rounded-[1.75rem] bg-navy-900 p-8 text-white sm:p-10" data-reveal>
        <div class="rba-grid-pattern" aria-hidden="true"></div>
        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 text-brand-400"><?php echo rba_icon('target', 'h-6 w-6'); ?></span>
        <h2 class="mt-6 text-2xl font-bold">Our Mission</h2>
        <p class="mt-3 text-lg leading-relaxed text-white/80">To empower Hispanic entrepreneurs with the tools, strategies, and guidance needed to grow their businesses and achieve lasting success in competitive markets.</p>
      </article>
      <article class="rounded-[1.75rem] bg-brand-100 p-8 sm:p-10" data-reveal>
        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-brand-700"><?php echo rba_icon('compass', 'h-6 w-6'); ?></span>
        <h2 class="mt-6 text-2xl font-bold text-navy-900">Our Vision</h2>
        <p class="mt-3 text-lg leading-relaxed text-ink/80">To transform businesses into industry leaders by providing exceptional support, education, and solutions tailored to the unique needs of Hispanic entrepreneurs.</p>
      </article>
    </div>
  </section>

  <!-- Valores -->
  <section class="bg-cream py-20 lg:py-28" aria-labelledby="values-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>Our Values</p>
        <h2 id="values-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Guiding principles that define how we work</h2>
        <p class="mt-4 text-lg text-ink/75" data-reveal>Our values guide every recommendation we make and every program we teach.</p>
      </div>
      <ul class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-5">
        <?php foreach (rba_values() as $value) : ?>
          <li class="rounded-2xl bg-white p-6 ring-1 ring-brand-100 transition hover:-translate-y-1 hover:shadow-xl" data-reveal>
            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500"><?php echo rba_icon($value['icon'], 'h-6 w-6'); ?></span>
            <h3 class="mt-5 text-lg font-semibold text-navy-900"><?php echo esc_html($value['title']); ?></h3>
            <p class="mt-2 text-sm leading-relaxed text-ink/70"><?php echo esc_html($value['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- Cómo trabajamos -->
  <section class="py-20 lg:py-28" aria-labelledby="method-title">
    <div class="mx-auto max-w-site px-4 sm:px-6 lg:px-10">
      <div class="max-w-3xl">
        <p class="eyebrow" data-reveal>How We Work</p>
        <h2 id="method-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">A practical, personalized approach</h2>
      </div>
      <ol class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($steps as $i => $step) : ?>
          <li class="relative rounded-2xl border border-gray-200 p-6" data-reveal>
            <span class="rba-stack-card__num block text-[3.25rem]" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $i + 1)); ?></span>
            <span class="absolute top-6 right-6 text-brand-500"><?php echo rba_icon($step['icon'], 'h-7 w-7'); ?></span>
            <h3 class="mt-6 text-lg font-semibold text-navy-900"><span class="sr-only">Step <?php echo (int) $i + 1; ?>: </span><?php echo esc_html($step['title']); ?></h3>
            <p class="mt-2 text-sm leading-relaxed text-ink/70"><?php echo esc_html($step['text']); ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </section>

  <!-- Equipo (adelanto) -->
  <section class="bg-cream py-20 lg:py-24" aria-labelledby="about-team-title">
    <div class="mx-auto grid max-w-site items-center gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:px-10">
      <div>
        <p class="eyebrow" data-reveal>Our Team</p>
        <h2 id="about-team-title" class="js-split mt-3 text-3xl font-bold text-navy-900 sm:text-4xl">Business consultants and educators on your side</h2>
        <p class="mt-4 text-lg leading-relaxed text-ink/75" data-reveal>A team with a shared mission: helping entrepreneurs build stronger, more structured, and lasting businesses.</p>
        <a href="<?php echo esc_url(home_url('/our-team/')); ?>" class="btn btn-outline mt-8" data-reveal>Meet Our Team <?php echo rba_icon('arrowRight', 'h-4 w-4'); ?></a>
      </div>
      <ul class="grid grid-cols-4 gap-4 sm:gap-6">
        <?php foreach (rba_team_members() as $i => $member) : ?>
          <li class="relative hover:z-10 <?php echo $i % 2 ? 'translate-y-4' : ''; ?>" data-reveal="pop">
            <figure class="rba-glass">
              <div class="rba-glass__orb">
                <?php echo wp_get_attachment_image($member['photo'], 'medium', false, array('class' => 'aspect-square w-full object-cover', 'loading' => 'lazy', 'sizes' => '140px', 'alt' => $member['name'])); ?>
                <span class="rba-glass__shine" aria-hidden="true"></span>
              </div>
              <figcaption class="rba-glass__label" aria-hidden="true"><?php echo esc_html($member['name']); ?></figcaption>
            </figure>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <?php get_template_part('template-parts/cta-band'); ?>
</main>

<?php get_footer();

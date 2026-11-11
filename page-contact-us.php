<?php
/**
 * Contact Us (brief §6.6): formulario progresivo (React) + datos de contacto + FAQ + ubicación.
 */

get_header();

$company = rba_company();
$requested = isset($_GET['service']) ? sanitize_key(wp_unslash($_GET['service'])) : '';
$form_props = array(
  'endpoint' => esc_url_raw(rest_url('rba/v1/contact')),
  'nonce' => wp_create_nonce('rba_contact'),
  'options' => rba_contact_form_options(),
  'service' => rba_service_by_slug($requested) ? $requested : '',
  'privacyUrl' => get_privacy_policy_url(),
  'termsUrl' => rba_terms_url(),
  'phone' => $company['phone'],
  'phoneHref' => 'tel:' . $company['phone_e164'],
  'homeUrl' => home_url('/'),
);
?>

<main id="main">
  <?php get_template_part('template-parts/page-hero', null, array(
    'eyebrow' => 'Contact Us',
    'title' => 'Tell Us About Your Business',
    'intro' => 'Reach out to schedule a consultation or ask a question. We will review your information and contact you to find the right next step.',
    'trail' => array(array('Contact Us', null)),
  )); ?>

  <section class="py-16 lg:py-24" aria-label="Contact form and information">
    <div class="mx-auto grid max-w-site gap-10 px-4 sm:px-6 lg:grid-cols-12 lg:gap-12 lg:px-10">

      <!-- Datos de contacto -->
      <aside class="space-y-6 lg:col-span-4" aria-label="Contact information">
        <div class="rounded-[1.75rem] bg-cream p-8 ring-1 ring-brand-100" data-reveal>
          <h2 class="text-xl font-bold text-navy-900">Get in touch</h2>
          <ul class="mt-6 space-y-5">
            <li class="flex gap-4">
              <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-500 shadow-sm"><?php echo rba_icon('phone', 'h-5 w-5'); ?></span>
              <span><span class="block text-sm text-ink/60">Phone</span><a class="font-semibold text-navy-900 hover:text-brand-700" href="tel:<?php echo esc_attr($company['phone_e164']); ?>"><?php echo esc_html($company['phone']); ?></a></span>
            </li>
            <li class="flex gap-4">
              <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-500 shadow-sm"><?php echo rba_icon('mail', 'h-5 w-5'); ?></span>
              <span class="min-w-0"><span class="block text-sm text-ink/60">Email</span><a class="font-semibold break-all text-navy-900 hover:text-brand-700" href="mailto:<?php echo esc_attr($company['email']); ?>"><?php echo esc_html($company['email']); ?></a></span>
            </li>
            <li class="flex gap-4">
              <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-brand-500 shadow-sm"><?php echo rba_icon('mapPin', 'h-5 w-5'); ?></span>
              <span><span class="block text-sm text-ink/60">Office</span><a class="font-semibold text-navy-900 hover:text-brand-700" href="<?php echo esc_url($company['maps_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($company['street']); ?><br><?php echo esc_html($company['locality'] . ', ' . $company['region'] . ' ' . $company['postal']); ?><span class="sr-only"> (opens Google Maps in a new tab)</span></a></span>
            </li>
          </ul>
        </div>

        <div class="relative isolate overflow-hidden rounded-[1.75rem] bg-navy-900 p-8 text-white" data-reveal>
          <div class="rba-grid-pattern" aria-hidden="true"></div>
          <h2 class="text-xl font-bold">How we can meet</h2>
          <ul class="mt-5 space-y-3 text-white/80">
            <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>By phone</li>
            <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>On Zoom</li>
            <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>In person at our Irvine office</li>
            <li class="flex gap-3"><?php echo rba_icon('check', 'mt-0.5 h-5 w-5 shrink-0 text-brand-400'); ?>Service available in Spanish</li>
          </ul>
        </div>
      </aside>

      <!-- Formulario (React: src/components/ContactForm.js) -->
      <div class="lg:col-span-8">
        <div class="rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-black/5 sm:p-10" data-reveal>
          <div id="rba-contact-form" data-props="<?php echo esc_attr(wp_json_encode($form_props)); ?>">
            <p class="text-ink/70">Loading the form&hellip; If it does not appear, call us at <a class="font-semibold text-brand-700" href="tel:<?php echo esc_attr($company['phone_e164']); ?>"><?php echo esc_html($company['phone']); ?></a> or email <a class="font-semibold text-brand-700" href="mailto:<?php echo esc_attr($company['email']); ?>"><?php echo esc_html($company['email']); ?></a>.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php get_template_part('template-parts/faq', null, array('bg' => 'bg-cream')); ?>
  <?php get_template_part('template-parts/hq-map'); ?>
</main>

<?php get_footer();

<?php
/**
 * Datos compartidos entre las plantillas PHP y los componentes de React.
 * Los slugs /service/... conservan las URLs del sitio actual (brief §05).
 */

function rba_contact_url() {
  return home_url('/contact-us/');
}

function rba_services() {
  return array(
    array(
      'slug' => 'business-diagnosis',
      'group' => 'consulting',
      'tagline' => 'See how your business operates today and what to fix first.',
      'title' => 'Business Diagnosis',
      'icon' => 'clipboard',
      'summary' => 'Get a clear picture of how your business operates today, identify gaps, and prioritize your next steps.',
    ),
    array(
      'slug' => 'personalized-advisory-sessions',
      'group' => 'consulting',
      'tagline' => 'One-on-one guidance for your specific questions and decisions.',
      'title' => 'Personalized Advisory Sessions',
      'icon' => 'message',
      'summary' => 'One-on-one guidance to work through the specific questions and decisions your business is facing.',
    ),
    array(
      'slug' => 'formation-of-corporate-entities',
      'group' => 'consulting',
      'tagline' => 'Choose and set up the right structure for your goals.',
      'title' => 'Formation of Corporate Entities',
      'icon' => 'building',
      'summary' => 'Support choosing and setting up the business structure that fits your goals, in coordination with your legal and tax advisors.',
    ),
    array(
      'slug' => 'strategic-planning',
      'group' => 'consulting',
      'tagline' => 'Turn your vision into a clear, actionable roadmap.',
      'title' => 'Strategic Planning',
      'icon' => 'target',
      'summary' => 'Turn your vision into a clear, actionable roadmap with defined priorities and responsibilities.',
    ),
    array(
      'slug' => 'financial-diagnosis',
      'group' => 'consulting',
      'tagline' => 'Understand your numbers and make better-informed decisions.',
      'title' => 'Financial Diagnosis',
      'icon' => 'lineChart',
      'summary' => 'Understand your numbers so you can improve profitability and make better-informed decisions.',
    ),
    array(
      'slug' => 'course-for-managers-and-supervisors',
      'group' => 'training',
      'tagline' => 'Strengthen leadership, team management, and operations.',
      'title' => 'Course for Managers and Supervisors',
      'icon' => 'briefcase',
      'summary' => 'Training that strengthens leadership, team management, and day-to-day operational performance.',
    ),
    array(
      'slug' => 'business-coaching-academy',
      'group' => 'training',
      'tagline' => 'A practical training program for entrepreneurs and leaders.',
      'title' => 'Business Coaching Academy',
      'icon' => 'graduation',
      'summary' => 'A practical training program for entrepreneurs and business leaders who want to lead and grow with more structure.',
    ),
  );
}

function rba_service_url($slug) {
  return home_url('/service/' . $slug . '/');
}

function rba_nav_items() {
  // Los hijos de "Our Services" alimentan el mega menú: grupo, ícono y descripción corta.
  $services = array_map(function ($service) {
    return array(
      'label' => $service['title'],
      'url' => rba_service_url($service['slug']),
      'icon' => $service['icon'],
      'group' => $service['group'],
      'tagline' => $service['tagline'],
    );
  }, rba_services());

  return array(
    array('label' => 'Home', 'url' => home_url('/')),
    array('label' => 'About Us', 'url' => home_url('/about-us/')),
    array('label' => 'Our Services', 'url' => home_url('/services/'), 'children' => $services),
    array('label' => 'Events', 'url' => 'https://simposiodenegocios.com/', 'external' => true),
    array('label' => 'Our Team', 'url' => home_url('/our-team/')),
    array('label' => 'Contact Us', 'url' => rba_contact_url()),
  );
}

/* Logo: usa el "Logo" del Personalizador si existe; si no, el BrandLogo de la biblioteca de medios. */
function rba_logo_id() {
  static $id = null;
  if ($id !== null) return $id;

  $id = (int) get_theme_mod('custom_logo');
  if (!$id) {
    $found = get_posts(array('post_type' => 'attachment', 'name' => 'brandlogo', 'posts_per_page' => 1, 'fields' => 'ids'));
    $id = $found ? (int) $found[0] : 0;
  }
  return $id;
}

function rba_logo_url($size = 'medium_large') {
  $id = rba_logo_id();
  return $id ? wp_get_attachment_image_url($id, $size) : '';
}

function rba_social_links() {
  $networks = array('linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube');
  $links = array();
  foreach ($networks as $key => $label) {
    $url = get_theme_mod('rba_social_' . $key);
    if ($url) $links[] = array('network' => $key, 'label' => $label, 'url' => esc_url_raw($url));
  }
  return $links;
}

/* Imagen administrable desde el Personalizador; si no hay, muestra un bloque decorativo. */
function rba_image($mod, $class, $size = 'large', $attrs = array()) {
  $id = (int) get_theme_mod($mod);
  if ($id) {
    return wp_get_attachment_image($id, $size, false, array_merge(array('class' => $class), $attrs));
  }
  return '<div class="rba-placeholder ' . esc_attr($class) . '" aria-hidden="true"></div>';
}

/* IDs de imágenes guardados como lista separada por comas en un theme mod. */
function rba_image_ids_mod($mod) {
  $ids = array_filter(array_map('absint', explode(',', (string) get_theme_mod($mod, ''))));
  return array_values(array_filter($ids, 'wp_attachment_is_image'));
}

/* Retratos del equipo para el home: theme mod "rba_team_photos". */
function rba_team_photo_ids() {
  return rba_image_ids_mod('rba_team_photos');
}

/* Slideshow del hero: theme mod "rba_hero_slides"; si está vacío, usa la imagen única del hero. */
function rba_hero_slide_ids() {
  $ids = rba_image_ids_mod('rba_hero_slides');
  if (!$ids && get_theme_mod('rba_hero_image')) $ids = array((int) get_theme_mod('rba_hero_image'));
  return $ids;
}

function rba_react_data() {
  $legal = array();
  if (get_privacy_policy_url()) $legal[] = array('label' => 'Privacy Policy', 'url' => get_privacy_policy_url());
  if (rba_terms_url()) $legal[] = array('label' => 'Terms & Conditions', 'url' => rba_terms_url());

  return array(
    'siteName' => 'Remake Business Advisors',
    'homeUrl' => home_url('/'),
    'logoUrl' => rba_logo_url(),
    'nav' => rba_nav_items(),
    'services' => array_map(function ($service) {
      return array('label' => $service['title'], 'url' => rba_service_url($service['slug']));
    }, rba_services()),
    'cta' => array('label' => 'Book a Consultation', 'url' => rba_contact_url()),
    'servicesUrl' => home_url('/services/'),
    'socials' => rba_social_links(),
    'legal' => $legal,
  );
}

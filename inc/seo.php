<?php
/**
 * SEO técnico y privacidad (revisión de staging del 11-nov-2026, prioridades 1 y 4).
 *
 * - Títulos: separador "|" y portada con servicio + ciudad.
 * - Canonical en la portada (WordPress solo lo imprime en entradas y páginas).
 * - OpenGraph y Twitter Cards en todas las páginas.
 * - Datos estructurados: ProfessionalService (dirección, teléfono, geo) y Person para los socios.
 * - Sitemap de WordPress solo con páginas (sin usuarios, categorías ni entradas).
 * - Sin enumeración de usuarios: archivos de autor redirigidos (routing.php), REST /wp/v2/users y oEmbed sin autor.
 * - /llms.txt generado con las páginas reales.
 */

/* ---------- Títulos ---------- */
add_filter('document_title_separator', function () {
  return '|';
});

add_filter('document_title_parts', function ($parts) {
  if (is_front_page()) {
    return array('title' => 'Business Consulting in Irvine, CA', 'site' => 'Remake Business Advisors');
  }
  unset($parts['tagline']);
  return $parts;
});

/* ---------- URL canónica de la vista actual ---------- */
function rba_current_canonical() {
  if (is_front_page()) return home_url('/');
  if (is_singular()) return wp_get_canonical_url();
  return '';
}

add_action('wp_head', function () {
  // En entradas y páginas WordPress ya imprime rel=canonical (rel_canonical); aquí solo la portada.
  if (is_front_page() && !is_singular()) {
    echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
  }
}, 3);

/* ---------- OpenGraph y Twitter ---------- */
function rba_share_image() {
  // 1) Imagen para compartir del Personalizador (recomendado 1200×630). 2) Primera foto del hero. 3) Logo.
  $id = (int) get_theme_mod('rba_share_image');
  if (!$id) {
    $slides = rba_hero_slide_ids();
    $id = $slides ? (int) $slides[0] : 0;
  }
  if ($id) {
    $src = wp_get_attachment_image_src($id, 'large');
    if ($src) {
      return array('url' => $src[0], 'width' => $src[1], 'height' => $src[2], 'alt' => get_post_meta($id, '_wp_attachment_image_alt', true));
    }
  }
  return array('url' => rba_logo_url(), 'width' => 0, 'height' => 0, 'alt' => 'Remake Business Advisors');
}

add_action('wp_head', function () {
  if (is_404()) return;

  $title = wp_get_document_title();
  $description = rba_meta_description();
  $url = rba_current_canonical();
  $image = rba_share_image();

  $og = array(
    'og:type' => 'website',
    'og:site_name' => 'Remake Business Advisors',
    'og:locale' => 'en_US',
    'og:title' => $title,
    'og:description' => $description,
    'og:url' => $url,
    'og:image' => $image['url'],
    'og:image:width' => $image['width'] ?: '',
    'og:image:height' => $image['height'] ?: '',
    'og:image:alt' => $image['alt'],
  );
  foreach ($og as $property => $content) {
    if ($content === '' || $content === null) continue;
    echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '">' . "\n";
  }

  $twitter = array(
    'twitter:card' => 'summary_large_image',
    'twitter:title' => $title,
    'twitter:description' => $description,
    'twitter:image' => $image['url'],
    'twitter:image:alt' => $image['alt'],
  );
  foreach ($twitter as $name => $content) {
    if ($content === '' || $content === null) continue;
    echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($content) . '">' . "\n";
  }
}, 4);

/* ---------- Datos estructurados ---------- */
function rba_business_schema() {
  $c = rba_company();
  $schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    '@id' => home_url('/#organization'),
    'name' => $c['name'],
    'url' => home_url('/'),
    'logo' => rba_logo_url(),
    'image' => rba_share_image()['url'],
    'description' => 'Business consulting and education that helps entrepreneurs and business owners, especially in the Hispanic community, diagnose, structure, and grow their companies.',
    'telephone' => $c['phone_e164'],
    'email' => $c['email'],
    'address' => array(
      '@type' => 'PostalAddress',
      'streetAddress' => $c['street'],
      'addressLocality' => $c['locality'],
      'addressRegion' => $c['region'],
      'postalCode' => $c['postal'],
      'addressCountry' => $c['country'],
    ),
    'geo' => array('@type' => 'GeoCoordinates', 'latitude' => (string) $c['lat'], 'longitude' => (string) $c['lng']),
    'hasMap' => $c['maps_url'],
    'knowsLanguage' => array('en', 'es'),
  );
  $same_as = array_values(array_filter(array_column(rba_social_links(), 'url')));
  if ($same_as) $schema['sameAs'] = $same_as;
  return $schema;
}

/*
 * Person para los socios. Sin jobTitle hasta confirmar "fundador/cofundador" y credenciales con Laura
 * (correo del 11-nov-2026: no publicar títulos sin verificar).
 */
function rba_partner_schema() {
  $partners = array_filter(rba_team_members(), function ($m) {
    return !empty($m['partner']);
  });
  return array_values(array_map(function ($m) {
    $image = wp_get_attachment_image_url($m['photo'], 'medium');
    return array_filter(array(
      '@context' => 'https://schema.org',
      '@type' => 'Person',
      'name' => $m['name'],
      'url' => home_url('/our-team/#' . $m['slug']),
      'image' => $image ?: null,
      'worksFor' => array('@id' => home_url('/#organization')),
    ));
  }, $partners));
}

add_action('wp_head', function () {
  $graphs = array();
  if (is_front_page() || is_page(array('about-us', 'contact-us'))) $graphs[] = rba_business_schema();
  if (is_page(array('our-team', 'about-us'))) $graphs = array_merge($graphs, rba_partner_schema());
  foreach ($graphs as $graph) {
    echo '<script type="application/ld+json">' . wp_json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
  }
}, 20);

/* ---------- Sitemap de WordPress: solo páginas ---------- */
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
  return in_array($name, array('users', 'taxonomies'), true) ? false : $provider;
}, 10, 2);

add_filter('wp_sitemaps_post_types', function ($post_types) {
  unset($post_types['post']); // El sitio no tiene blog.
  return $post_types;
});

/* ---------- Sin enumeración de usuarios ---------- */
add_filter('rest_endpoints', function ($endpoints) {
  if (!is_user_logged_in()) {
    unset($endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  }
  return $endpoints;
});

add_filter('oembed_response_data', function ($data) {
  unset($data['author_name'], $data['author_url']);
  return $data;
});

/* ---------- /llms.txt con las páginas reales ---------- */
function rba_llms_txt() {
  $c = rba_company();
  $line = function ($title, $url, $desc = '') {
    return '- [' . $title . '](' . $url . ')' . ($desc ? ': ' . $desc : '');
  };

  $pages = array(
    $line('Home', home_url('/'), 'Overview of Remake Business Advisors, our services, how we work, and how to book a consultation.'),
    $line('About Us', home_url('/about-us/'), 'Who we are, our mission, vision, and values.'),
    $line('Our Services', home_url('/services/'), 'All consulting services and training, grouped by type.'),
    $line('Our Team', home_url('/our-team/'), 'The business consultants and educators on our team.'),
    $line('Contact Us', home_url('/contact-us/'), 'Book a consultation by phone, Zoom, or in person.'),
  );
  $services = array_map(function ($s) use ($line) {
    return $line($s['title'], rba_service_url($s['slug']), $s['summary']);
  }, rba_services());
  $legal = array();
  if (get_privacy_policy_url()) $legal[] = $line('Privacy Policy', get_privacy_policy_url());
  if (rba_terms_url()) $legal[] = $line('Terms & Conditions', rba_terms_url());

  $out = array(
    '# Remake Business Advisors',
    '',
    '> Business consulting and education for entrepreneurs and business owners, especially in the Hispanic community. We help clients diagnose, structure, and grow their companies. Services are available in English and Spanish, in person in Irvine, California, and by phone or Zoom.',
    '',
    '- Office: ' . rba_company_address_line(),
    '- Phone: ' . $c['phone'],
    '- Email: ' . $c['email'],
    '',
    '## Pages',
    '',
    implode("\n", $pages),
    '',
    '## Services',
    '',
    implode("\n", $services),
  );
  if ($legal) {
    $out[] = '';
    $out[] = '## Legal';
    $out[] = '';
    $out[] = implode("\n", $legal);
  }
  return implode("\n", $out) . "\n";
}

// Se sirve antes que las redirecciones (prioridad 0). Si existe un archivo físico llms.txt en la raíz
// (p. ej. el que genera Hostinger Tools), el servidor lo entrega primero: hay que desactivarlo en ese plugin.
add_action('template_redirect', function () {
  if (rba_request_path() !== 'llms.txt') return;
  status_header(200);
  header('Content-Type: text/plain; charset=utf-8');
  header('X-Robots-Tag: noindex');
  echo rba_llms_txt();
  exit;
}, 0);

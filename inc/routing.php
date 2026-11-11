<?php
/**
 * Rutas de las páginas internas, redirecciones de URLs antiguas y meta descriptions.
 *
 * Estructura (se conservan los slugs del sitio actual, brief §05):
 *   /about-us/  /services/  /our-team/  /contact-us/   → page-{slug}.php
 *   /service/{slug}/  (páginas hijas de "service")     → template-service.php
 */

/* Las páginas hijas de "service" usan la plantilla de landing de servicio. */
function rba_is_service_landing($post = null) {
  $post = get_post($post);
  if (!$post || $post->post_type !== 'page' || !$post->post_parent) return false;
  $parent = get_post($post->post_parent);
  return $parent && $parent->post_name === 'service' && rba_service_by_slug($post->post_name);
}

function rba_service_template($template) {
  if (is_page() && rba_is_service_landing()) {
    $custom = locate_template('template-service.php');
    if ($custom) return $custom;
  }
  return $template;
}

add_filter('template_include', 'rba_service_template');

/* Redirecciones 301: /service/ → /services/ y perfiles antiguos /team/{slug}/ → /our-team/#{slug}. */
function rba_legacy_redirects() {
  if (is_page('service')) {
    wp_safe_redirect(home_url('/services/'), 301);
    exit;
  }

  // Se revisa la ruta directamente (no solo en 404): WordPress resuelve /team/laura-elena-martinez/
  // como la página de adjunto de la foto con ese nombre y la mandaría al archivo de imagen.
  $path = trim((string) wp_parse_url(isset($_SERVER['REQUEST_URI']) ? wp_unslash($_SERVER['REQUEST_URI']) : '', PHP_URL_PATH), '/');
  $home_path = trim((string) wp_parse_url(home_url(), PHP_URL_PATH), '/');
  if ($home_path !== '' && strpos($path, $home_path . '/') === 0) $path = substr($path, strlen($home_path) + 1);

  if (preg_match('#^team/([a-z0-9-]+)$#', $path, $m)) {
    $slugs = wp_list_pluck(rba_team_members(), 'slug');
    $target = home_url('/our-team/') . (in_array($m[1], $slugs, true) ? '#' . $m[1] : '');
    wp_safe_redirect($target, 301);
    exit;
  }
}

// Prioridad 1: antes de redirect_canonical() y de la redirección de páginas de adjuntos.
add_action('template_redirect', 'rba_legacy_redirects', 1);

/* Sin feeds de comentarios por página (el sitio no usa comentarios). */
remove_action('wp_head', 'feed_links_extra', 3);

/* Meta description única por página (brief §07: titles y descriptions únicos). */
function rba_meta_description() {
  if (is_front_page()) {
    return 'Business consulting and education for entrepreneurs and business owners, especially in the Hispanic community. Diagnose, structure, and grow your company with Remake Business Advisors.';
  }
  if (is_page()) {
    $post = get_post();
    if (rba_is_service_landing($post)) {
      $service = rba_service_by_slug($post->post_name);
      return $service['summary'];
    }
    $map = array(
      'about-us' => 'Learn about Remake Business Advisors: our mission, vision, and values, and how we help Hispanic entrepreneurs diagnose, structure, and grow their businesses.',
      'services' => 'Explore our consulting services and training programs: Business Diagnosis, Advisory Sessions, Corporate Entity Formation, Strategic Planning, Financial Diagnosis, and more.',
      'our-team' => 'Meet the Remake Business Advisors team of business consultants and educators committed to helping entrepreneurs build stronger, more structured businesses.',
      'contact-us' => 'Contact Remake Business Advisors in Irvine, California. Book a consultation by phone, Zoom, or in person and tell us about your business.',
      'privacy-policy' => 'How Remake Business Advisors collects, uses, and protects your personal information.',
      'terms-and-conditions' => 'Terms and conditions for using the Remake Business Advisors website.',
    );
    if (isset($map[$post->post_name])) return $map[$post->post_name];
    if (has_excerpt($post)) return wp_strip_all_tags(get_the_excerpt($post));
  }
  return '';
}

function rba_print_meta_description() {
  $description = rba_meta_description();
  if ($description) {
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
  }
}

add_action('wp_head', 'rba_print_meta_description', 2);

/* Breadcrumbs visibles + datos estructurados BreadcrumbList. $trail = [[label, url|null], ...] */
function rba_breadcrumbs($trail) {
  $items = array_merge(array(array('Home', home_url('/'))), $trail);

  $schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array(),
  );

  echo '<nav aria-label="Breadcrumb" class="text-sm"><ol class="flex flex-wrap items-center gap-2 text-white/70">';
  foreach ($items as $i => $item) {
    list($label, $url) = $item;
    $last = $i === count($items) - 1;
    $schema['itemListElement'][] = array_filter(array(
      '@type' => 'ListItem',
      'position' => $i + 1,
      'name' => $label,
      'item' => $url ?: null,
    ));
    echo '<li class="flex items-center gap-2">';
    if ($i > 0) echo '<span aria-hidden="true" class="text-white/40">/</span>';
    if ($last || !$url) {
      echo '<span' . ($last ? ' aria-current="page" class="text-white"' : '') . '>' . esc_html($label) . '</span>';
    } else {
      echo '<a class="transition-colors hover:text-brand-300" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
    }
    echo '</li>';
  }
  echo '</ol></nav>';
  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

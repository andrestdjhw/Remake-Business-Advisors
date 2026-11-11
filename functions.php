<?php

require_once get_theme_file_path('/inc/site-data.php');
require_once get_theme_file_path('/inc/icons.php');
require_once get_theme_file_path('/inc/customizer.php');
require_once get_theme_file_path('/inc/faqs.php');
require_once get_theme_file_path('/inc/content.php');
require_once get_theme_file_path('/inc/pages-content.php');
require_once get_theme_file_path('/inc/routing.php');
require_once get_theme_file_path('/inc/contact-endpoint.php');

function rba_load_assets() {
  $asset = include get_theme_file_path('/build/index.asset.php');

  wp_enqueue_style('rba-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600&family=Poppins:ital,wght@0,500;0,600;0,700;1,600&display=swap', array(), null);
  wp_enqueue_style('rba-main-css', get_theme_file_uri('/build/index.css'), array('rba-fonts'), filemtime(get_theme_file_path('/build/index.css')));

  wp_enqueue_script('rba-main-js', get_theme_file_uri('/build/index.js'), $asset['dependencies'], $asset['version'], true);
  wp_add_inline_script('rba-main-js', 'window.rbaData = ' . wp_json_encode(rba_react_data()) . ';', 'before');
}

add_action('wp_enqueue_scripts', 'rba_load_assets');

function rba_resource_hints($urls, $relation) {
  if ($relation === 'preconnect') {
    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array('href' => 'https://fonts.gstatic.com', 'crossorigin');
  }
  return $urls;
}

add_filter('wp_resource_hints', 'rba_resource_hints', 10, 2);

/*
 * Activa las transiciones antes del primer pintado (evita que el contenido parpadee).
 * Si el usuario pidió movimiento reducido no se activan; si el JS no carga en 3.5 s, se revierten.
 */
function rba_animation_flag() {
  echo "<script>(function(d){if(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)return;d.classList.add('rba-anim');setTimeout(function(){if(!window.rbaAnimReady)d.classList.remove('rba-anim')},3500)})(document.documentElement);</script>\n";
}

add_action('wp_head', 'rba_animation_flag', 1);

/*
 * Google Consent Mode v2: todo "denied" por defecto antes de cualquier script de analítica/marketing.
 * Si el visitante ya eligió (localStorage "rba-consent", ver src/components/CookieConsent.js), se aplica de inmediato.
 * Cualquier etiqueta de GA4 / Tag Manager debe cargarse DESPUÉS de este bloque.
 */
function rba_consent_defaults() {
  echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}"
    . "gtag('consent','default',{ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied',analytics_storage:'denied',functionality_storage:'granted',security_storage:'granted',wait_for_update:500});"
    . "try{var c=JSON.parse(localStorage.getItem('rba-consent')||'null');if(c&&c.v===1){gtag('consent','update',{analytics_storage:c.analytics?'granted':'denied',ad_storage:c.marketing?'granted':'denied',ad_user_data:c.marketing?'granted':'denied',ad_personalization:c.marketing?'granted':'denied'});}}catch(e){}"
    . "</script>\n";
}

add_action('wp_head', 'rba_consent_defaults', 0);

function rba_add_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('custom-logo');
  add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
}

add_action('after_setup_theme', 'rba_add_support');

/* Datos estructurados de la organización en la página de inicio. */
function rba_organization_schema() {
  if (!is_front_page()) return;

  $schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Remake Business Advisors',
    'url' => home_url('/'),
    'logo' => rba_logo_url(),
    'description' => 'Business consulting and education that helps entrepreneurs and business owners, especially in the Hispanic community, diagnose, structure, and grow their companies.',
    'telephone' => rba_company()['phone_e164'],
    'address' => array(
      '@type' => 'PostalAddress',
      'streetAddress' => rba_company()['street'],
      'addressLocality' => rba_company()['locality'],
      'addressRegion' => rba_company()['region'],
      'postalCode' => rba_company()['postal'],
      'addressCountry' => rba_company()['country'],
    ),
  );

  $same_as = array_values(array_filter(array_column(rba_social_links(), 'url')));
  if ($same_as) $schema['sameAs'] = $same_as;

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

add_action('wp_head', 'rba_organization_schema');

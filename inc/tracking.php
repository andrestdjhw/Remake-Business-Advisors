<?php
/**
 * Etiquetas de medición (pedido de Carlos, 11-nov-2026): GTM, GA4, Search Console y Microsoft Clarity.
 *
 * Los IDs se configuran en Apariencia > Personalizar > "Remake: Medición". Vacío = no se imprime nada.
 * Orden en el <head>: Consent Mode (prioridad 0, functions.php) → GTM / GA4 → resto.
 * - GA4 directo solo si NO hay GTM (con GTM, GA4 se configura dentro del contenedor).
 * - Clarity solo se carga si el visitante aceptó cookies de analítica (CookieConsent.js).
 */

function rba_tracking_ids() {
  return array(
    'gtm' => (string) get_theme_mod('rba_gtm_id', ''),
    'ga4' => (string) get_theme_mod('rba_ga4_id', ''),
    'clarity' => (string) get_theme_mod('rba_clarity_id', ''),
    'gsc' => (string) get_theme_mod('rba_gsc_verification', ''),
  );
}

function rba_tracking_customize($wp_customize) {
  $wp_customize->add_section('rba_tracking', array('title' => 'Remake: Medición', 'priority' => 31));

  $fields = array(
    'rba_gtm_id' => array('Google Tag Manager (GTM-XXXXXXX)', '/^GTM-[A-Z0-9]+$/'),
    'rba_ga4_id' => array('Google Analytics 4 (G-XXXXXXXXXX) — solo si no se usa GTM', '/^G-[A-Z0-9]+$/'),
    'rba_clarity_id' => array('Microsoft Clarity (ID del proyecto)', '/^[a-z0-9]+$/'),
    'rba_gsc_verification' => array('Search Console: código de verificación (solo el valor de "content")', '/^[A-Za-z0-9_-]+$/'),
  );

  foreach ($fields as $id => $field) {
    list($label, $pattern) = $field;
    $wp_customize->add_setting($id, array(
      'sanitize_callback' => function ($value) use ($pattern) {
        $value = trim((string) $value);
        if ($pattern === '/^[a-z0-9]+$/') $value = strtolower($value);
        else if ($pattern !== '/^[A-Za-z0-9_-]+$/') $value = strtoupper($value);
        return preg_match($pattern, $value) ? $value : '';
      },
    ));
    $wp_customize->add_control($id, array('label' => $label, 'section' => 'rba_tracking', 'type' => 'text'));
  }
}

add_action('customize_register', 'rba_tracking_customize');

add_action('wp_head', function () {
  $ids = rba_tracking_ids();

  if ($ids['gsc']) {
    echo '<meta name="google-site-verification" content="' . esc_attr($ids['gsc']) . '">' . "\n";
  }

  if ($ids['gtm']) {
    $gtm = esc_js($ids['gtm']);
    echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtm}');</script>\n";
  } elseif ($ids['ga4']) {
    $ga4 = esc_attr($ids['ga4']);
    echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga4 . '"></script>' . "\n";
    echo "<script>gtag('js',new Date());gtag('config','" . esc_js($ids['ga4']) . "');</script>\n";
  }
}, 1);

add_action('wp_body_open', function () {
  $ids = rba_tracking_ids();
  if ($ids['gtm']) {
    echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . esc_attr($ids['gtm']) . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
  }
});

/* Clarity: se inyecta solo con consentimiento de analítica (al cargar si ya existía, o al aceptarlo). */
add_action('wp_footer', function () {
  $id = rba_tracking_ids()['clarity'];
  if (!$id) return;
  $id = esc_js($id);
  echo "<script>(function(){var loaded=false;function load(){if(loaded)return;loaded=true;(function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window,document,'clarity','script','{$id}');window.clarity('consent');}"
    . "try{var c=JSON.parse(localStorage.getItem('rba-consent')||'null');if(c&&c.analytics)load();}catch(e){}"
    . "window.addEventListener('rba:consent',function(e){if(e.detail&&e.detail.analytics)load();});})();</script>\n";
}, 20);

<?php
/**
 * Apariencia > Personalizar > "Remake: Home y redes":
 * imágenes reales del home y URLs de redes sociales (solo se muestran las que tengan URL).
 */

function rba_customize_register($wp_customize) {
  $wp_customize->add_section('rba_home', array(
    'title' => 'Remake: Home y redes',
    'priority' => 30,
  ));

  $images = array(
    'rba_hero_image' => 'Imagen del hero (consultor/a real)',
    'rba_process_image' => 'Imagen de la sección "How We Work"',
    'rba_bca_image' => 'Imagen de Business Coaching Academy',
    'rba_team_image' => 'Foto del equipo',
  );

  // Una imagen por servicio (tarjetas del carrusel de servicios).
  foreach (rba_services() as $service) {
    $images['rba_service_img_' . $service['slug']] = 'Servicio: ' . $service['title'];
  }

  foreach ($images as $id => $label) {
    $wp_customize->add_setting($id, array('sanitize_callback' => 'absint'));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $id, array(
      'label' => $label,
      'section' => 'rba_home',
      'mime_type' => 'image',
    )));
  }

  $wp_customize->add_setting('rba_hero_slides', array('sanitize_callback' => 'rba_sanitize_id_list'));
  $wp_customize->add_control('rba_hero_slides', array(
    'label' => 'Slideshow del hero (IDs de medios)',
    'description' => 'IDs de imágenes separados por coma, en orden. Con 2 o más se muestra como slideshow; si está vacío se usa la "Imagen del hero".',
    'section' => 'rba_home',
    'type' => 'text',
  ));

  $wp_customize->add_setting('rba_team_photos', array('sanitize_callback' => 'rba_sanitize_id_list'));
  $wp_customize->add_control('rba_team_photos', array(
    'label' => 'Retratos del equipo (IDs de medios)',
    'description' => 'IDs de imágenes de la biblioteca, separados por coma, en el orden en que se muestran. Si está vacío se usa la "Foto del equipo".',
    'section' => 'rba_home',
    'type' => 'text',
  ));

  foreach (array('linkedin' => 'LinkedIn', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube') as $key => $label) {
    $wp_customize->add_setting('rba_social_' . $key, array('sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control('rba_social_' . $key, array(
      'label' => 'URL de ' . $label,
      'section' => 'rba_home',
      'type' => 'url',
    ));
  }
}

add_action('customize_register', 'rba_customize_register');

function rba_sanitize_id_list($value) {
  return implode(',', array_filter(array_map('absint', explode(',', (string) $value))));
}

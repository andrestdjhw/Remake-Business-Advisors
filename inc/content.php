<?php
/**
 * Utilidades de contenido para páginas internas.
 */

/*
 * Agrega un id a cada <h2> del contenido y devuelve la lista para el índice "On this page".
 * @return array{0: string, 1: array<int, array{id: string, title: string}>}
 */
function rba_content_with_toc($content) {
  $toc = array();
  $used = array();

  $content = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/is', function ($m) use (&$toc, &$used) {
    $title = wp_strip_all_tags($m[2]);
    $id = sanitize_title($title);
    if (preg_match('/\sid="([^"]+)"/', $m[1], $existing)) {
      $id = $existing[1];
      $attrs = $m[1];
    } else {
      $base = $id;
      $n = 2;
      while (isset($used[$id])) $id = $base . '-' . $n++;
      $attrs = $m[1] . ' id="' . esc_attr($id) . '"';
    }
    $used[$id] = true;
    $toc[] = array('id' => $id, 'title' => $title);
    return '<h2' . $attrs . '>' . $m[2] . '</h2>';
  }, $content);

  return array($content, $toc);
}

function rba_terms_url() {
  $page = get_page_by_path('terms-and-conditions');
  return ($page && $page->post_status === 'publish') ? get_permalink($page) : '';
}

function rba_is_legal_page() {
  return is_page() && (get_the_ID() === (int) get_option('wp_page_for_privacy_policy') || is_page('terms-and-conditions'));
}

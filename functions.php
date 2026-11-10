<?php

function rba_load_assets() {
  wp_enqueue_script('rba-main-js', get_theme_file_uri('/build/index.js'), array('wp-element', 'react-jsx-runtime'), '1.0', true);
  wp_enqueue_style('rba-main-css', get_theme_file_uri('/build/index.css'));
}

add_action('wp_enqueue_scripts', 'rba_load_assets');

function rba_add_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'rba_add_support');

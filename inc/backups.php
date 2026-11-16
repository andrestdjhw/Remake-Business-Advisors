<?php
/**
 * UpdraftPlus: deja fuera de los respaldos las carpetas de desarrollo que no hacen falta para restaurar el sitio.
 * node_modules pesa cientos de MB y se regenera con `npm install`.
 * Funciona en cualquier tema o plugin (se compara el nombre de la carpeta). En el log de UpdraftPlus aparece como
 * "Skip filtered directory: .../node_modules".
 */
add_filter('updraftplus_exclude_directory', function ($exclude, $fullpath) {
  return basename($fullpath) === 'node_modules' ? true : $exclude;
}, 10, 2);

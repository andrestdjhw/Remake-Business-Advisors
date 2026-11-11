<?php
/**
 * Endpoint del formulario de contacto: POST /wp-json/rba/v1/contact
 *
 * Seguridad (brief §6.6 y §08): nonce, honeypot, tiempo mínimo de llenado, límite por IP,
 * validación contra las opciones permitidas y sanitización. No guarda los datos en la base:
 * los envía por correo al destinatario oficial (filtro "rba_contact_recipient").
 * PENDIENTE: integración con el CRM de Remake y correo oficial definitivo.
 */

function rba_register_contact_route() {
  register_rest_route('rba/v1', '/contact', array(
    'methods' => 'POST',
    'callback' => 'rba_handle_contact',
    'permission_callback' => '__return_true',
  ));
}

add_action('rest_api_init', 'rba_register_contact_route');

function rba_contact_error($message, $status = 400, $fields = array()) {
  return new WP_REST_Response(array('ok' => false, 'message' => $message, 'fields' => $fields), $status);
}

function rba_handle_contact(WP_REST_Request $request) {
  $p = $request->get_json_params();
  if (!is_array($p)) $p = array();

  // Anti-spam: nonce, honeypot y tiempo mínimo.
  if (!wp_verify_nonce(isset($p['_nonce']) ? $p['_nonce'] : '', 'rba_contact')) {
    return rba_contact_error('Your session expired. Please reload the page and try again.', 403);
  }
  if (!empty($p['website']) || (isset($p['elapsed']) && (int) $p['elapsed'] < 3000)) {
    return new WP_REST_Response(array('ok' => true), 200); // Respuesta neutra para bots.
  }

  // Límite: 5 envíos por hora por IP.
  $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
  $key = 'rba_contact_' . md5($ip);
  $count = (int) get_transient($key);
  if ($count >= 5) {
    return rba_contact_error('Too many requests. Please call us at ' . rba_company()['phone'] . '.', 429);
  }

  $opts = rba_contact_form_options();
  $text = function ($k, $max = 120) use ($p) {
    return isset($p[$k]) ? mb_substr(sanitize_text_field($p[$k]), 0, $max) : '';
  };
  $choice = function ($k, $allowed) use ($p) {
    $v = isset($p[$k]) ? sanitize_text_field($p[$k]) : '';
    return in_array($v, $allowed, true) ? $v : '';
  };

  $data = array(
    'first_name' => $text('first_name'),
    'last_name' => $text('last_name'),
    'email' => sanitize_email(isset($p['email']) ? $p['email'] : ''),
    'phone' => $text('phone', 40),
    'city' => $text('city'),
    'service' => $choice('service', wp_list_pluck($opts['services'], 'value')),
    'objective' => $choice('objective', $opts['objectives']),
    'decision' => $choice('decision', $opts['decision']),
    'timeline' => $choice('timeline', $opts['timeline']),
    'sales' => $choice('sales', $opts['sales']),
    'age' => $choice('age', $opts['age']),
    'message' => isset($p['message']) ? mb_substr(sanitize_textarea_field($p['message']), 0, 2000) : '',
    'consent' => !empty($p['consent']),
  );

  $errors = array();
  if ($data['first_name'] === '') $errors['first_name'] = 'Please enter your first name.';
  if ($data['last_name'] === '') $errors['last_name'] = 'Please enter your last name.';
  if (!is_email($data['email'])) $errors['email'] = 'Please enter a valid email address.';
  if (strlen(preg_replace('/\D/', '', $data['phone'])) < 7) $errors['phone'] = 'Please enter a valid phone number.';
  if ($data['objective'] === '') $errors['objective'] = 'Please choose your main objective.';
  if ($data['decision'] === '') $errors['decision'] = 'Please choose an option.';
  if ($data['timeline'] === '') $errors['timeline'] = 'Please choose an option.';
  if (!$data['consent']) $errors['consent'] = 'Please accept the Privacy Policy and Terms & Conditions.';
  if ($errors) return rba_contact_error('Please review the highlighted fields.', 422, $errors);

  $service_label = '';
  foreach ($opts['services'] as $s) {
    if ($s['value'] === $data['service']) $service_label = $s['label'];
  }

  $lines = array(
    'New consultation request from the website',
    '',
    'Name: ' . $data['first_name'] . ' ' . $data['last_name'],
    'Email: ' . $data['email'],
    'Phone: ' . $data['phone'],
    'City: ' . ($data['city'] ?: '-'),
    '',
    'Service of interest: ' . ($service_label ?: 'Not sure yet'),
    'Main objective: ' . $data['objective'],
    'Makes decisions for the company: ' . $data['decision'],
    'Timeline: ' . $data['timeline'],
    $opts['salesLabel'] . ' ' . ($data['sales'] ?: '-'),
    'Time in operation: ' . ($data['age'] ?: '-'),
    '',
    'Message:',
    $data['message'] ?: '-',
    '',
    'Consent to Privacy Policy and Terms: yes',
  );

  $to = apply_filters('rba_contact_recipient', rba_company()['email']);
  $subject = sprintf('New consultation request: %s %s', $data['first_name'], $data['last_name']);
  $headers = array('Reply-To: ' . $data['first_name'] . ' ' . $data['last_name'] . ' <' . $data['email'] . '>');

  if (!wp_mail($to, $subject, implode("\n", $lines), $headers)) {
    return rba_contact_error('We could not send your request. Please call us at ' . rba_company()['phone'] . '.', 500);
  }

  set_transient($key, $count + 1, HOUR_IN_SECONDS);
  return new WP_REST_Response(array('ok' => true), 200);
}

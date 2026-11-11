<?php
/**
 * Datos de la empresa (sede) y preguntas frecuentes del home.
 *
 * PENDIENTE DE VALIDAR CON REMAKE (brief §6.6, §07 y §12):
 * - Teléfono, dirección, correo y ficha de Maps: tomados del sitio actual (el teléfono y la dirección coinciden en todas las páginas).
 * - FAQs: las 6 primeras vienen del Contact actual, reescritas sin afirmaciones sin verificar
 *   ("50+ years of combined experience", "all 50 states", "optimal results", BPPE, precios/fechas).
 *   Las 6 restantes se redactaron a partir de la descripción de servicios del brief.
 */

function rba_company() {
  return array(
    'name' => 'Remake Business Advisors',
    'street' => '6 Venture, Suite 310',
    'locality' => 'Irvine',
    'region' => 'CA',
    'postal' => '92618',
    'country' => 'US',
    'phone' => '(949) 281-0862',
    'phone_e164' => '+19492810862',
    // El footer actual muestra este correo pero su enlace abre lauraelenam@icloud.com (brief §6.6):
    // se usa el del dominio corporativo hasta que Remake confirme el correo oficial.
    'email' => 'lauraelena@remakeadvisors.com',
    // Ficha de Google Maps enlazada en el footer del sitio actual.
    'maps_url' => 'https://maps.app.goo.gl/r5AvkdkqR1giwVFb6',
  );
}

function rba_company_address_line() {
  $c = rba_company();
  return $c['street'] . ', ' . $c['locality'] . ', ' . $c['region'] . ' ' . $c['postal'];
}

function rba_maps_embed_url() {
  return 'https://maps.google.com/maps?q=' . rawurlencode(rba_company()['name'] . ', ' . rba_company_address_line()) . '&t=m&z=15&output=embed&iwloc=near';
}

function rba_maps_directions_url() {
  return 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode(rba_company_address_line());
}

function rba_home_faqs() {
  return array(
    array(
      'q' => 'What services does Remake Business Advisors offer?',
      'a' => 'We offer seven services: Business Diagnosis, Personalized Advisory Sessions, Formation of Corporate Entities, Strategic Planning, Financial Diagnosis, the Course for Managers and Supervisors, and the Business Coaching Academy, our training program for entrepreneurs.',
    ),
    array(
      'q' => 'Who do you work with?',
      'a' => 'We work with entrepreneurs and business owners, especially in the Hispanic community, who want more clarity, structure, and direction in their companies, whether they are just getting started or already running an established business.',
    ),
    array(
      'q' => 'Are your consulting services and courses available in Spanish?',
      'a' => 'Yes. Our consulting services and training programs are offered in Spanish so you can communicate clearly and focus on what matters for your business.',
    ),
    array(
      'q' => 'How is Remake different from other consulting firms?',
      'a' => 'We combine consulting and business education. Our team understands the challenges Hispanic entrepreneurs face, and we focus on practical tools, clear processes, and decisions you can apply in your business, not just reports.',
    ),
    array(
      'q' => 'How can I get started?',
      'a' => 'Book an initial consultation by phone, Zoom, or in person. In that first conversation we get to know your business, your goals, and your challenges, and recommend the service that best fits your situation.',
    ),
    array(
      'q' => 'What is the Business Coaching Academy and who is it for?',
      'a' => 'The Business Coaching Academy is a structured training program for entrepreneurs and business leaders who want to learn how to organize, manage, and grow their company. Visit the Academy page for the current program details and next steps.',
    ),
    array(
      'q' => 'What happens during a Business Diagnosis?',
      'a' => 'We review the key areas of your business to understand how it operates today, identify gaps and opportunities, and help you prioritize your next steps with a clear picture of where you stand.',
    ),
    array(
      'q' => 'Can you help me choose and set up the right business entity?',
      'a' => 'Yes. We guide you through the options and help you set up a structure that fits your goals, coordinating with your legal and tax professionals for the steps that require their advice.',
    ),
    array(
      'q' => 'Is Remake a tax preparation firm?',
      'a' => 'No. Our focus is business consulting and education: helping you diagnose, structure, and lead your company. When your situation requires tax or legal services, we work alongside the appropriate professionals.',
    ),
    array(
      'q' => 'Do you offer training for my managers and supervisors?',
      'a' => 'Yes. Our Course for Managers and Supervisors strengthens leadership, team management, and day-to-day operational performance. Contact us to discuss your team and how the course can be adapted to your company.',
    ),
    array(
      'q' => 'Do I need to visit your office?',
      'a' => 'No. Our headquarters are in Irvine, California, and we also hold sessions by phone and Zoom, so you can work with us wherever your business is located.',
    ),
    array(
      'q' => 'How long does it take to see progress?',
      'a' => 'It depends on your starting point and the service you choose. After the initial consultation we agree on clear priorities and next steps, so you know what to expect from the process from the beginning.',
    ),
  );
}

/* Datos estructurados FAQPage en el home (AEO): deben coincidir con el contenido visible. */
function rba_faq_schema() {
  if (!is_front_page()) return;

  $schema = array(
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(function ($faq) {
      return array(
        '@type' => 'Question',
        'name' => $faq['q'],
        'acceptedAnswer' => array('@type' => 'Answer', 'text' => $faq['a']),
      );
    }, rba_home_faqs()),
  );

  echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

add_action('wp_head', 'rba_faq_schema');

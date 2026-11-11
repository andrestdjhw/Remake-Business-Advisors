<?php
/**
 * Contenido de las páginas internas (About, Services, landings de servicio, Team y Contact).
 *
 * Fuente: sitio actual remakeadvisors.com (consultado 9–11 nov 2026) y brief de rediseño.
 * PENDIENTE DE VALIDAR CON REMAKE antes de publicar (brief §03, §6.2–6.6, §12):
 * - Se omitieron afirmaciones sin evidencia: "50+ years", "15,000 entrepreneurs trained", "CPAs / enrolled
 *   agents / corporate tax specialists", "all 50 states", certificación BPPE, precios y fechas del BCA,
 *   y duraciones (sesiones de 1h/2h, planeación de 5–6 h).
 * - Cargos del equipo: copiados del sitio actual; confirmar con la lista maestra.
 * - Opciones del formulario: rangos de ventas y "antigüedad" nuevos (el sitio actual tenía huecos).
 */

/* ---------- Landings de servicio: contenido por slug ---------- */
function rba_service_details() {
  return array(
    'business-diagnosis' => array(
      'headline' => 'See your whole business clearly, then decide what to fix first.',
      'problem' => 'When you are running the business every day, it is hard to step back and see where time and money are being lost. Problems in one area show up in another, and it is not always clear which one to solve first.',
      'solution' => 'We analyze your business holistically, area by area, to understand how it operates today. From there we identify opportunities for improvement and work with you on a customized plan to organize processes, use your time and resources better, and support growth.',
      'for' => array(
        'Business owners who feel busy all the time but are not sure where the business is really standing.',
        'Companies that are growing and need more structure in their operations.',
        'Entrepreneurs who want an objective starting point before making bigger decisions.',
      ),
      'includes' => array(
        'A review of the key areas of your business, department by department.',
        'Identification of gaps, risks, and opportunities for improvement.',
        'A prioritized list of next steps.',
        'A customized plan to organize processes and support growth.',
      ),
      'faqs' => array(
        array('q' => 'What areas of my business do you review?', 'a' => 'We look at the business as a whole, area by area, including how it is organized, its processes, its numbers, and its team, to understand how each part affects the others.'),
        array('q' => 'What do I get at the end of the diagnosis?', 'a' => 'A clear picture of where your business stands today and a prioritized plan with the next steps to work on.'),
        array('q' => 'Is this a good first step if I am not sure what I need?', 'a' => 'Yes. The diagnosis is designed to give you an objective starting point, and it helps define which other services, if any, make sense for your business.'),
      ),
      'related' => array('financial-diagnosis', 'strategic-planning', 'personalized-advisory-sessions'),
    ),

    'personalized-advisory-sessions' => array(
      'headline' => 'One-on-one guidance for the decisions your business is facing right now.',
      'problem' => 'Some business questions cannot wait for a long project: a decision about your structure, a challenge with sales or your team, or a situation you have never dealt with before.',
      'solution' => 'In a personalized advisory session we focus on your specific situation. You bring your questions, and we work through them together so you leave with clarity and concrete next steps.',
      'for' => array(
        'Business owners with specific questions who need direct, practical guidance.',
        'Entrepreneurs evaluating an important decision.',
        'Clients who want ongoing support after a diagnosis or a training program.',
      ),
      'includes' => array(
        'A one-on-one session focused on your questions.',
        'Topics such as sales challenges, corporate structure, management, and operations.',
        'Clear recommendations and next steps.',
        'Sessions by phone, Zoom, or in person.',
      ),
      'faqs' => array(
        array('q' => 'What kinds of questions can I bring?', 'a' => 'Any business-related concern, from sales and management challenges to questions about your corporate structure or how to organize your operations.'),
        array('q' => 'How should I prepare for my session?', 'a' => 'Write down your main questions and bring any information that helps explain the situation. The more context you share, the more useful the session will be.'),
      ),
      'related' => array('business-diagnosis', 'strategic-planning', 'formation-of-corporate-entities'),
    ),

    'formation-of-corporate-entities' => array(
      'headline' => 'Choose the right structure for your business and set it up properly.',
      'problem' => 'Choosing between an LLC, an S-Corp, or a C-Corp affects your responsibilities, your obligations, and how your business operates. Making that decision without guidance can create problems later.',
      'solution' => 'We guide you through the process of forming your company and explain the options, responsibilities, obligations, and benefits of each structure so you can choose the one that fits your goals. We coordinate with your legal and tax professionals for the steps that require their advice.',
      'for' => array(
        'Entrepreneurs who are formalizing a new business.',
        'Business owners operating as a DBA or sole proprietor who want to change their structure.',
        'Companies reviewing whether their current structure still fits their growth.',
      ),
      'includes' => array(
        'Guidance on the differences between business entity types (LLC, S-Corp, C-Corp).',
        'Support throughout the formation process.',
        'Clear explanation of the responsibilities and obligations of your new entity.',
        'Coordination with your legal and tax advisors when needed.',
      ),
      'faqs' => array(
        array('q' => 'Do you give legal or tax advice?', 'a' => 'No. We guide you through the options and the process, and we coordinate with licensed legal and tax professionals for the decisions that require their advice.'),
        array('q' => 'Can you help me change from a DBA to an LLC?', 'a' => 'Yes. We can walk you through what changes, what you need to consider, and the steps to form the new entity.'),
      ),
      'related' => array('business-diagnosis', 'personalized-advisory-sessions', 'financial-diagnosis'),
    ),

    'strategic-planning' => array(
      'headline' => 'Turn your vision into a clear roadmap your team can follow.',
      'problem' => 'Without clear goals and priorities, every day becomes urgent, the team pulls in different directions, and growth depends on improvising.',
      'solution' => 'In a guided strategic planning session we help you define clear goals and a roadmap for short-, medium-, and long-term growth, and align your team around the same objectives.',
      'for' => array(
        'Business owners who know where they want to go but not how to get there.',
        'Leadership teams that need to agree on priorities.',
        'Companies preparing for a new stage of growth.',
      ),
      'includes' => array(
        'A guided planning session with you and your key people.',
        'Clear short-, medium-, and long-term goals.',
        'A strategic roadmap with priorities and responsibilities.',
        'Team alignment around shared objectives.',
      ),
      'faqs' => array(
        array('q' => 'Who should take part in the planning session?', 'a' => 'The business owner and the people who make key decisions or lead important areas, so the plan reflects the whole business and everyone is aligned.'),
        array('q' => 'What do we have at the end?', 'a' => 'Defined goals and a roadmap with priorities and responsibilities that your team can follow and review.'),
      ),
      'related' => array('business-diagnosis', 'financial-diagnosis', 'course-for-managers-and-supervisors'),
    ),

    'financial-diagnosis' => array(
      'headline' => 'Understand your numbers so you can make better decisions.',
      'problem' => 'Many businesses sell well but do not know exactly where the money goes, which areas are profitable, or how to read their own financial reports.',
      'solution' => 'We take a deep look at the financial health of your business, reviewing accounting, expenses, and income to uncover areas for improvement, and we create a plan to manage your finances more effectively.',
      'for' => array(
        'Business owners who work a lot but do not see the results in their profits.',
        'Companies that need clarity before investing, hiring, or expanding.',
        'Entrepreneurs who want to learn to read and use their financial information.',
      ),
      'includes' => array(
        'A review of your accounting, expenses, and income.',
        'Identification of areas for financial improvement.',
        'A plan to optimize how you manage your finances.',
        'Recommendations you can apply in your day-to-day decisions.',
      ),
      'faqs' => array(
        array('q' => 'What information do I need to share?', 'a' => 'Your available financial information, such as accounting records and reports of income and expenses. We will tell you exactly what to prepare once we understand your situation. Never send sensitive financial data through the website forms.'),
        array('q' => 'Is this the same as tax preparation?', 'a' => 'No. The financial diagnosis focuses on understanding and improving how your business manages its finances. Tax filing is handled by tax professionals.'),
      ),
      'related' => array('business-diagnosis', 'strategic-planning', 'personalized-advisory-sessions'),
    ),

    'course-for-managers-and-supervisors' => array(
      'headline' => 'Prepare your managers and supervisors to lead with confidence.',
      'problem' => 'Having trained, qualified people in leadership roles takes time and resources, but when supervisors are not prepared, productivity, communication, and the team environment suffer.',
      'solution' => 'Our course for managers and supervisors is fully customized to your company and its needs. It strengthens the skills your leaders use every day to manage people, communicate, and drive results.',
      'for' => array(
        'Companies with new or recently promoted managers and supervisors.',
        'Teams that need better communication and accountability.',
        'Business owners who want leaders who can take on more responsibility.',
      ),
      'includes' => array(
        'Maximizing productivity and personal accountability.',
        'Keeping a positive attitude and managing emotions.',
        'Communicating effectively and listening actively.',
        'Teamwork, leading by example, and being assertive.',
        'Training customized to your company, available online.',
      ),
      'faqs' => array(
        array('q' => 'Can the course be adapted to my company?', 'a' => 'Yes. The course is customized to your company and the needs of your team.'),
        array('q' => 'How is the training delivered?', 'a' => 'The training is available online. Contact us to discuss your team, schedule, and the best format for your company.'),
      ),
      'related' => array('business-coaching-academy', 'strategic-planning', 'business-diagnosis'),
    ),

    'business-coaching-academy' => array(
      'headline' => 'A practical program to structure, organize, and grow your business.',
      'problem' => 'Many entrepreneurs build their business on effort and experience, but without the structure, processes, and financial knowledge that a growing company needs.',
      'solution' => 'The Business Coaching Academy is a training program with a practical approach. Across seven modules you learn how to structure and optimize the key areas of your company, from organization and human resources to processes, marketing, and finances.',
      'for' => array(
        'Hispanic entrepreneurs at any stage, whether they are starting or already established.',
        'Business owners who want to go from running a business empirically to leading it with structure.',
        'Leaders who want practical tools they can apply right away.',
      ),
      'modules' => array(
        array('title' => 'From merchant to entrepreneur', 'items' => array('The areas that make up a company', 'Discovering your "Big Why" as an entrepreneur', 'From an empirical business to a structured company', 'The pillars of a successful entrepreneur')),
        array('title' => 'Organizational design', 'items' => array('The roles of a CEO, CFO, COO, and CMO', 'Efficient organizational design', 'Manual of functions, responsibilities, and obligations by department')),
        array('title' => 'People and compliance', 'items' => array('Labor compliance', 'Hiring processes and job interviews', 'Employee handbook, safety, and onboarding', 'Commercial insurance, workers\' compensation, and general liability')),
        array('title' => 'SWOT for your business', 'items' => array('Strengths, weaknesses, opportunities, and threats', 'Applying and implementing SWOT in your company')),
        array('title' => 'Essential processes', 'items' => array('Administration, sales and metrics, production, and logistics', 'Staff training', 'Systematization and optimization')),
        array('title' => 'Products, pricing, and marketing', 'items' => array('Validating your products and services', 'Pricing, premium products, and hook products', 'The AIDA formula and an effective marketing plan')),
        array('title' => 'Finances for a profitable company', 'items' => array('The "Profit First" formula', 'Profit and loss report, balance sheet, and general ledger', 'From entrepreneur to investor')),
      ),
      'faqs' => array(
        array('q' => 'Who is the Business Coaching Academy for?', 'a' => 'For entrepreneurs and business owners, especially in the Hispanic community, who want to structure and grow their companies, whether they are just starting or already established.'),
        array('q' => 'When does the next group start, and how much does it cost?', 'a' => 'Program dates, schedules, and enrollment options are updated for each group. Request information and our team will share the current details with you.'),
        array('q' => 'Is the program available in Spanish?', 'a' => 'Yes. Our training programs are offered in Spanish.'),
      ),
      'related' => array('course-for-managers-and-supervisors', 'business-diagnosis', 'personalized-advisory-sessions'),
    ),
  );
}

function rba_service_by_slug($slug) {
  foreach (rba_services() as $service) {
    if ($service['slug'] === $slug) {
      $details = rba_service_details();
      return array_merge($service, isset($details[$slug]) ? $details[$slug] : array());
    }
  }
  return null;
}

/* ---------- Equipo ---------- */
function rba_team_members() {
  // photo = ID en la biblioteca de medios (importados del sitio actual).
  return array(
    array('slug' => 'karina-nava', 'name' => 'Karina Nava', 'role' => 'Enrolled Agent · Founder', 'photo' => 13),
    array('slug' => 'laura-elena-martinez', 'name' => 'Laura Elena Martínez', 'role' => 'Business Advisor · Co-founder', 'photo' => 11),
    array('slug' => 'sergio-garcia-rivas', 'name' => 'Sergio Garcia Rivas', 'role' => 'General Manager', 'photo' => 18),
    array('slug' => 'miguel-gomez', 'name' => 'Miguel Gómez', 'role' => 'CFP®, EA', 'photo' => 17),
    array('slug' => 'manuel-martinez', 'name' => 'Manuel Martínez', 'role' => 'Junior Business Consultant', 'photo' => 12),
    array('slug' => 'liliana-nevarez', 'name' => 'Liliana Nevarez', 'role' => 'Junior Business Consultant', 'photo' => 15),
    array('slug' => 'cynthia-iriarte', 'name' => 'Cynthia Iriarte', 'role' => 'Corporations Department', 'photo' => 14),
    array('slug' => 'luis-gonzalez', 'name' => 'Luis Gonzalez', 'role' => 'Client Service Associate', 'photo' => 16),
  );
}

/* ---------- About ---------- */
function rba_values() {
  return array(
    array('icon' => 'users', 'title' => 'Trust', 'text' => 'We build strong relationships by delivering reliable, transparent, and professional services that clients can consistently depend on.'),
    array('icon' => 'compass', 'title' => 'Transformation', 'text' => 'We help businesses evolve and reach their full potential through practical strategies and personalized guidance.'),
    array('icon' => 'check', 'title' => 'Integrity', 'text' => 'We uphold high ethical standards, fostering trust and accountability in every interaction and decision.'),
    array('icon' => 'target', 'title' => 'Commitment', 'text' => 'We support our clients with focus and dedication, keeping their success at the center of what we do.'),
    array('icon' => 'message', 'title' => 'Listening', 'text' => 'We understand each client\'s needs by listening first, so our solutions fit their specific challenges and goals.'),
  );
}

/* ---------- Formulario de contacto (opciones compartidas con React y validadas en el endpoint) ---------- */
function rba_contact_form_options() {
  $last_year = (int) wp_date('Y') - 1;

  return array(
    'objectives' => array(
      'Increase profitability',
      'Increase sales',
      'Gain access to IRS credits and deductions',
      'Attract and retain employees',
      'Grow your profits through real estate investment',
      'Achieve tax-free financial independence',
      'Organize and structure my business',
      'Other',
    ),
    'decision' => array('Yes', 'No', 'I share decisions with a partner'),
    'timeline' => array('As soon as possible', 'Within the next week', 'Within the next month', 'I\'m just exploring for now'),
    'salesLabel' => sprintf('Roughly how much did your company make in total sales in %d?', $last_year),
    'sales' => array(
      'Under $250,000',
      '$250,000 to $750,000',
      '$750,000 to $1,000,000',
      '$1,000,000 to $2,000,000',
      '$2,000,000 to $4,000,000',
      '$4,000,000 to $10,000,000',
      'More than $10,000,000',
      'Prefer not to say',
    ),
    'age' => array('Not registered yet', 'Less than 1 year', '1 to 3 years', '3 to 5 years', 'More than 5 years'),
    'services' => array_map(function ($s) { return array('value' => $s['slug'], 'label' => $s['title']); }, rba_services()),
  );
}

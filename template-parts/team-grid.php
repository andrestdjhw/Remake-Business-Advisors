<?php
/**
 * Cuadrícula del equipo con retratos "glass" (nombre y cargo).
 * Cada tarjeta tiene id = slug para que /team/{slug}/ redirija a /our-team/#{slug}.
 */
?>
<ul class="grid grid-cols-2 gap-x-6 gap-y-12 sm:grid-cols-3 lg:grid-cols-4">
  <?php foreach (rba_team_members() as $member) : ?>
    <li id="<?php echo esc_attr($member['slug']); ?>" class="relative scroll-mt-32 text-center hover:z-10" data-reveal="pop">
      <figure class="rba-glass mx-auto w-full max-w-[13rem]">
        <div class="rba-glass__orb">
          <?php echo wp_get_attachment_image($member['photo'], 'medium', false, array(
            'class' => 'aspect-square w-full object-cover',
            'loading' => 'lazy',
            'sizes' => '(min-width: 1024px) 208px, 40vw',
            'alt' => $member['name'],
          )); ?>
          <span class="rba-glass__shine" aria-hidden="true"></span>
        </div>
      </figure>
      <h3 class="mt-5 text-lg font-semibold text-navy-900"><?php echo esc_html($member['name']); ?></h3>
      <p class="mt-1 text-sm text-ink/70"><?php echo esc_html($member['role']); ?></p>
    </li>
  <?php endforeach; ?>
</ul>

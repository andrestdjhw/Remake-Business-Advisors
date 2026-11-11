<?php
/**
 * Plantilla de páginas internas (Privacy Policy, Terms & Conditions y cualquier página de contenido).
 * Encabezado navy + índice "On this page" generado de los H2 cuando hay 3 o más secciones.
 */

get_header();

while (have_posts()) :
  the_post();
  list($content, $toc) = rba_content_with_toc(apply_filters('the_content', get_the_content()));
  $has_toc = count($toc) >= 3;
  ?>

  <main id="main">
    <header class="relative isolate overflow-hidden bg-navy-900 text-white">
      <div class="rba-grid-pattern" aria-hidden="true"></div>
      <div class="relative mx-auto max-w-site px-4 py-16 sm:px-6 lg:px-10 lg:py-20">
        <?php if (rba_is_legal_page()) : ?>
          <p class="eyebrow flex items-center gap-3 text-brand-400"><span class="h-px w-8 bg-brand-400" aria-hidden="true"></span>Legal</p>
        <?php endif; ?>
        <h1 class="mt-4 text-4xl font-bold sm:text-5xl"><?php the_title(); ?></h1>
        <p class="mt-4 text-white/70">
          Last updated: <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('F j, Y')); ?></time>
        </p>
      </div>
    </header>

    <div class="mx-auto grid max-w-site gap-12 px-4 py-16 sm:px-6 lg:grid-cols-12 lg:px-10 lg:py-20">
      <?php if ($has_toc) : ?>
        <aside class="lg:col-span-3">
          <nav class="rounded-2xl bg-cream p-6 ring-1 ring-brand-100 lg:sticky lg:top-[calc(var(--wp-admin--admin-bar--height,0px)_+_7rem)]" aria-label="On this page">
            <p class="font-heading text-sm font-semibold text-navy-900">On this page</p>
            <ol class="mt-4 space-y-2.5 text-sm">
              <?php foreach ($toc as $item) : ?>
                <li>
                  <a href="#<?php echo esc_attr($item['id']); ?>" class="text-ink/70 transition-colors hover:text-brand-700"><?php echo esc_html($item['title']); ?></a>
                </li>
              <?php endforeach; ?>
            </ol>
          </nav>
        </aside>
      <?php endif; ?>

      <article class="<?php echo $has_toc ? 'lg:col-span-9' : 'lg:col-span-12'; ?>">
        <div class="prose prose-lg max-w-3xl prose-headings:font-heading prose-headings:text-navy-900 prose-h2:mt-12 prose-h2:scroll-mt-32 prose-h2:text-2xl prose-h3:text-xl prose-a:text-brand-700 prose-a:underline-offset-2 hover:prose-a:text-brand-800 prose-strong:text-navy-900 prose-li:marker:text-brand-500">
          <?php echo $content; // Contenido ya filtrado por the_content. ?>
        </div>
      </article>
    </div>
  </main>

<?php
endwhile;

get_footer();

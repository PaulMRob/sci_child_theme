<?php get_header(); ?>

<div class="publications">
<h1>Publications</h1>
<a href="/all-bibtex.bib" target="_blank" class="bibtex-btn">📄 View All BibTeX</a>


    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
            $pub_url = get_field('pub_url');
            $authors = get_field('authors');
            $source = get_field('source');
            $doi_url = get_field('doi');
            $year = get_field('year');
            $pubmed_id = get_field('pubmed_id');
            $issn = get_field('issn');
            $abstract = get_field('abstract');
            $type = get_field('type') ?: 'Misc';

        // make a BibTeX key
        $author_key = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $authors), 0, 3));
        $bib_key = "SCI:{$author_key}{$year}a";

        // build BibTeX 
        $bibtex = "@$type{{$bib_key},\n" .
                  "  author = {" . $authors . "},\n" .
                  "  title = {" . get_the_title() . "},\n" .
                  ($source ? "  booktitle = {" . $source . "},\n" : "") .
                  ($volume ? "  volume = {" . $volume . "},\n" : "") .
                  ($number ? "  number = {" . $number . "},\n" : "") .
                  ($pages ? "  pages = {" . $pages . "},\n" : "") .
                  "  year = {" . $year . "},\n" .
                  ($doi_url ? "  doi = {" . $doi_url . "},\n" : "") .
                  ($pub_url ? "  url = {" . $pub_url . "},\n" : "") .
                  "}";
    ?>
        <div class="publication">
            <p>
                <?php if ($pub_url): ?>
                    <a href="<?php echo esc_url($pub_url); ?>" target="_blank">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pdf-icon.svg" class="icon" alt="PDF icon">
                    </a>
                <?php endif; ?>
                    
                <a class="bibtex-button" data-bibtex="<?php echo esc_attr($bibtex); ?>" target="_blank">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bibtex-icon.svg" class="icon" alt="BibTeX icon">
                </a>

                <br>

                <?php echo esc_html($authors); ?>.
                <b>“<?php the_title(); ?>”</b>
                In <i><?php echo esc_html($source); ?></i>
                <?php if ($volume): ?>, Vol. <?php echo esc_html($volume); ?><?php endif; ?>
                <?php if ($number): ?>, No. <?php echo esc_html($number); ?><?php endif; ?>,
                <?php echo esc_html($year); ?>.
                <br>
                
                <?php if ($issn): ?>
                    <small>ISSN: <?php echo esc_html($issn) ?></small>
                <?php endif; ?>

                <?php if ($doi_url): ?>
                    <small>DOI: <a href="<?php echo esc_url($doi_url); ?>" target="_blank"><?php echo esc_html($doi_url); ?></a></small>
                <?php endif; ?>
            </p>
            <p>
                <?php if ($abstract): ?>
                <button class="toggle-abstract" type="button" data-target="abstract-<?php the_ID(); ?>">
                    <span class="label">Abstract</span>
                    <span class="arrow">▼</span>
                </button>
                <div id="abstract-<?php the_ID(); ?>" class="abstract-content">
                    <p><?php echo esc_html($abstract); ?></p>
                </div>
                <?php endif; ?>
            </p>
        
        </div>
    <?php endwhile; else: ?>
        <p>No publications found.</p>
    <?php endif; ?>
</div>

<!-- hidden BibTeX modal -->
<div id="bibtex-modal" class="bibtex-modal" style="display: none;">
    <div class="bibtex-content">
        <div class="bibtex-actions">
            <button id="copy-bibtex" title="Copy BibTeX">
                <span class="dashicons dashicons-clipboard"></span>
            </button>
            <button id="close-bibtex" title="Close">
                <span class="dashicons dashicons-no-alt"></span>
            </button>
        </div>
        <textarea id="bibtex-text" readonly></textarea>
    </div>
</div>


<div class="pagination">
    <?php
        the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => __('« Prev', 'textdomain'),
            'next_text' => __('Next »', 'textdomain'),
        ]);
    ?>
</div>

<!-- popup (modal) script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('bibtex-modal');
    const content = document.querySelector('.bibtex-content');
    const textArea = document.getElementById('bibtex-text');
    const copyBtn = document.getElementById('copy-bibtex');
    const closeBtn = document.getElementById('close-bibtex');

    document.querySelectorAll('.bibtex-button').forEach(button => {
        button.addEventListener('click', () => {
            const bibtex = button.getAttribute('data-bibtex');
            textArea.value = bibtex;
            modal.style.display = 'flex';
        });
    });

    copyBtn.addEventListener('click', () => {
        textArea.select();
        document.execCommand('copy');
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {
        if (!content.contains(e.target)) {
            modal.style.display = 'none';
        }
    });
});

// show abstract button
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-abstract').forEach(button => {
    button.addEventListener('click', () => {
      const targetId = button.getAttribute('data-target');
      const abstractDiv = document.getElementById(targetId);
      const arrow = button.querySelector('.arrow');
      const label = button.querySelector('.label');

      abstractDiv.classList.toggle('visible');
      const isVisible = abstractDiv.classList.contains('visible');

      label.textContent = isVisible ? 'Hide' : 'Abstract';
      arrow.classList.toggle('rotated', isVisible);
    });
  });
});

</script>

<?php get_footer(); ?>

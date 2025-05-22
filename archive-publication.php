<?php get_header(); ?>

<div class="publications">
<h1>Publications</h1>
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
        ?>
        <div class="publication">
            <p>
                <?php if ($pub_url): ?>
                    <a href="<?php echo esc_url($pub_url); ?>" target="_blank">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/pdf-icon.svg" class="icon" alt="PDF icon">
                    </a>
                <?php endif; ?>

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
        </div>
    <?php endwhile; else: ?>
        <p>No publications found.</p>
    <?php endif; ?>
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

<?php get_footer(); ?>

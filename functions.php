<?php
// enqueue parent styles
function people_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style']);
}
add_action('wp_enqueue_scripts', 'people_child_enqueue_styles');


// PEOPLE cpt @ sci
function sci_register_person_cpt() {
    register_post_type('person', [
        'labels' => [
            'name' => 'People',
            'singular_name' => 'Person',
            'add_new_item' => 'Add New Person',
            'edit_item' => 'Edit Person',
            'new_item' => 'New Person',
            'view_item' => 'View Person',
            'search_items' => 'Search People',
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'people'],
        'menu_icon' => 'dashicons-id',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sci_register_person_cpt');

// PUBLICATIONS cpt @ sci.
function sci_register_publication_cpt() {
    register_post_type('publication', [
        'labels' => [
            'name' => 'Publications',
            'singular_name' => 'Publication',
            'add_new_item' => 'Add New Publication',
            'edit_item' => 'Edit Publication',
            'new_item' => 'New Publication',
            'view_item' => 'View Publication',
            'search_items' => 'Search Publications',
        ],
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'publications'],
        'menu_icon' => 'dashicons-book-alt',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'sci_register_publication_cpt');

// redirect single publication view to the archive page
function sci_disable_single_publication_view() {
    if (is_singular('publication')) {
        wp_redirect(home_url('/publications/'), 301);
        exit;
    }
}
add_action('template_redirect', 'sci_disable_single_publication_view');

// order publications by date published
function sci_modify_publication_archive_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('publication')) {
        $query->set('orderby', 'date');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', 20);

    }
}
add_action('pre_get_posts', 'sci_modify_publication_archive_query');

function generate_bibtex($post_id) {
    $authors = get_field('authors', $post_id);
    $title = get_the_title($post_id);
    $year = get_field('year', $post_id);
    $url = get_field('pub_url', $post_id);
    $source = get_field('source', $post_id);
    $issn = get_field('issn', $post_id);
    $doi = get_field('doi', $post_id);
    $pubmed_id = get_field('pubmed_id', $post_id);

    // Build BibTeX ID like SCI:Cha2025a
    $bib_id = 'SCI:' . substr(preg_replace('/[^A-Za-z]/', '', $authors), 0, 3) . $year . 'a';

    return "@InProceedings{{$bib_id},
  author = {$authors},
  title = {$title},
  source/subtitle = {$source},
  year = {$year}," .
  ($issn ? "\n  ISSN = {$issn}," : "") .
  ($doi ? "\n  title_url = {$doi}," : "") .
  ($pubmed_id ? "\n  pubmed_id = {$pubmed_id}," : "") .
  ($url ? "\n  url = {$url}" : "") . "
}";
}

// register custom endpoint for sci.bib 
add_action('init', function () {
    add_rewrite_rule('^all-bibtex\.bib$', 'index.php?all_bibtex=1', 'top');
    add_rewrite_tag('%all_bibtex%', '1');
});

add_action('template_redirect', function () {
    if (get_query_var('all_bibtex')) {
        header('Content-Type: text/plain');
        header('Content-Disposition: inline; filename="all-publications.bib"');

        $query = new WP_Query([
            'post_type' => 'publication',
            'posts_per_page' => -1,
            'orderby' => 'meta_value_num', 
            'meta_key' => 'year',
            'order' => 'ASC', // is this the order we want??
        ]);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                echo generate_bibtex(get_the_ID()) . "\n\n";
            }
        }
        exit;
    }
});

//PERFORMANCE IMPROVEMENTS
//footer
add_action('wp_footer', function () {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
      const img = document.querySelector(".uu-footer-dept-logo img");
      if (img && !img.hasAttribute("width")) {
        img.setAttribute("width", "80");
        img.setAttribute("height", "48.45"); 
      }
    });
    </script>
    <?php
});

// dynamically add width and height to all imgs
add_filter('the_content', function ($content) {
    return preg_replace_callback('/<img([^>]+)>/', function ($matches) {
        $img = $matches[0];
        if (strpos($img, 'width=') !== false && strpos($img, 'height=') !== false) {
            return $img;
        }

        if (preg_match('/src="([^"]+)"/', $img, $srcMatch)) {
            $src = $srcMatch[1];
            $path = str_replace(home_url(), ABSPATH, $src);
            if (file_exists($path)) {
                [$width, $height] = getimagesize($path);
                return preg_replace(
                    '/<img/',
                    "<img width=\"$width\" height=\"$height\"",
                    $img
                );
            }
        }

        return $img;
    }, $content);
});

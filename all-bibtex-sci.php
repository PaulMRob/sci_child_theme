<?php
/* Template Name: All BibTeX */
get_header();
?>

<div class="bibtex-title">
    <h1>All Publications – BibTeX</h1>
    <div class="bibtex-actions">
    <button onclick="printBibtex()" class="bibtex-btn">🖨 Print</button>
    <button onclick="copyBibtex()" class="bibtex-btn">📋 Copy</button>
    <button onclick="downloadBibtex()" class="bibtex-btn">⬇ Download</button>
    </div>
</div>

<div class="bibtex-content">
    <pre style="white-space: pre-wrap; font-size: 0.9rem;">
<?php
$args = [
    'post_type' => 'publication',
    'posts_per_page' => -1,
    'orderby' => 'meta_value_num',
    'meta_key' => 'year',
    'order' => 'DESC',
];
$query = new WP_Query($args);

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        $authors = get_field('authors');
        $title = get_the_title();
        $source = get_field('source');
        $year = get_field('year');
        $url = get_field('pub_url');
        $slug = basename(get_permalink());

        // Generate a BibTeX key, e.g., SCI:Lastname2024a
        preg_match('/\b(\w+)\b/', $authors, $matches);
        $key = "SCI:" . ucfirst(strtolower($matches[1])) . $year . "a";

        echo "@Article{{$key},\n";
        echo "  author =    \"" . $authors . "\",\n";
        echo "  title =     \"" . $title . "\",\n";
        echo "  journal =   \"" . $source . "\",\n";
        echo "  year =      \"" . $year . "\",\n";
        echo "  url =       \"" . $url . "\"\n";
        echo "}\n\n";
    endwhile;
    wp_reset_postdata();
else :
    echo "No publications found.";
endif;
?>
    </pre>
</div>

<script>
function printBibtex() {
  const content = document.getElementById('bibtex-content').innerHTML;
  const printWindow = window.open('', '_blank');
  printWindow.document.write('<pre>' + content + '</pre>');
  printWindow.document.close();
  printWindow.focus();
  printWindow.print();
}

function copyBibtex() {
  const text = document.getElementById('bibtex-content').innerText;
  navigator.clipboard.writeText(text).then(() => {
    alert('Copied BibTeX to clipboard!');
  });
}

function downloadBibtex() {
  const text = document.getElementById('bibtex-content').innerText;
  const blob = new Blob([text], { type: 'text/plain' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = 'publications.bib';
  link.click();
}
</script>

<?php get_footer(); ?>

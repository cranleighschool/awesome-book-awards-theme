<?php

ob_start();

?>
<html>
<head>
<link href="//cdn.cranleigh.org/fonts/bentonsans/fontface.css" rel="stylesheet" />
    <link rel='stylesheet' id='aspect-font-css'  href='//cdn.cranleigh.org/css/AspW-Rg.css?ver=4.9.4' type='text/css' media='all' />

    <style>
    body {
        font-family: "Benton Sans Reg", sans-serif;
    }
    table {
        width:100%;
        border: 1px;
        border-spacing:0px;
        border-collapse: collapse;
    }

    td,th {
        text-align: left;
        border: 1px solid #000000;
        margin:0px;
        padding:15px;
    }

    th {
        border:none;
        border-bottom-width: 2px;
        font-family: AspW-Rg;
        text-transform: uppercase;
    }
    .thead {
        font-family:AspW-Rg;
        text-transform: uppercase;
    }
    h1,h2,h3 {
        font-family: AspW-Rg;
        text-transform: uppercase;
    }
    hr {
        margin:50px;
    }
    p {
        font-size:0.8em;
    }

</style>
</head>
<body>
<?php
if (!isset($_GET['num']) || $_GET['num'] < 1) {
    echo '<h1>Awesome Book Awards 2018</h1>';
    echo '<h2>How many voting slips do you need to generate?</h2>';
    echo '<form>';
    echo '<input type="number" name="num">';
    echo '<input type="submit"></form>';
}
$num = $_GET['num'];

$books = new \WP_Query([
    "posts_per_page" => -1,
    "post_type" => 'book',
    "orderby" => "ID",
    "order" => "ASC",
    "tax_query" => [
        [
            "taxonomy" => "awesome-year",
            "field" => "slug",
            "terms" => date("Y")
        ]
    ]
]);
$results = $books->posts;
wp_reset_query();
wp_reset_postdata();
for($i=0; $i < $num; $i++): ?>
    <h1>Awesome Book Awards 2018</h1>
<p>You must give your favourite book 5 points, your second favourite book 4 points and so on, down to 1 point for your least favourite book.</p>
<p>You may only vote if you have read all five books.</p>
<table class="slip">
    <tbody>
    <tr class="thead">    <td>Book</td>
        <td>Score</td>
    </tr>
    <?php foreach ($results as $book): ?>
        <tr>
            <td><?php echo $book->post_title; ?>, <em>by <?php echo get_the_title(get_post_meta($book->ID, 'author', true)); ?></em></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>
<hr />
<?php endfor; ?>

</body>
</html>

<?php
$html = ob_get_clean();

echo $html;
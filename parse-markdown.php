<?php
$file = $_GET['file'];

$path = __DIR__ . '/' . $file . '.md';

$path = realpath($path);

if (!$path || !is_readable($path)) {
    http_response_code(404);
    echo '<h1>Not found</h1>';
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$contents = file_get_contents($path);

$modal = <<<MODAL
<div class="modal micromodal-slide" id="$1" aria-hidden="true">
<div class="modal__overlay" tabindex="-1" data-micromodal-close>
  <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="$1-title">
    <header class="modal__header">
      <h2 class="modal__title" id="$1-title">
        $2
      </h2>
      <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
    </header>
    <main class="modal__content" id="$1-content">
      $3
    </main>
    <footer class="modal__footer">
    </footer>
  </div>
</div>
</div>
MODAL;


$contents = preg_replace('#{{modal:(.+?):(.+?):(.+?)}}#s', $modal, $contents);

$parsedown = new Parsedown();

$title = \Stringy\Stringy::create(basename($file))->upperCamelize()->regexReplace('^\d+', '')->underscored()->humanize()->toTitleCase();

$body = $parsedown->text($contents);
$body = str_replace('language-', 'line-numbers language-', $body);

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.1.0/github-markdown.min.css" integrity="sha512-KUoB3bZ1XRBYj1QcH4BHCQjurAZnCO3WdrswyLDtp7BMwCw7dPZngSLqILf68SGgvnWHTD5pPaYrXi6wiRJ65g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/themes/prism.min.css" integrity="sha512-/mZ1FHPkg6EKcxo0fKXF51ak6Cr2ocgDi5ytaTBjsQZIH/RNs6GF6+oId/vPe3eJB836T36nXwVh/WBl/cWT4w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/css/micromodal.css">
    <style>
        .markdown-body {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer {
            color: #AAA;
            text-align: right;
        }

        .box-yellow {
            background: lightyellow;
            padding: 10px;
            border: yellowgreen 1px solid;
            margin: 5px auto;
        }

        ol li > img {
            vertical-align: top;
        }

        .small {
            font-size: .7em;
        }
    </style>
</head>
<body>
<div class="markdown-body">
    <?= $body; ?>
    <div class="footer">Wojciech Frącz, fracz@agh.edu.pl</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/prism.min.js" integrity="sha512-UOoJElONeUNzQbbKQbjldDf9MwOHqxNz49NNJJ1d90yp+X9edsHyJoAs6O4K19CZGaIdjI5ohK+O2y5lBTW6uQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
<script>
    MicroModal.init();
</script>
</body>
</html>



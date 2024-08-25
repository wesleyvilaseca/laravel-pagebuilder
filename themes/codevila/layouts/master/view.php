<?php
use App\Supports\Helper\Utils;
?>
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" /> -->

        <link rel="stylesheet" href="<?= Utils::getasset('assets-themes/codevila/css/owl/owl.carousel.min.css') ?>" />
        <link rel="stylesheet" href="<?= Utils::getasset('assets-themes/codevila/css/owl/owl.theme.default.min.css') ?>" />
        <link rel="stylesheet" href="<?= Utils::getasset('assets-themes/codevila/css/magnific-popup.css') ?>" />
        <link rel="stylesheet" href="<?= Utils::getasset('assets-themes/codevila/css/main.css') ?>" />

        <title><?= $page->get('title'); ?></title>


        <script defer>
            const site_url = '<?= Utils::get_site_url() ?>';
            window.uspEvent = '<?= @$_GET['event'] ?? '' ?>';
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="<?= mix('js/sitebuilder-app.js') ?>"></script>
    </head>

    <body>

        <?= $body ?>
        <!--JS-->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="<?= Utils::getasset('assets-themes/codevila/js/bootstrap.min.js') ?>"></script>
        <script src="<?= Utils::getasset('assets-themes/codevila/js/owl.carousel.min.js') ?>"></script>
        <script src="<?= Utils::getasset('assets-themes/codevila/js/isotope.pkgd.min.js') ?>"></script>
        <script src="<?= Utils::getasset('assets-themes/codevila/js/magnify/jquery.magnific-popup.min.js') ?>"></script>
        <script src="<?= Utils::getasset('assets-themes/codevila/js/main.js') ?>"></script>
    </body>
</html>
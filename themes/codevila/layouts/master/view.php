<?php
use App\Supports\Helper\Utils;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?= phpb_theme_asset('css/owl/owl.carousel.min.css') ?>" />
    <link rel="stylesheet" href="<?= phpb_theme_asset('css/owl/owl.theme.default.min.css') ?>" />
    <link rel="stylesheet" href="<?= phpb_theme_asset('css/magnific-popup.css') ?>" />
    <link rel="stylesheet" href="<?= phpb_theme_asset('css/main.css') ?>" />

    <title><?= $page->get('title'); ?></title>
    <script>
        let site_url = '<?= Utils::get_site_url() ?>';
    </script>
</head>

<body>

    <?= $body ?>
    <!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="<?= phpb_theme_asset('js/bootstrap.min.js') ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= phpb_theme_asset('js/owl.carousel.min.js') ?>"></script>
    <script src="<?= phpb_theme_asset('js/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= phpb_theme_asset('js/magnify/jquery.magnific-popup.min.js') ?>"></script>
    <script src="<?= phpb_theme_asset('js/main.js') ?>"></script>
</body>

</html>
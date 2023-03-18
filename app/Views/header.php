<!-- metadata -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="<?= base_url('public/favicon.ico') ?>"/>

<!-- Font Awesome kit CSS -->
<script src="https://kit.fontawesome.com/b3b08eaee3.js" crossorigin="anonymous"></script>

<!-- Custom CSS -->
<link rel="stylesheet" href="<?= base_url('public/css/navigation.css') ?>">

<!-- Tab title -->
<title><?= (isset($meta_title) ? $meta_title : 'Missing title') ?></title>

<!-- last search data, used for dynamic page elements -->
<script>
    let lastSearch = <?= json_encode($_GET === [] ? $_POST : $_GET) ?>;

    if (typeof lastSearch['sort'] === 'function' || lastSearch['sort'] === undefined) {
        lastSearch['sort'] = 'id';
    }
    if (lastSearch['sortDir'] === undefined) {
        lastSearch['sortDir'] = 'ASC';
    }
</script>

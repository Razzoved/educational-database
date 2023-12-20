<!-- metadata -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="<?= base_url('public/favicon.ico') ?>" />

<!-- Font Awesome kit CSS -->
<script src="https://kit.fontawesome.com/b3b08eaee3.js" crossorigin="anonymous"></script>

<!-- Tab title -->
<title><?= (isset($meta_title) ? $meta_title : 'Missing title') ?></title>

<!-- last search data, used for dynamic page elements -->
<script>
    const TOKEN = '<?= csrf_hash() ?>';
    const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';

    const params = new URL(window.location).searchParams;
    if (!params.has('sort')) params.set('sort', '<?= $_GET['sort'] ?? 'id' ?>');
    if (!params.has('sortDir')) params.set('sortDir', '<?= $_GET['sortDir'] ?? 'desc' ?>');
</script>

<script src="<?= base_url('public/js/common.js') ?>">
</script>
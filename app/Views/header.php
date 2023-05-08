<!-- metadata -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/png" href="<?= base_url('public/favicon.ico') ?>"/>

<!-- Font Awesome kit CSS -->
<script src="https://kit.fontawesome.com/b3b08eaee3.js" crossorigin="anonymous"></script>

<!-- Tab title -->
<title><?= (isset($meta_title) ? $meta_title : 'Missing title') ?></title>

<!-- last search data, used for dynamic page elements -->
<script>
    const params = new URL(window.location).searchParams;
    if (!params.has('sort')) params.set('sort', '<?= $_GET['sort'] ?? 'id' ?>');
    if (!params.has('sortDir')) params.set('sortDir', '<?= $_GET['sortDir'] ?? 'desc' ?>');
</script>

<script>
    String.prototype.fill = function(data = undefined) {
        let result = this;
        for (var k in data) {
            result = result.replaceAll(`@${k.toLowerCase()}@`, data[k]);
        }
        return result;
    }

    String.prototype.html = function(data = undefined) {
        let result = this.fill(data);
        if ((match = result.match(/@[^ @]*@/)) !== null) {
            console.warn('Template not fully filled, please check your arguments');
            console.debug('Found: ', match);
        }
        const parser = new DOMParser();
        return parser.parseFromString(result, 'text/html')?.body.firstElementChild;
    };

    HTMLInputElement.prototype.verifyOption = function() {
        const result = Array.from(this.list.querySelectorAll('option'))
            .filter(option => option.value === this.value);
        return result.length > 0 && result[0];
    }
</script>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<link rel="stylesheet" href="<?= base_url('public/css/dashboard.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page dashboard">
    <div class="page__content">
        <section>
            <h2>Statistics</h2>
            <div>
                <p>Total views: <strong><?= $viewsTotal ?? 'unknown' ?></strong></p>
                <p>Recent views: <strong><?= array_sum($viewsHistory) ?? 'unknown' ?></strong></p>
            </div>
            <canvas class="dashboard__views" id="views-chart"></canvas>
        </section>

        <section>
            <h2>Most viewed recently</h2>
            <ol class="dashboard__most-viewed">
                <?php foreach ($materials as $m) : ?>
                <li class="dashboard__material" onclick="window.location.href='<?= base_url('single/' . $m->id) ?>'">
                    <img src="<?= $m->getThumbnail()->getURL() ?>" alt="material thumbnail" />
                    <p class="dashboard__subtitle"><?= $m->title ?><p>
                    <p><?= $m->views ?> view<?= $m->views !== 1 ? 's' : ''?></p>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>

        <section>
            <h2>Most viewed all-time</h2>
            <ol class="dashboard__most-viewed">
                <?php foreach ($materialsTotal as $m) : ?>
                <li class="dashboard__material" onclick="window.location.href='<?= base_url('single/' . $m->id) ?>'">
                    <img src="<?= $m->getThumbnail()->getURL() ?>" alt="material thumbnail" />
                    <p class="dashboard__subtitle"><?= $m->title ?><p>
                    <p><?= $m->views ?> view<?= $m->views !== 1 ? 's' : ''?></p>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>

        <section>
            <h2>Contributors</h2>
            <ol class="dashboard__contributors">
                <?php foreach ($contributors as $c) : ?>
                <li class="dashboard__contributor">
                    <p><?= $c['contributor'] ?></p>
                    <strong><?= $c['total_posts'] ?></strong>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>
    </div>

    <div class="page__sidebar">
        <section>
            <h2>Newest</h2>
            <ul class="dashboard__recent">
                <?php foreach ($recentPublished as $r) : ?>
                <li class="dashboard__material" onclick="location.href='<?= base_url('admin/materials/1?search=' . urlencode($r->title)) ?>'">
                    <p class="dashboard__subtitle"><?= $r->title ?><p>
                    <p><?= $r->published ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section>
            <h2>Updated</h2>
            <ul class="dashboard__recent">
                <?php foreach ($recentUpdated as $r) : ?>
                <li class="dashboard__material" onclick="location.href='<?= base_url('admin/materials/1?search=' . urlencode($r->title)) ?>'">
                    <p class="dashboard__subtitle"><?= $r->title ?><p>
                    <p><?= $r->updated ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    const yValues = <?= json_encode($viewsHistory) ?>;
    while (yValues.length < 30) {
        yValues.push(0);
    }
    let xValues = [];
    for (let i = 29; i >= 0; i--) {
        let date = new Date();
        date.setDate(date.getDate() - i);
        xValues.push(date.getDate() + '.' + (date.getMonth() + 1) + '.');
    }

    console.log(xValues);
    console.log(yValues);

    new Chart("views-chart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                data: yValues,
                borderColor: "black",
                fill: false
            }]
        },
        options: {
            legend: {display: false}
        }
    });
</script>
<?= $this->endSection() ?>

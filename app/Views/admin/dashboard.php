<?= $this->extend('layouts/admin') ?>

<?= $this->section('header') ?>
<link rel="stylesheet" href="<?= base_url('public/css/dashboard.css') ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page">
    <article class="dashboard">
        <section>
            <h2>Views in past 30 days</h2>
            <canvas class="dashboard__views" id="views-chart"></canvas>
        </section>

        <section>
            <h2>Most Viewed</h2>
            <ol class="dashboard__most-viewed">
                <?php foreach ($materials as $m) : ?>
                <li class="dashboard__material" onclick="window.location.href='<?= base_url('single/' . $m->id) ?>'">
                    <img src="<?= $m->getThumbnail()->getURL() ?>" alt="material thumbnail" />
                    <p class="dashboard__subtitle"><?= $m->title ?><p>
                    <strong><?= $m->views ?> view<?= $m->views !== 1 ? 's' : ''?></strong>
                </li>
                <?php endforeach; ?>
            </ol>
        </section>

        <section>
            <h2>Newest</h2>
            <ul class="dashboard__recent">
                <?php foreach ($recentNew as $r) : ?>
                <li class="dashboard__material">
                    <p class="dashboard__subtitle"><?= $r->title ?><p>
                    <strong><?= $r->created_at ?></strong>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section>
            <h2>Recently updated</h2>
            <ul class="dashboard__recent">
                <?php foreach ($recentUpdated as $r) : ?>
                <li class="dashboard__material">
                    <p class="dashboard__subtitle"><?= $r->title ?><p>
                    <strong><?= $r->updated_at ?></strong>
                </li>
                <?php endforeach; ?>
            </ul>
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
    </article>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    const yValues = <?= json_encode($viewHistory) ?>;
    while (yValues.length < 30) {
        yValues.push(0);
    }
    let xValues = [];
    for (let i = 29; i >= 0; i--) {
        let date = new Date();
        date.setDate(date.getDate() - i);
        xValues.push(date.getDate() + '.' + date.getMonth() + '.');
    }

    console.log(xValues);
    console.log(yValues);

    new Chart("views-chart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                data: yValues,
                borderColor: "red",
                fill: false
            }]
        },
        options: {
            legend: {display: false}
        }
    });
</script>
<?= $this->endSection() ?>
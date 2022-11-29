<!-- MATERIAL DISPLAYED AS A TABLE ITEM -->
<tr style="height: 100px">
    <th scope="row"><input type="checkbox" name="<?= $material->id ?>" id="<?= $material->id ?>" style="width: 20px; height: 20px"></th>
    <td><img src="<?= $material->getThumbnail() ?>" class="rounded" style="width: 100px; height: 100px" alt="missing_img"></td>
    <td><?= $material->title ?></td>
    <td><small>Last update:</small><br> <?= $material->createdToDate() ?></td>
    <td><?= $material->views ?></td>
    <td><?= $material->rating ?></td>
    <td><?= $material->rating_count ?>x</td>
</tr>

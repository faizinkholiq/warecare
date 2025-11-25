<table border="0.5" cellpadding="10">
    <tr>
        <th width="50%" align="center" valign="middle" height="40"><b>Sebelum</b></th>
        <th width="50%" align="center" valign="middle" height="40"><b>Sesudah</b></th>
    </tr>
    <?php if (empty($evidence_works)): ?>
        <tr>
            <td colspan="2" align="center" valign="middle">Belum ada gambar yang diupload</td>
        </tr>
    <?php else: ?>
        <?php foreach ($evidence_works as $key => $value): ?>
            <tr>
                <td valign="middle" align="center">
                    <?php
                    if (!empty($value['image_name_before'])) {
                    ?>
                        <img src="<?= FCPATH . 'uploads/' . $value['image_name_before'] ?>" alt="evidence_before" style="height: 200px; width: auto;">
                    <?php
                    } else {
                    ?>
                        - Tidak ada eviden -
                    <?php
                    }
                    ?>
                </td>
                <td valign="middle" align="center">
                    <?php
                    if (!empty($value['image_name_after'])) {
                    ?>
                        <img src="<?= FCPATH . 'uploads/' . $value['image_name_after'] ?>" alt="evidence_after" style="height: 200px; width: auto;">
                    <?php
                    } else {
                    ?>
                        - Tidak ada eviden -
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td valign="middle" align="center"><?= $value['description_before'] ?></td>
                <td valign="middle" align="center"><?= $value['description_after'] ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
<table border="0.5" cellpadding="10">
    <tr>
        <th width="50%" align="center" valign="middle" height="40">Sebelum</th>
        <th width="50%" align="center" valign="middle" height="40">Sesudah</th>
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
                        <div>
                            <img src="<?= FCPATH . 'uploads/' . $value['image_name_before'] ?>" alt="evidence_before" style="max-width: 100%; max-height: 10px; height: auto;">
                            <div style="text-align: center; margin-top: 5px;"><?= $value['description_before'] ?></div>
                        </div>
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
                        <div>
                            <img src="<?= FCPATH . 'uploads/' . $value['image_name_after'] ?>" alt="evidence_after" style="max-width: 100%; max-height: 10px; height: auto;">
                            <div style="text-align: center; margin-top: 5px;"><?= $value['description_after'] ?></div>
                        </div>
                    <?php
                    } else {
                    ?>
                        - Tidak ada eviden -
                    <?php
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>
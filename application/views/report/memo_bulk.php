<div>
    <h3 style="text-align: center;"><?= $title ?></h3>
</div>
<table border="0.5" cellpadding="10" cellspacing="0" style="font-size: 12px;">
    <tr>
        <th width="15%" align="center" valign="middle" height="40">Tgl. Pengaduan</th>
        <th width="20%" align="center" valign="middle" height="40">Gudang Blok</th>
        <th width="35%" align="center" valign="middle" height="40">Keterangan</th>
        <th width="15%" align="center" valign="middle" height="40">Tgl. Selesai</th>
        <th width="15%" align="center" valign="middle" height="40">Paraf Kontraktor</th>
    </tr>
    <?php
    foreach ($reports as $report):
        $description = isset($report['description']) && !empty($report['description']) ? nl2br($report['description']) : '';
        if (in_array($report['category_id'], $category_with_detail)) {
            if (!empty($report['details'])) {
                $description = "<ul>";
                $has_open_nested = false;
                $has_open_parent = false;

                foreach ($report['details'] as $detail) {
                    if ($detail["level"] == 1) {
                        // Close previous nested list if open
                        if ($has_open_nested) {
                            $description .= "</ul>";
                            $has_open_nested = false;
                        }
                        // Close previous parent item if open
                        if ($has_open_parent) {
                            $description .= "</li>";
                        }

                        $description .= "<li>" . $detail['description'];
                        $has_open_parent = true;
                    } else if ($detail["level"] == 2) {
                        // Open nested list if not already open
                        if (!$has_open_nested) {
                            $description .= "<ul>";
                            $has_open_nested = true;
                        }
                        $description .= "<li>" . $detail['description'] . "</li>";
                    }
                }

                // Close any remaining open tags
                if ($has_open_nested) {
                    $description .= "</ul>";
                }
                if ($has_open_parent) {
                    $description .= "</li>";
                }

                $description .= "</ul>";
            }
        }
    ?>
        <tr>
            <td valign="middle"><?= $report['created_at'] ?></td>
            <td valign="middle"><?= $report['warehouse'] ?></td>
            <td valign="middle"><?= $description ?></td>
            <td valign="middle"><?= $report['completed_at'] ?></td>
            <td valign="middle"></td>
        </tr>
    <?php endforeach; ?>
</table>
<br><br><br><br><br><br>
<table width="100%">
    <tr>
        <td width="25%" align="center">Mengetahui,</td>
        <td width="50%" align="center"></td>
        <td width="25%" align="center">Kontraktor,</td>
    </tr>
    <tr>
        <td height="80" align="center"></td>
        <td height="80" align="center"></td>
        <td height="80" align="center"></td>
    </tr>
    <tr>
        <td align="center">Henny Susanto</td>
        <td align="center">Hari Djijanto</td>
        <td align="center">(_________________)</td>
    </tr>
</table>
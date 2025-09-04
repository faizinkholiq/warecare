<h2 style="text-align: center; text-decoration: underline;"><?= $title ?></h2>
<br><br>
<table border="1" cellpadding="5">
    <tr style="background-color: #f8f9fa;">
        <th width="20%" align="center">Gudang</th>
        <th width="50%" align="center">Detail Komplain</th>
        <th width="15%" align="center">Tanggal Selesai</th>
        <th width="15%" align="center">Paraf Kontraktor</th>
    </tr>
    <tr>
        <td><?= $report['warehouse'] ?></td>
        <td><?= $report['description'] ?></td>
        <td><?= $report['completed_at'] ?></td>
        <td></td>
    </tr>
</table>
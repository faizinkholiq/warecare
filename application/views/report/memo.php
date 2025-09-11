<div>
    <h3 style="text-align: center;"><?= $title ?></h3>
</div>
<table border="0.5" cellpadding="10" cellspacing="0">
    <tr>
        <th width="20%" align="center" valign="middle" height="40">Gudang Blok</th>
        <th width="50%" align="center" valign="middle" height="40">Keterangan</th>
        <th width="15%" align="center" valign="middle" height="40">Tanggal Selesai</th>
        <th width="15%" align="center" valign="middle" height="40">Paraf Kontraktor</th>
    </tr>
    <tr>
        <td valign="middle"><?= $report['warehouse'] ?></td>
        <td valign="middle"><?= $report['description'] ?></td>
        <td valign="middle"><?= $report['completed_at'] ?></td>
        <td valign="middle"></td>
    </tr>
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
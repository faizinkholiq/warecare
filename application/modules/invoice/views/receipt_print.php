<!DOCTYPE html>
<html>

<head>
    <title><?php echo $filename; ?></title>
</head>
<style type="text/css">
    @page {
        margin-top: 48pt;
        margin-bottom: 40px;
        margin-left: 0px;
        margin-right: 0px;
        margin-header: 0px;
        background: linear-gradient(to right, #E7E6E6 0%, #E7E6E6 45%, #FFFFFF 45%, #FFFFFF);
        header: html_purchaseheader;
    }

    .header-container {
        background-color: #FFFFFF;
    }


    /* ---------- Invoice Table ---------- */
    table.invoice-table {
        border-collapse: collapse;
        font-size: 11pt;
    }

    table.invoice-table th {
        padding: 5px;
        background-color: #EB000E;
        color: #FFFFFF;
    }

    table.invoice-table th:nth-child(n+3) {
        background-color: #000000;
    }

    table.invoice-table td {
        padding: 5px;
    }

    table.invoice-table tbody tr:nth-child(odd) td {
        background-color: #FFFFFF;
    }

    table.invoice-table tbody tr:nth-child(even) td {
        background-color: #F2F2F2;
    }

    table.invoice-table tbody tr.total-row td {
        background-color: #FFFFFF;
    }

    table.invoice-table tbody tr.grand-total-row td {
        font-weight: bold;
        background-color: #EB000E;
        color: #FFFFFF;
    }

    table.invoice-table tbody tr.grand-total-row {
        font-weight: bold;
        background-color: #EB000E;
        color: #FFFFFF;
    }

    /* ---------- ./Invoice Table ---------- */

    /* ---------- Footer Table ---------- */
    table.footer-table {
        width: 505px;
        font-size: 12px;
        border-collapse: collapse;
    }

    table.footer-table tr:nth-child(1) td {
        height: 80px;
    }

    table.footer-table tr:nth-child(2) td {
        font-weight: bold;
    }

    /* ---------- ./Footer Table ---------- */
</style>

<body>
    <!-- Header -->
    <htmlpageheader name="purchaseheader" style="display: none;">
        <div class="header-container">
            <div style="float: left; width: 520px;">
                <img src="assets/images/logo.png" style="width: 350px;">
            </div>
            <div style="float: right; width: 160px; padding: 30px; line-height: 14px; text-align: right;">
                <span style="font-size: 14px; font-weight: bold;">{PAGENO}</span>
            </div>
        </div>
    </htmlpageheader>

    <table style="width: 98%; border-collapse: collapse; margin-left: 2%;">
        <tr>
            <td width="58%" valign="top">
                <div style="font-size: 38pt; font-weight: bold; color: #EB000E;">&nbsp;</div>
                <div style="font-size: 14pt; font-weight: bold;">PT INTERNATIONAL AKATSUKI BUSINESS</div>
                <div>Business Park Kebon Jeruk, Blok H 1-2</div>
                <div>Jalan Raya Meruya Ilir Nomor 88</div>
                <div>Desa/Kelurahan Meruya Utara</div>
                <div>Kec. Kembangan, Kota Adm. Jakarta Barat</div>
                <div>Provinsi DKI Jakarta, Kode Pos 11620</div>
            </td>
            <td width="42%" valign="top">
                <div style="font-size: 24pt; font-weight: bold; color: #EB000E;">RECEIPT</div>
                <table style="width: 100%; border-collapse: collapse; margin-top: 14pt; font-size: 11pt; color: #000000;">
                    <tr>
                        <td width="50%">Receipt Number</td>
                        <td width="50%" style="text-align: right;">1|05062024|INV|IDR</td>
                    </tr>
                    <tr>
                        <td>Receipt Date</td>
                        <td style="text-align: right;">05/06/2024</td>
                    </tr>
                    <tr>
                        <td>Receipt Currency</td>
                        <td style="text-align: right; font-weight: bold; color: #EB000E;">IDR</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 98%; border-collapse: collapse; margin-top: 48pt; margin-left: 2%; font-size: 11pt;">
        <tr>
            <td width="58%" valign="top">
                <div style="font-weight: bold;">Received From:</div>
                <div style="font-size: 14pt; font-weight: bold;">DAI YONGGE</div>
                <div>12/F TIMES Tower, 391-470</div>
                <div>Jafee road, Wanchai HK</div>
            </td>
            <td width="42%" valign="top">
                <div style="font-weight: bold;">Paid By:</div>
                <div>
                    <input type="checkbox" name="cash" id="cash">
                    <label for="cash">Cash</label>
                </div>
                <div>
                    <input type="checkbox" name="cheque" id="cheque">
                    <label for="cheque">Cheque</label>
                </div>
                <div>
                    <input type="checkbox" name="transfer" id="transfer">
                    <label for="transfer">Transfer</label>
                </div>
            </td>
        </tr>
    </table>

    <table class="invoice-table" style="width: 98%; margin-top: 48pt; margin-left: 2%;">
        <thead>
            <tr>
                <th width="8%">NO</th>
                <th width="50%">DESCRIPTION</th>
                <th width="10%">QTY</th>
                <th width="16%">PRICE</th>
                <th width="16%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $num = 1;
            $sub_total = 0;
            $tax_rate = 0;
            $other = 0;
            foreach($data as $key => $value): 
                $sub_total+= $value['sub_total'];
            ?>
            <tr>
                <td style="text-align: center;"><?= $num++ ?></td>
                <td><?= $value['description'] ?></td>
                <td style="text-align: center;"><?= $value['qty'] ?></td>
                <td style="text-align: right;"><?= $value['price'] ?></td>
                <td style="text-align: right;"><?= rupiah($value['sub_total']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="2" rowspan="5" style="background: none; padding-left: 96pt;">
                    <div style="font-weight: bold;">Status:</div>
                    <div style="font-size: 20pt; font-weight: bold; color: #EB000E;">PAID</div>
                </td>
                <td>SUB TOTAL</td>
                <td style="text-align: right;">IDR</td>
                <td style="text-align: right;"><?= rupiah($sub_total) ?></td>
            </tr>
            <tr class="total-row">
                <td>TAX RATE</td>
                <td style="text-align: right;">IDR</td>
                <td style="text-align: right;">-</td>
            </tr>
            <tr class="total-row">
                <td>OTHER FEE</td>
                <td style="text-align: right;">IDR</td>
                <td style="text-align: right;">-</td>
            </tr>
            <tr class="total-row">
                <td>AMOUNT PAID</td>
                <td style="text-align: right;">IDR</td>
                <td style="text-align: right;">-</td>
            </tr>
            <tr class="grand-total-row">
                <td>RECEIVED</td>
                <td style="text-align: right;">IDR</td>
                <td style="text-align: right;"><?= rupiah($sub_total - $tax_rate - $other) ?></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
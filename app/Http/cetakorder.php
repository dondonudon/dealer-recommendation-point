<?php
    $sql = "select * from tb_user left JOIN tb_order on tb_user.iduser = tb_order.idpembeli WHERE noorder = '".$_REQUEST['noorder']."' union select * from tb_user left JOIN tb_preorder on tb_user.iduser = tb_preorder.idpembeli WHERE noorder = '".$_REQUEST['noorder']."'";
    $profile = mysql_fetch_array(mysql_query($sql));
?>
<script type="text/javascript" src="system/myJs/checkout.js"></script>
<div class="container-fluid" style="padding: 10px;">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget dark">
                <form action="home.php?page=dealers-modify" id="Form" method="POST">
                    <span id="post"></span>
                    <div class="widget-title">
                        <h4 class="h4Icon"><i class="icon-reorder"></i></h4>
                        <h4 class="h4Title">Billing Details</h4>
                        <span class="tools">
                            <button type="button" class="btn btn-mini btn-success" id="btnCetaks" onclick="printDiv();">
                                <i class="icon-ok icon-white"></i> Cetak
                            </button>
                        </span>
                    </div>
                    <div id="areaCetakOrder" class="orders">
                        <div class="widget-body">
                            <div class="row-fluid">
                                <img src="../logo/Logo_Infinity.png" height="42" width="42">
                                <strong>CV INFINITY CORPORA</strong>
                                <br />
                            </div>
                            <div class="form-group">
                                <TABLE>
                                    <tr>
                                        <td>Nama Customer</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><span class="price black"><?php echo $profile['nama'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><span class="price black"><?php echo $profile['alamat'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>NPWP</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><span class="price black"><?php echo $profile['npwp'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td>No HP</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><span class="price black"><?php echo $profile['notelp'] ?></span></td>
                                    </tr>
                                
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td>Tanggal</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><?php $sql = "select TglTransaksi from tb_order where noorder = '".$_REQUEST['noorder']."'"; $cek = mysql_fetch_array(mysql_query($sql)); ?>
                                            <span class="price black"><?php echo $cek['TglTransaksi'] ?></span>
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td>Log</td>
                                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                        <td><?php 
                                        $sql1 = "select sys_user.NamaLengkap as namaadmin from sys_user left join tb_order on sys_user.IdUser = tb_order.acc where noorder = '".$_REQUEST['noorder']."'"; 
                                        $cek1 = mysql_fetch_array(mysql_query($sql1)); ?>
                                    <span class="price black"><?php echo $cek1['namaadmin'] ?></span>    </td>
                                    </tr> -->
                                    <tr>
                                    </tr>
                                </TABLE>
                            </div>
                            <br><br>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Item</th>
                                        <th>Nama Barang</th>
                                        <th>Merk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Disc Barang (%)</th>
                                        <th>Disc Sales (%)</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $cek = mysql_num_rows(mysql_query("select noorder from tb_order where noorder='".$_REQUEST['noorder']."'"));
                                        if ($cek>0) {
                                            $sql = "SELECT a.kodeItem,namamerk,a.namabarang,merkmobil,namamobil,Stok,
                                            f.qtypesan as qty,f.HargaJual,f.dscBrg,f.dscSales,f.qtypesan*(f.HargaJual-(f.dscBrg+f.dscSales)) as subtotal 
                                            from tb_orderdtl f  inner join tb_produk a on a.kodeItem=f.kodeItem
                                            left join tb_produk_merk b on a.Idmerk = b.Idmerk
                                            left join tb_partmobil c on c.kodeitem = a.kodeItem
                                            left join tb_mobil d on d.idmobil = c.idmobil
                                            left join tb_merkmobil e on e.idmerkmobil = d.idmerkmobil where f.noorder='".$_REQUEST['noorder']."'";
                                        } else {
                                            $sql = "SELECT a.kodeItem,namamerk,a.namabarang,merkmobil,namamobil,Stok,
                                            f.qtypesan as qty,f.HargaJual,(f.dscBrg+f.dscSales) as diskonproduk,f.qtypesan*(f.HargaJual-(f.dscBrg+f.dscSales)) as subtotal 
                                            from tb_preorderdtl f  inner join tb_produk a on a.kodeItem=f.kodeItem
                                            left join tb_produk_merk b on a.Idmerk = b.Idmerk
                                            left join tb_partmobil c on c.kodeitem = a.kodeItem
                                            left join tb_mobil d on d.idmobil = c.idmobil
                                            left join tb_merkmobil e on e.idmerkmobil = d.idmerkmobil where f.noorder='".$_REQUEST['noorder']."'";
                                        }
                                          
                                        $rsl = mysql_query($sql);

                                        $subtotal = 0;

                                        $voucher['nilai']=0;
                                        $a =1;
                                        while ($dt_shop = mysql_fetch_array($rsl)) {
                                            $subtotal += $dt_shop['subtotal'];
                                            $dic1 = ($dt_shop['dscBrg']/$dt_shop['HargaJual'])*100;
                                            $dic2 = ($dt_shop['dscSales']/($dt_shop['HargaJual']-$dt_shop['dscBrg']))*100;
                                            echo "
                                                <tr>
                                                    <td class='product-name'>$dt_shop[kodeItem]</td>
                                                    <td class='product-name'>$dt_shop[namabarang]</td>
                                                    <td class='product-name'>$dt_shop[namamerk]</td>
                                                    <td align='center' class='product-quantity'>$dt_shop[qty]</td>
                                                    <td style='text-align:right' class='product-quantity'>Rp. ".number_format($dt_shop['HargaJual'],0,",",".")."</td>
                                                    <td style='text-align:right' class='product-quantity'>".$dic1."%</td>
                                                    <td style='text-align:right' class='product-quantity'>".$dic2."%</td>
                                                    <td style='text-align:right' class='product-quantity'>Rp. ".number_format($dt_shop['subtotal'],0,",",".")."</td>
                                                </tr>
                                            ";
                                        }

                                        
                                        $dsc = mysql_fetch_array(mysql_query("select diskonRp,diskonpersen from tb_order where noorder='".$_REQUEST['noorder']."'"));
                                        $diskon = ($subtotal*$dsc['diskonpersen'])/100;
                                        $grandTotal = $subtotal - $diskon - $voucher['nilai'];
                                        $disabled = (trim($profile['saldo'])=='0')?"disabled" : "";
                                    ?>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Subtotal</td>
                                        <td>Rp. <?php echo number_format($subtotal); ?></td>
                                    </tr>

                                    <tr>
                                        <td>Status</td>
                                        <td><?php 
                                            $sql = "SELECT
                                                    CASE
                                                        
                                                        WHEN
                                                        proses = 0 THEN
                                                        'TUNGGU PEMBAYARAN' 
                                                        WHEN
                                                        proses = 1 THEN
                                                        'TUNGGU KONFIRMASI' 
                                                        WHEN
                                                        proses = 2 THEN
                                                        'TUNGGU PENGIRIMAN' 
                                                        WHEN
                                                        proses = 3 THEN
                                                        'DALAM PENGIRIMAN'
                                                        WHEN
                                                        proses = 9 THEN
                                                        'TEMPO'
                                                        
                                                    END AS proses 
                                                    FROM
                                                        tb_order 
                                                    WHERE noorder = '".$_REQUEST['noorder']."'"; 
                                            $cek = mysql_fetch_array(mysql_query($sql)); ?>
                                            <?php echo $cek['proses']?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Diskon</td>
                                        <td>Rp. <?php echo number_format($diskon); ?></td>
                                    </tr>

                                    <tr>
                                        <td>Jatuh Tempo</td>
                                        <td><?php $sql = "select tgljatuhtempo from tb_order where noorder = '".$_REQUEST['noorder']."'"; $cek = mysql_fetch_array(mysql_query($sql)); ?>
                                            <?php echo $cek['tgljatuhtempo'] ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Voucher</td>
                                        <td>Rp. <?php echo $voucher['nilai']; ?></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>Grand Total</td>
                                        <td>Rp. <?php echo number_format($grandTotal); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="3">Harga sudah termasuk PPN (10%)</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
	// ini_set("memory_limit", "256M");
	require_once "../inc/config.php";
	$sql = "select * from tb_user left JOIN tb_order on tb_user.iduser = tb_order.idpembeli WHERE noorder = '".$_REQUEST['noorder']."' union select * from tb_user left JOIN tb_preorder on tb_user.iduser = tb_preorder.idpembeli WHERE noorder = '".$_REQUEST['noorder']."'";
    $profile = mysql_fetch_array(mysql_query($sql));

    $sql2 = "select TglTransaksi from tb_order where noorder = '".$_REQUEST['noorder']."'"; 
    $cek = mysql_fetch_array(mysql_query($sql2));
    $month = array('01' => 'Januari','02' => 'Februari','03' => 'Maret','04' => 'April','05' => 'Mei','06' => 'Juni','07' => 'Juli','08' => 'Agustus','09' => 'September','10' => 'Oktober','11' => 'Nopember','12' => 'Desember');
	
	$r = explode("-", $cek['TglTransaksi']);
	$s = explode("-", $profile['TglJatuhTempo']);
	if($profile['top']>0){
	    $tagihan = "Credit";
	} else {
	    $tagihan = "Paid";
	}
	
	if($cek['TglTransaksi']==$profile['TglJatuhTempo']){
	    $method = "Cash";
	} else {
	    $method = "Tempo";
	}
	$header ='
		<table style="font-size: 12px;" border="0" width="100%" cellspacing="0" cellpadding="0">
		    <tbody>
					<tr>
						<td rowspan="6" width="5px" style="background:#14a2e3">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td rowspan="6" style="text-align:right"><img src="https://infinitycorpora.com/corpora/assets/img/invoice/header.png" height="70px"></td>
					</tr>
					<tr>
						<td width="80px" style="padding-left:15px"><b>INVOICE</b></td>
						<td width="10px"></td>
						<td></td>
					</tr>
					<tr>
						<td style="padding-left:15px">Number</td>
						<td>:</td>
						<td>#'.$_REQUEST['noorder'].'</td>
					<tr>
					<tr>
						<td style="padding-left:15px">Date</td>
						<td>:</td>
						<td>'.$r[2].' '.$month[$r[1]].' '.$r[0].'</td>
					</tr>
			</tbody>
		</table>
	';
	$html .= '
		<table style="font-size: 12px;" border="0" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td rowspan="6" width="5px" style="background:#14a2e3;">&nbsp;</td>
                    <td colspan="3" style="padding-left:15px"><b>CUSTOMER DETAIL</b></td>
                    <td rowspan="6" width="5px" style="background:#14a2e3;">&nbsp;</td>
                    <td colspan="3" style="padding-left:15px"><b>PAYMENT DETAIL</b></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-left:15px">&nbsp;</td>
                    <td colspan="3" style="padding-left:15px">&nbsp;</td>
                </tr>
                <tr>
    				<td width="100px" style="padding-left:15px">Name</td>
    				<td width="10px">:</td>
    				<td style="padding-left:15px" width="250px">'.$profile['nama'].'</td>
    				<td width="100px" style="padding-left:15px">Method</td>
    				<td width="10px">:</td>
    				<td style="padding-left:15px" width="150px">'.$method.'</td>
    			</tr>
    			<tr>
    			    <td style="padding-left:15px">Address</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$profile['alamat'].'</td>
    			    <td style="padding-left:15px">Method info</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$profile['top'].' hari</td>
    			</tr>
    			<tr>
    			    <td style="padding-left:15px">Contact Number</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$profile['notelp'].'</td>
    			    <td style="padding-left:15px">Status</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$tagihan.'</td>
    			</tr>
    			<tr>
    			    <td style="padding-left:15px">NPWP</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$profile['npwp'].'</td>
    			    <td style="padding-left:15px">Jatuh Tempo</td>
    			    <td>:</td>
    			    <td style="padding-left:15px">'.$s[2].' '.$month[$s[1]].' '.$s[0].'</td>
    			</tr>
    		</tbody>
        </table>
        <br/><br/>
	';
	
	$html .= '
		<table style="font-size: 12px;" border="0" width="100%" cellspacing="0" cellpadding="0">
			<thead>
                <tr style="background: #f0f0f0;">
                	<th width="31px" height="30px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">No</th>
                    <th width="100px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Kode Item</th>
                    <th style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Nama Barang</th>
                    <th width="80px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Qty</th>
                    <th width="80px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Harga</th>
                    <th width="80px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Disc (%)</th>
                    <th width="80px" style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6">Nama Mobil</th>
                    <th width="80px" style="border-bottom: 1px solid #b7b5b6">Subtotal</th>
                </tr>
            </thead>
			<tbody>';
			$cek = mysql_num_rows(mysql_query("select noorder from tb_order where noorder='".$_REQUEST['noorder']."'"));
            if ($cek>0) {
                $sql = "SELECT a.kodeItem,namamerk,a.namabarang,a.Foto,Stok,
                f.qtypesan as qty,f.HargaJual,f.dscBrg,f.dscSales,f.qtypesan*(f.HargaJual-(f.dscBrg+f.dscSales)) as subtotal,nama_mobil 
                from tb_orderdtl f  inner join tb_produk a on a.kodeItem=f.kodeItem
                left join tb_produk_merk b on a.Idmerk = b.Idmerk
                 where f.noorder='".$_REQUEST['noorder']."'";
            } else {
                $sql = "SELECT a.kodeItem,namamerk,a.namabarang,a.Foto,Stok,
                f.qtypesan as qty,f.HargaJual,(f.dscBrg+f.dscSales) as diskonproduk,f.qtypesan*(f.HargaJual-(f.dscBrg+f.dscSales)) as subtotal,nama_mobil 
                from tb_preorderdtl f  inner join tb_produk a on a.kodeItem=f.kodeItem
                left join tb_produk_merk b on a.Idmerk = b.Idmerk
                 where f.noorder='".$_REQUEST['noorder']."'";
            }
              
            $rsl = mysql_query($sql);

            $subtotal = 0;

            $voucher['nilai']=0;
            $no =1;
            while ($dt_shop = mysql_fetch_array($rsl)) {
				$subtotal += $dt_shop['subtotal'];
                $dic1 = ($dt_shop['dscBrg']/$dt_shop['HargaJual'])*100;
                $dic2 = ($dt_shop['dscSales']/($dt_shop['HargaJual']-$dt_shop['dscBrg']))*100;
				$html .='
				<tr>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px">'.utf8_encode($no).'</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px">'.utf8_encode($dt_shop['kodeItem']).'</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px">'.utf8_encode($dt_shop['namabarang']).'</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;text-align:right;padding:5px">'.number_format($dt_shop['qty'],0,",",".").'</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;text-align:right;padding:5px">'.number_format($dt_shop['HargaJual'],0,",",".").'</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;text-align:right;padding:5px">'.number_format($dic1,1,",",".").' %</td>
					<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;;padding:5px">'.$dt_shop['nama_mobil'].'</td>
					<td style="text-align:right;border-bottom: 1px solid #b7b5b6;padding:5px">'.number_format($dt_shop['subtotal'],0,",",".").'</td>
				</tr>';
				$no++;
			}
			$dsc = mysql_fetch_array(mysql_query("select diskonRp,diskonpersen from tb_order where noorder='".$_REQUEST['noorder']."'"));
            $diskon = ($subtotal*$dsc['diskonpersen'])/100;
            $grandTotal = $subtotal - $diskon - $voucher['nilai'];
            $disabled = (trim($profile['saldo'])=='0')?"disabled" : "";
	$html .='
			<tr>
				<td colspan="5"></td>
				<td style="border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">Sub Total</td>
				<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">&nbsp;</td>
				<td style="text-align:right;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">'.number_format($subtotal,0,",",".").'</td>
			</tr>
			<tr>
				<td colspan="5"></td>
				<td style="border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">Disc</td>
				<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">&nbsp;</td>
				<td style="text-align:right;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">'.number_format($diskon,0,",",".").'</td>
			</tr>
			<tr>
				<td colspan="5" style="border-bottom: 1px solid #b7b5b6;"></td>
				<td style="border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">Voucher</td>
				<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">&nbsp;</td>
				<td style="text-align:right;border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">'.number_format($voucher['nilai'],0,",",".").'</td>
			</tr>
			<tr>
				<td colspan="5" style="background:#98dfff;border-bottom: 1px solid #b7b5b6;padding:5px;">Harga sudah termasuk PPN (10%)</td>
				<td style="border-bottom: 1px solid #b7b5b6;padding:5px;background:#98dfff;">Grand Total</td>
				<td style="border-right: 1px solid #b7b5b6;border-bottom: 1px solid #b7b5b6;padding:5px;background:#98dfff;">&nbsp;</td>
				<td style="text-align:right;border-bottom: 1px solid #b7b5b6;padding:5px;background:#98dfff;">'.number_format($grandTotal,0,",",".").'</td>
			</tr>
			<tr>
				<td colspan="8" style="border-bottom: 1px solid #b7b5b6;padding:5px;background:#f0f0f0;">Terbilang : '.Terbilang($grandTotal).' Rupiah</td>
			</tr>
			</tbody>
		</table>
		<br/><br/><br/>';

	if($tagihan=='Paid'){
	    $footer .= '<img width="150px" height="150px" src="https://infinitycorpora.com/corpora/assets/img/invoice/status%20bayar.png">';
	} else {
	    $footer .= '<img width="150px" height="150px" src="https://infinitycorpora.com/corpora/assets/img/invoice/Untitled-1.png">';
	}
	$footer .= '<img src="https://infinitycorpora.com/corpora/assets/img/invoice/footer.png">';
	//==============================================================
	//==============================================================
	//==============================================================
	include("../../../_pdf/pdf/mpdf.php");

	$mpdf=new mPDF('c','A4','','',10,10,40,15,10,13); // jika tampilan ingin landscape di ubah menjadi A4-L 
	// Angka diatas 20,20,40,20,20,10 => kiri,kanan,header,bottom,top, footer


	$mpdf->SetDisplayMode('fullpage');

	$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list

	// LOAD a stylesheet
	$stylesheet = file_get_contents('pdf/mpdfstyletables2.css');
	$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($html,2);
	$mpdf->SetHTMLFooter($footer) ;
	//$mpdf->AddPage('L','','','','',25,25,55,45,18,12);
	$mpdf->Output('invoice.pdf','I'); // Nama File ketika pdf di download
	exit;
	//==============================================================
	//==============================================================
	//==============================================================
	// echo $header;
	echo $html;
	// echo $footer;
?>
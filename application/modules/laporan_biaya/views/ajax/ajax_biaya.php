<table class="table">
	<tr bgcolor="#D3D3D3" style="color:black">
		<th width="10%">PERIODE</th>
		<th width="90%" colspan="5" class="text-right"><?= $filter_date;?></th>
	</tr>
	<tr>
		<th class="text-center" width="10%" style="padding-left:20px;">Tanggal</th>
		<th class="text-center" width="5%">Transaksi</th>
		<th class="text-center" width="20%">Kategori</th>
		<th class="text-center" width="15%">Nomor Transaksi</th>
		<th class="text-center" width="35%">Keterangan</th>
		<th class="text-center" width="15%" class="text-right">Jumlah</th>
	</tr>
	<tr class="active">
		<th class="text-left" colspan="6">Biaya Overhead Produksi</th>
	</tr>
	<?php
	$total_biaya_langsung = 0;
	if(!empty($biaya_langsung)){
		foreach ($biaya_langsung as $key => $bl) {
			$total_parent = $this->m_laporan->getTotal($bl['coa_id'],$filter_date);
			?>
			<tr>
				<td><?= $bl['tanggal_transaksi'];?></td>
				<td>BIAYA</td>
				<td><?= $bl['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/biaya/detail_biaya/' . $bl["id"]) .'" target="_blank">'. $bl["nomor_transaksi"] . "</a>";?></td>
				<td><?= $bl['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($bl['total']);?></td>
			</tr>
			<?php
			$total_biaya_langsung += $bl['total'];
			
		}
	}
	$total_biaya_langsung_jurnal = 0;
	$grand_total_biaya_langsung = $total_biaya_langsung;
	if(!empty($biaya_langsung_jurnal)){
		foreach ($biaya_langsung_jurnal as $key => $blj) {
			$total_parent = $this->m_laporan->getTotal($blj['coa_id'],$filter_date);
			?>	
			<tr>
				<td><?= $blj['tanggal_transaksi'];?></td>
				<td>JURNAL</td>
				<td><?= $blj['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/jurnal_umum/detailJurnal/' . $blj["id"]) .'" target="_blank">'. $blj["nomor_transaksi"] . "</a>";?></td>
				<td><?= $blj['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($blj['total']);?></td>
			</tr>
			<?php
			$total_biaya_langsung_jurnal += $blj['total'];		
		}
	}
	$total_a = $grand_total_biaya_langsung + $total_biaya_langsung_jurnal;
	?>
	<tr class="active">
		<td width="80%" style="padding-left:20px;" colspan="5">Total Biaya Overhead Produksi</td>
		<td width="20%" class="text-right"><b><?= $this->filter->Rupiah($total_a);?></b></td>
	</tr>
	<tr>
		<th colspan="7"></th>
	</tr>
	<tr class="active">
		<th class="text-left" colspan="6">Biaya Umum & Administrasi</th>
	</tr>
	<?php
	$total_biaya = 0;
	if(!empty($biaya)){
		foreach ($biaya as $key => $row) {
			$total_parent = $this->m_laporan->getTotal($row['coa_id'],$filter_date);
			?>
			<tr>
				<td><?= $row['tanggal_transaksi'];?></td>
				<td>BIAYA</td>
				<td><?= $row['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/biaya/detail_biaya/' . $row["id"]) .'" target="_blank">'. $row["nomor_transaksi"] . "</a>";?></td>
				<td><?= $row['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($row['total']);?></td>
			</tr>
			<?php
			$total_biaya += $row['total'];		
		}
	}
	$total_biaya_jurnal = 0;
	$grand_total_biaya = $total_biaya;
	if(!empty($biaya_jurnal)){
		foreach ($biaya_jurnal as $key => $row2) {
			$total_parent = $this->m_laporan->getTotal($row2['coa_id'],$filter_date);
			?>
			<tr>
				<td><?= $row2['tanggal_transaksi'];?></td>
				<td>JURNAL</td>
				<td><?= $row2['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/jurnal_umum/detailJurnal/' . $row2["id"]) .'" target="_blank">'. $row2["nomor_transaksi"] . "</a>";?></td>
				<td><?= $row2['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($row2['total']);?></td>
			</tr>
			<?php
			$total_biaya_jurnal += $row2['total'];		
		}
	}
	$total_b = $grand_total_biaya + $total_biaya_jurnal;
	?>
	<tr class="active">
		<td width="80%" style="padding-left:20px;" colspan="5">Total Biaya Umum & Administrasi</td>
		<td width="20%" class="text-right"><b><?= $this->filter->Rupiah($total_b);?></b></td>
	</tr>
	<tr>
		<th colspan="6"></th>
	</tr>
	<tr class="active">
		<th class="text-left" colspan="6">Biaya Lain - Lain</th>
	</tr>
	<?php
	$total_biaya_lainnya = 0;
	if(!empty($biaya_lainnya)){
		foreach ($biaya_lainnya as $key => $row) {
			$total_parent = $this->m_laporan->getTotal($row['coa_id'],$filter_date);
			?>
			<tr>
			<td><?= $row['tanggal_transaksi'];?></td>
				<td>BIAYA</td>
				<td><?= $row['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/biaya/detail_biaya/' . $row["id"]) .'" target="_blank">'. $row["nomor_transaksi"] . "</a>";?></td>
				<td><?= $row['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($row['total'] + $total_parent);?></td>
			</tr>
			<?php
			$total_biaya_lainnya += $row['total'] + $total_parent;
		}
	}
	$total_biaya_lainnya_jurnal = 0;
	$grand_total_biaya_lainnya = $total_biaya_lainnya;
	if(!empty($biaya_lainnya_jurnal)){
		foreach ($biaya_lainnya as $key => $row2) {
			$total_parent = $this->m_laporan->getTotal($row['coa_id'],$filter_date);
			?>
			<tr>
			<td><?= $row2['tanggal_transaksi'];?></td>
				<td>JURNAL</td>
				<td><?= $row2['coa'];?></td>
				<td><?= "<a href=" . base_url('pmm/jurnal_umum/detailJurnal/' . $row2["id"]) .'" target="_blank">'. $row2["nomor_transaksi"] . "</a>";?></td>
				<td><?= $row2['deskripsi'];?></td>
				<td class="text-right"><?= $this->filter->Rupiah($row2['total'] + $total_parent);?></td>
			</tr>
			<?php
			$total_biaya_lainnya_jurnal += $row2['total'] + $total_parent;
		}
	}
	$total_c = $grand_total_biaya_lainnya + $total_biaya_lainnya_jurnal;
	?>
	<tr class="active">
		<td width="80%" style="padding-left:20px;" colspan="5">Total Biaya Lain - Lain</td>
		<td width="20%" class="text-right"><b><?= $this->filter->Rupiah($total_c);?></b></td>
	</tr>
</table>
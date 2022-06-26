<?php
function convertDateDBtoIndo($string){
    // contoh : 30/01/2019
    
    $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];
 
    $tanggal = explode("-", $string)[2];
    $bulan = explode("-", $string)[1];
    $tahun = explode("-", $string)[0];
 
    return $tanggal . " " . $bulanIndo[abs($bulan)] . " " . $tahun;
}
?>
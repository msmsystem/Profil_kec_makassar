<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['default_controller'] = 'modul';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['po'] = "modul/get_grid/po";
$route['histori-po'] = "modul/get_grid/histori_po";
$route['pengajuan-po'] = "modul/get_grid/pengajuan_po";
$route['pengajuan-anggran'] = "modul/get_grid/pengajuan_anggaran";
$route['histori-pengajuan'] = "modul/get_grid/histori_pengajuan";
$route['histori-pengajuan-po'] = "modul/get_grid/histori_pengajuan_po";
$route['pemasukan-anggran'] = "modul/get_grid/pemasukan_anggaran";
$route['dashboard'] = "modul/get_konten/dashboard";
$route['m-project'] = "modul/get_grid/project";
$route['m-lokasi'] = "modul/get_grid/lokasi";
$route['m-barang'] = "modul/get_grid/barang";
$route['m-jabatan'] = "modul/get_grid/jabatan";
$route['m-katalog'] = "modul/get_grid/cl_vendor";
$route['m-departemen'] = "modul/get_grid/departemen";
$route['m-user'] = "modul/get_grid/user";
$route['m-user-group'] = "modul/get_grid/user_group";
$route['laporan-pengajuan'] = "modul/laporan/pengajuan";
$route['laporan-laba-rugi'] = "modul/laporan/laba_rugi";


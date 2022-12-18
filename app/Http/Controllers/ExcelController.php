<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

use App\Exports\ReportPenjualanSalesExport;
use App\Exports\ReportPenjualanBersihExport;
use App\Exports\ReportRincianKasExport;
use App\Exports\ReportPenerimaanPelanggan;
use App\Exports\ReportAnalisaPenjualan;
use App\Exports\ReportPiutangUmur;
use App\Exports\ReportLabaRugi;


use App\Models\CashAccount;

class ExcelController extends Controller
{
    public function penjualanSalesExport(Request $request) 
    {
        return (new ReportPenjualanSalesExport($request))->download('penjualan-per-sales.xlsx');
    }

    public function penjualanBersihExport(Request $request) 
    {
        return (new ReportPenjualanBersihExport($request))->download('rekap-penjualan-bersih.xlsx');
    }

    public function rincianKasExport(Request $request) 
    {
        $kas = CashAccount::find($request->kas);
        $nama_kas = str_replace(" ", "-", strtolower($kas->nama));
        return (new ReportRincianKasExport($request))->download('rincian-kas-'.$nama_kas.'.xlsx');
    }

    public function penerimaanPelangganExport(Request $request) 
    {
        return (new ReportPenerimaanPelanggan($request))->download('penerimaan-pelanggan.xlsx');
    }
    
    public function analisaPenjualanExport(Request $request) 
    {
        return (new ReportAnalisaPenjualan($request))->download('analisa-penjualan.xlsx');
    }

    public function piutangUmurPiutangExport(Request $request) 
    {
        return (new ReportPiutangUmur($request))->download('piutang-umur-piutang.xlsx');
    }    

    public function labaRugiExport(Request $request) 
    {
        return (new ReportLabaRugi($request))->download('laba-rugi.xlsx');
    }    
    
}

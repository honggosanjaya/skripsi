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

    public function rincianKasExport(Request $request, $id) 
    {
        $kas = CashAccount::find($id);
        $nama_kas = str_replace(" ", "-", strtolower($kas->nama));
        return (new ReportRincianKasExport($request))->download('rincian-kas-'.$nama_kas.'.xlsx');
    }

    public function penerimaanPelangganExport(Request $request) 
    {
        return (new ReportPenerimaanPelanggan($request))->download('penerimaan-pelanggan.xlsx');
    }
    
}

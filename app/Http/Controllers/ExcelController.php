<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

use App\Exports\ReportPenjualanSalesExport;
use App\Exports\ReportPenjualanBersihExport;

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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Exports\ReportPenjualanSalesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class ExcelController extends Controller
{
    public function penjualanSalesExport(Request $request) 
    {
        return (new ReportPenjualanSalesExport($request))->download('penjualan-per-sales.xlsx');
    }

}

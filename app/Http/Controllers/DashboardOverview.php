<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardOverview extends Controller
{
    private function permission() {
        if (Session::exists('username')) {
            return 'true';
        } else {
            return 'login';
        }
    }

    public function index() {
        $check = $this->permission();

        if ($check == 'true') {
            return view('dashboard.overview');
        } else {
            return redirect('login');
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date.' 23:50:00';
        try {
            $data['sales_prospect'][] = DB::table('sales_prospect')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['sales_prospect'][] = DB::table('sales_prospect')
                ->where('status_fu','=','0')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['sales_prospect'][] = DB::table('sales_prospect')
                ->where('status_fu','=','3')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['sales_prospect'][] = DB::table('sales_prospect')
                ->where('status_fu','=','2')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['sales_prospect'][] = DB::table('sales_prospect')
                ->where('status_fu','=','1')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();

            $data['booking_gr'][] = DB::table('booking_gr_mst')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['booking_gr'][] = DB::table('booking_gr_mst')
                ->where('status_fu','=','0')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['booking_gr'][] = DB::table('booking_gr_mst')
                ->where('status_fu','=','3')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['booking_gr'][] = DB::table('booking_gr_mst')
                ->where('status_fu','=','2')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['booking_gr'][] = DB::table('booking_gr_mst')
                ->where('status_fu','=','1')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();

            $data['bp_estimation'][] = DB::table('bp_estimation_mst')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['bp_estimation'][] = DB::table('bp_estimation_mst')
                ->where('status_fu','=','0')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['bp_estimation'][] = DB::table('bp_estimation_mst')
                ->where('status_fu','=','3')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['bp_estimation'][] = DB::table('bp_estimation_mst')
                ->where('status_fu','=','2')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();
            $data['bp_estimation'][] = DB::table('bp_estimation_mst')
                ->where('status_fu','=','1')
                ->whereBetween('created_at',[$startDate,$endDate])
                ->get()->count();

            return json_encode($data);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesProspectMonitoring extends Controller
{
    private function permission($segment) {
        if (Session::exists('username')) {
            $permission = DB::table('sys_permission')
                ->select('username','id_menu')
                ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
                ->where('sys_menu.segment_name','=',$segment);
            if ($permission->exists()) {
                return 'true';
            } else {
                return 'not available';
            }
        } else {
            return 'login';
        }
    }

    public function index(Request $request) {
        $segment = $request->segment(2);
        $permit = $this->permission($segment);

        switch ($permit) {
            case 'login':
                return redirect('login');
                break;

            case 'not available':
                return redirect('/');
                break;

            default:
                return view('dashboard.sales_prospect-monitoring');
                break;
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date.' 23:50:50';
        $statusFU = $request->status_fu;
        try {
            if ($statusFU == 'all') {
                $menu = DB::table('sales_prospect')
                    ->select('no_sales', 'nama_customer', 'no_telephone', 'model_kendaraan', 'kabupaten', 'kecamatan', 'alamat', 'pekerjaan', 'kebutuhan','salesman','status_fu','waktu_telp', 'username', 'created_at')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            } else {
                $menu = DB::table('sales_prospect')
                    ->select('no_sales', 'nama_customer', 'no_telephone', 'model_kendaraan', 'kabupaten', 'kecamatan', 'alamat', 'pekerjaan', 'kebutuhan','salesman','status_fu','waktu_telp', 'username', 'created_at')
                    ->where('status_fu', '=', $statusFU)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            }
            $result['data'] = $menu;
            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

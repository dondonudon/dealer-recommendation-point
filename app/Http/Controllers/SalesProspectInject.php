<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesProspectInject extends Controller
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
                return view('dashboard.sales_prospect-inject_prospect_to_salesman');
                break;
        }
    }

    public function inject(Request $request) {
        $data = json_decode($request->data);
        $salesman = $request->salesman;

        try {
            foreach ($data as $d) {
                DB::table('sales_prospect')
                    ->where('no_sales','=',$d->no_sales)
                    ->update([
                        'salesman' => $salesman
                    ]);
            }
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }

        return 'success';
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date.' 23:50:50';
        try {
            $menu = DB::table('sales_prospect')
                ->select('no_sales', 'nama_customer', 'no_telephone', 'model_kendaraan', 'kabupaten', 'kecamatan', 'alamat', 'pekerjaan', 'kebutuhan','salesman','status_fu','waktu_telp', 'username', 'created_at')
                ->where('salesman', '=', null)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $result['data'] = $menu;
            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

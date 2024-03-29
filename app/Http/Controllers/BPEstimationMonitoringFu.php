<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BPEstimationMonitoringFu extends Controller
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
                return view('dashboard.bpEstimation-monitoring-fu');
                break;
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date.' 23:50:00';
        $statusFU = $request->status_fu;
        try {
            if ($statusFU == 'all') {
                $menu = DB::table('bp_estimation_mst')
                    ->whereBetween('created_at',[$startDate,$endDate])
                    ->get();
            } else {
                $menu = DB::table('bp_estimation_mst')
                    ->where('status_fu','=',$statusFU)
                    ->whereBetween('created_at',[$startDate,$endDate])
                    ->get();
            }
            $result['data'] = $menu;

            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function updateFU(Request $request) {
        $hasilFU = $request->hasil_fu;
        $noEst = $request->no_estimasi;

        try {
            DB::table('bp_estimation_mst')
                ->where('no_estimation','=',$noEst)
                ->update([
                    'status_fu' => $hasilFU
                ]);
            return 'success';
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function trn(Request $request) {
        $noEstimation = $request->no_estimation;

        try {
            $items = DB::table('bp_estimation_trns')
                ->where('no_estimation','=',$noEstimation)
                ->get();
            $result['data'] = $items;

            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

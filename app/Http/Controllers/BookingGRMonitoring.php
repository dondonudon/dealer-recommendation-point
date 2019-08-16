<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingGRMonitoring extends Controller
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
                return view('dashboard.bookingGR-monitoring');
                break;
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $statusFU = $request->status_fu;
        try {
            if ($statusFU == 'all') {
                $menu = DB::table('booking_gr_mst')
                    ->select('no_booking', 'nama', 'no_telp', 'no_pol', 'model_kendaraan', 'tahun_kendaraan', 'tgl_booking', 'jam_booking', 'tipe_service', 'status_fu', 'username', 'created_at')
                    ->whereBetween('tgl_booking', [$startDate, $endDate])
                    ->get();
            } else {
                $menu = DB::table('booking_gr_mst')
                    ->select('no_booking', 'nama', 'no_telp', 'no_pol', 'model_kendaraan', 'tahun_kendaraan', 'tgl_booking', 'jam_booking', 'tipe_service', 'status_fu', 'username', 'created_at')
                    ->where('status_fu', '=', $statusFU)
                    ->whereBetween('tgl_booking', [$startDate, $endDate])
                    ->get();
            }
            $result['data'] = $menu;
            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function notes(Request $request) {
        $noBooking = $request->no_booking;

        try {
            $notes = DB::table('booking_gr_trn')
                ->where('no_booking','=',$noBooking)
                ->get();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        $result['data'] = $notes;

        return json_encode($result);
    }

    public function updateFU(Request $request) {
        $hasilFU = $request->hasil_fu;
        $noBooking = $request->no_booking;

        try {
            DB::table('booking_gr_mst')
                ->where('no_booking','=',$noBooking)
                ->update([
                    'status_fu' => $hasilFU
                ]);
            return 'success';
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

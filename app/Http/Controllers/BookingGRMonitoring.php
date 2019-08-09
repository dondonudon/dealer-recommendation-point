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
        $date = $request->date_filter;
        try {
            $menu = DB::table('booking_gr_mst')
                ->select('no_booking','nama','no_telp','no_pol','model_kendaraan','tahun_kendaraan','tgl_booking','jam_booking','tipe_service')
                ->whereDate('tgl_booking','=',$date)
                ->get();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        $result['data'] = $menu;

        return json_encode($result);
    }

    public function keluhan(Request $request) {
        $noBooking = $request->no_booking;

        try {
            $keluhan = DB::table('booking_gr_trn')
                ->select('keluhan')
                ->where('no_booking','=',$noBooking)
                ->get();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        $result['data'] = $keluhan;

        return json_encode($result);
    }
}

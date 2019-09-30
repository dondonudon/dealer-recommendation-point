<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingGRMonitorBooking extends Controller
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
                return view('dashboard.bookingGR-monitoringBooking');
                break;
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $statusHadir = $request->status_fu;
        try {
            if ($statusHadir == 'all') {
                $menu = DB::table('booking_gr_mst')
                    ->whereBetween('tgl_booking', [$startDate, $endDate])
                    ->get();
            } else {
                $menu = DB::table('booking_gr_mst')
                    ->where('isDatang', '=', $statusHadir)
                    ->whereBetween('tgl_booking', [$startDate, $endDate])
                    ->get();
            }
            $result['data'] = $menu;
            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}

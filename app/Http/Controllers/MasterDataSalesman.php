<?php

namespace App\Http\Controllers;

use App\ms_sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterDataSalesman extends Controller
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
                return view('dashboard.masterdata-salesman');
                break;
        }
    }

    public function list() {
        try {
            $sales = DB::table('ms_salesman')
                ->select('ms_salesman.username as username', 'sys_user_profile.nama_lengkap', 'sys_user_profile.email', 'sys_user_profile.no_telp')
                ->leftJoin('sys_user_profile', 'sys_user_profile.username', '=', 'ms_salesman.username')
                ->get();
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }
        $result['data'] = $sales;

        return json_encode($result);
    }

    public function user() {
        try {
            $sales = DB::table('ms_salesman');
            $dtSales = [];
            if ($sales->exists()) {
                $dbSales = $sales->get();
                foreach ($dbSales as $s) {
                    $dtSales[] = $s->username;
                }
            }

            $user = DB::table('sys_user')
                ->select('sys_user.username as username', 'sys_user_profile.nama_lengkap', 'sys_user_profile.email', 'sys_user_profile.no_telp')
                ->leftJoin('sys_user_profile', 'sys_user_profile.username', '=', 'sys_user.username')
                ->where('sys_user.isDel','!=','1')
                ->whereNotIn('sys_user.username',$dtSales)
                ->get();
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }
        $result['data'] = $user;

        return json_encode($result);
    }

    public function add(Request $request) {
        $data = json_decode($request->data);

        try {
            foreach ($data as $d) {
                $sales = new ms_sales();
                $sales->username = $d->username;
                $sales->save();
            }
        } catch (\Exception $ex) {
            dd('Exception Block', $ex);
        }

        return 'success';
    }

    public function delete(Request $request) {
        $username = $request->username;

        try {
            DB::table('ms_salesman')
                ->where('username','=',$username)
                ->delete();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return 'success';
    }
}

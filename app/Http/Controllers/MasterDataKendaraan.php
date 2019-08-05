<?php

namespace App\Http\Controllers;

use App\msKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MasterDataKendaraan extends Controller
{
    private function permission($segment) {
        if (Session::exists('username')) {
            $permission = DB::table('sys_permission')
                ->select('username','id_menu')
                ->join('sys_menu','sys_menu.id','=','sys_permission.id_menu')
                ->where([
                    ['sys_menu.segment_name','=',$segment],
                    ['sys_permission.username','=',Session::get('username')],
                ]);
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
                return redirect('/')->with('error','permission denied');
                break;

            default:
                return view('dashboard.masterdata-kendaraan');
                break;
        }
    }

    public function list() {
        $result = [];
        try {
            $menu = DB::table('ms_kendaraan')
                ->select('id','model','category','tahun')
                ->where('isDel','=',0)
                ->get();
            $result['data'] = $menu;
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return json_encode($result);
    }

    public function add(Request $request) {
        $kategori = $request->kategori;
        $modeName = $request->model_name;
        $tahun = $request->tahun;

        try {
            msKendaraan::create([
                'category' => $kategori,
                'model' => $modeName,
                'tahun' => $tahun,
            ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return 'success';
    }

    public function edit(Request $request) {
        $id = $request->id;
        $kategori = $request->kategori;
        $modeName = $request->model_name;
        $tahun = $request->tahun;

        try {
            DB::table('ms_kendaraan')
                ->where('id','=',$id)
                ->update([
                    'category' => $kategori,
                    'model' => $modeName,
                    'tahun' => $tahun,
                ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }

        return 'success';
    }

    public function delete(Request $request) {
        $id = $request->id;

        try {
            DB::table('ms_kendaraan')
                ->where('id','=',$id)
                ->update([
                    'isDel' => 1
                ]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }
}

<?php

namespace App\Http\Controllers;

use App\msKonten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class MasterDataKontenGambar extends Controller
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
                return view('dashboard.masterdata-konten_gambar');
                break;
        }
    }

    public function list() {
        try {
            $data['data'] = msKonten::all();
            return json_encode($data);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function upload(Request $request, $info) {
        $file = $request->file('filepond');
        $extension = $request->file('filepond')->getClientOriginalExtension();

        try {
            $id = Uuid::uuid1();
            if ($info == 0) {
                $fileName = $id->toString().'.'.$extension;
                Storage::disk('public')->delete($fileName);
                DB::table('ms_konten')->where('info','=',0)->delete();
            } else {
                $fileName = $id->toString().'.'.$extension;
            }

            Storage::putFileAs('public', $file, $fileName);

            $konten = new msKonten();
            $konten->file_name = $fileName;
            $konten->file_location_laravel = Storage::url($fileName);
            $konten->file_location = url('/').'/laravel-system/storage/app/public/'.$fileName;
            if ($request->keterangan !== null) {
                $konten->keterangan = $request->keterangan;
            } else {
                $konten->keterangan = '';
            }
            $konten->info = $info;
            $konten->save();
        } catch (\Exception $ex) {
            return response()->json($ex);
        }

        return 'success';
    }

    public function preview(Request $request) {
        $msKonten = msKonten::all();
        $result = [];

        foreach ($msKonten as $img) {
            if ($request->getHttpHost() !== 'nasmocobrebesbp.com') {
                $url = $img->file_location_laravel;
            } else {
                $url = $img->file_location;
            }
            $result[] = [
                'src' => $url,
                'w' => 1000,
                'h' => 600,
                'title' => $img->file_name,
            ];
        }
        return json_encode($result);
    }

    public function delete(Request $request) {
        try {
            Storage::disk('public')->delete($request->filename);
            DB::table('ms_konten')->where('file_name','=',$request->filename)->delete();
        } catch (\Exception $ex) {
            return response()->json($ex);
        }

        return 'success';
    }
}

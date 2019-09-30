<?php

namespace App\Http\Controllers;

use App\bookingGrMst;
use App\Http\Controllers\publicFunc\GenerateNumber;
use App\salesProspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class BookingGRInput extends Controller
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
                return view('dashboard.bookingGR-input');
                break;
        }
    }

    public function download() {
        return response()
            ->download(
                storage_path('app/public/input_booking_gr.xlsx')
            );
    }

    public function upload(Request $request) {
        $username = Session::get('username');
        $file = $request->file('filepond');
        $extension = $request->file('filepond')->getClientOriginalExtension();

        $notAvailable = [];
        $key = [
            'nama' => 0,
            'no_telp' => 0,
            'no_pol' => 0,
            'model_kendaraan' => 0,
            'tahun_kendaraan' => 0,
            'keluhan' => 0,
            'tgl_booking' => 0,
            'jam_booking' => 0,
            'tipe_service' => 0,
            'user_fu' => 0,
        ];

        $reader='';
        switch ($extension) {
            case 'xls':
                $reader = new Xls();
                break;

            case 'xlsx':
                $reader = new Xlsx();
                break;
        }

        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $fValue = $worksheet->toArray();

        foreach ($fValue[0] as $i => $v) {
            switch ($v) {
                case 'NAMA':
                    $key['nama'] = $i;
                    break;

                case 'NO TELP':
                    $key['no_telp'] = $i;
                    break;

                case 'NO POL':
                    $key['no_pol'] = $i;
                    break;

                case 'MODEL KENDARAAN':
                    $key['model_kendaraan'] = $i;
                    break;

                case 'TAHUN KENDARAAN':
                    $key['tahun_kendaraan'] = $i;
                    break;

                case 'KELUHAN':
                    $key['keluhan'] = $i;
                    break;

                case 'TANGGAL BOOKING':
                    $key['tgl_booking'] = $i;
                    break;

                case 'JAM BOOKING':
                    $key['jam_booking'] = $i;
                    break;

                case 'TIPE SERVICE':
                    $key['tipe_service'] = $i;
                    break;

                case 'USER FU':
                    $key['user_fu'] = $i;
                    break;
            }
        }

        try {
            for ($i = 1; $i < count($fValue); $i++) {
                $noTelp = $fValue[$i][ $key['no_telp'] ];
                if ($noTelp[0] == '0') {
                    $noTelp = '+62'.substr($noTelp,1);
                } elseif ($noTelp[0] == '8') {
                    $noTelp = '+62'.$noTelp;
                }
                $sales = DB::table('ms_salesman')->where('username','=',$fValue[$i][ $key['user_fu'] ]);
                if ($sales->exists()) {
                    $gr = new bookingGrMst();
                    $gr->no_booking = GenerateNumber::generate('GE','booking_gr_mst','no_booking');
                    $gr->nama = $fValue[$i][ $key['nama'] ];
                    $gr->no_telp = $noTelp;
                    $gr->no_pol = $fValue[$i][ $key['no_pol'] ];
                    $gr->model_kendaraan = $fValue[$i][ $key['model_kendaraan'] ];
                    $gr->tahun_kendaraan = $fValue[$i][ $key['tahun_kendaraan'] ];
                    $gr->keluhan = $fValue[$i][ $key['keluhan'] ];
                    $gr->tgl_booking = date('Y-m-d',strtotime($fValue[$i][ $key['tgl_booking'] ]));
                    $gr->jam_booking = date('H:i:s',strtotime($fValue[$i][ $key['jam_booking'] ]));
                    $gr->tipe_service = $fValue[$i][ $key['tipe_service'] ];
                    $gr->user_fu = $fValue[$i][ $key['user_fu'] ];
                    $gr->username = $username;
                    $gr->save();
                } else {
                    $notAvailable[] = $fValue[$i][ $key['user_fu'] ];
                }
            }
        } catch (\Exception $ex) {
            return dd('Exception Block',$ex);
        }
        if (count($notAvailable) > 0) {
            $result = 'Salesman berikut tidak terdaftar <br><b>'.implode(', ',$notAvailable).'</b>';
        } else {
            $result = 'success';
        }
        return $result;
    }
}

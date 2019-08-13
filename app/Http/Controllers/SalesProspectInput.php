<?php

namespace App\Http\Controllers;

use App\Http\Controllers\publicFunc\GenerateNumber;
use App\salesProspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class SalesProspectInput extends Controller
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
                return view('dashboard.sales_prospect-input_prospect');
                break;
        }
    }

    public function upload(Request $request) {
        $username = Session::get('username');

        $file = $request->file('filepond');
        $extension = $request->file('filepond')->getClientOriginalExtension();

        $fKey = [
            'Nama Customer' => 0,
            'No. Telp' => 0,
            'Model Kendaraan' => 0,
            'Kabupaten' => 0,
            'Kecamatan' => 0,
            'Alamat' => 0,
            'Pekerjaan' => 0,
            'Kebutuhan Credit/Cash' => 0,
        ];

        switch ($extension) {
            case 'xls':
                $reader = new Xls();
                break;

            case 'xlsx':
                $reader = new Xlsx();
                break;

            case 'csv':
                $reader = new Csv();
                break;
        }

        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $fValue = $worksheet->toArray();

        foreach ($fValue[0] as $i => $v) {
            switch ($v) {
                case 'Nama Customer':
                    $fKey['Nama Customer'] = $i;
                    break;

                case 'No. Telp':
                    $fKey['No. Telp'] = $i;
                    break;

                case 'Model Kendaraan':
                    $fKey['Model Kendaraan'] = $i;
                    break;

                case 'Kabupaten':
                    $fKey['Kabupaten'] = $i;
                    break;

                case 'Kecamatan':
                    $fKey['Kecamatan'] = $i;
                    break;

                case 'Alamat':
                    $fKey['Alamat'] = $i;
                    break;

                case 'Pekerjaan':
                    $fKey['Pekerjaan'] = $i;
                    break;

                case 'Kebutuhan Credit/Cash':
                    $fKey['Kebutuhan Credit/Cash'] = $i;
                    break;
            }
        }

        try {
            for ($i = 1; $i < count($fValue); $i++) {
                $prospect = new salesProspect();
                $prospect->no_sales = GenerateNumber::generate('SA','sales_prospect','no_sales');
                $prospect->nama_customer = $fValue[$i][ $fKey['Nama Customer'] ];
                $prospect->no_telephone = $fValue[$i][ $fKey['No. Telp'] ];
                $prospect->model_kendaraan = $fValue[$i][ $fKey['Model Kendaraan'] ];
                $prospect->kabupaten = $fValue[$i][ $fKey['Kabupaten'] ];
                $prospect->kecamatan = $fValue[$i][ $fKey['Kecamatan'] ];
                $prospect->alamat = $fValue[$i][ $fKey['Alamat'] ];
                $prospect->pekerjaan = $fValue[$i][ $fKey['Pekerjaan'] ];
                $prospect->kebutuhan = $fValue[$i][ $fKey['Kebutuhan Credit/Cash'] ];
                $prospect->username = $username;
                $prospect->save();
            }
        } catch (\Exception $ex) {
            dd($ex);
        }

        return 'success';
    }
}

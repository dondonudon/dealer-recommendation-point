<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesProspectMonitoring extends Controller
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
                return view('dashboard.sales_prospect-monitoring');
                break;
        }
    }

    public function list(Request $request) {
        $startDate = $request->start_date;
        $endDate = $request->end_date.' 23:50:50';
        $statusFU = $request->status_fu;
        $salesman = $request->salesman;
        try {
            if ($statusFU == 'all' && $salesman == 'all') {
                $menu = DB::table('sales_prospect')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            } elseif ($statusFU == 'all' && $salesman !== 'all') {
                $menu = DB::table('sales_prospect')
                    ->where('salesman', '=', $salesman)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            } elseif ($statusFU !== 'all' && $salesman == 'all') {
                $menu = DB::table('sales_prospect')
                    ->where('status_fu', '=', $statusFU)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            } else {
                $menu = DB::table('sales_prospect')
                    ->where('status_fu', '=', $statusFU)
                    ->where('salesman', '=', $salesman)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
            }
            $result['data'] = $menu;
            return json_encode($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function export($startDate,$endDate,$statusFU,$salesman) {
        try {
            if ($statusFU == 'all' && $salesman == 'all') {
                $menu = DB::table('sales_prospect')
                    ->whereBetween('created_at', [$startDate, $endDate.' 23:50:50'])
                    ->get();
            } elseif ($statusFU == 'all' && $salesman !== 'all') {
                $menu = DB::table('sales_prospect')
                    ->where('salesman', '=', $salesman)
                    ->whereBetween('created_at', [$startDate, $endDate.' 23:50:50'])
                    ->get();
            } elseif ($statusFU !== 'all' && $salesman == 'all') {
                $menu = DB::table('sales_prospect')
                    ->where('status_fu', '=', $statusFU)
                    ->whereBetween('created_at', [$startDate, $endDate.' 23:50:50'])
                    ->get();
            } else {
                $menu = DB::table('sales_prospect')
                    ->where('status_fu', '=', $statusFU)
                    ->where('salesman', '=', $salesman)
                    ->whereBetween('created_at', [$startDate, $endDate.' 23:50:50'])
                    ->get();
            }
            $result['data'] = $menu;

            $xls = new Spreadsheet();
//            $xls->getActiveSheet()->setCellValue('A1','NoBooking');
            $xls->getActiveSheet()->fromArray(['ID','Nama Customer','No Telp','Model Kendaraan','Kabupaten','Kecamatan','Alamat','Pekerjaan','Kebutuhan','Jenis Pembelian','Salesman','Status FU','Waktu Telp','User Input','Tanggal Input']);

            $i=1;
            foreach ($result['data'] as $d) {
                $jenisBeli = '';
                switch ($d->jenisPembelian) {
                    case '1':
                        $jenisBeli = 'First Buyer';
                        break;

                    case '2':
                        $jenisBeli = 'Replacement';
                        break;

                    case '3':
                        $jenisBeli = 'Repeat Buyer';
                        break;

                    default:
                        $jenisBeli = 'Salesman belum follow up';
                        break;
                }
                $i++;
                $xls->getActiveSheet()
                    ->fromArray([
                        $d->no_sales,
                        $d->nama_customer,
                        $d->no_telephone,
                        $d->model_kendaraan,
                        $d->kabupaten,
                        $d->kecamatan,
                        $d->alamat,
                        $d->pekerjaan,
                        $d->kebutuhan,
                        $jenisBeli,
                        $d->salesman,
                        ($d->status_fu == 0)?'Belum Follow Up':'Sudah Follow Up',
                        gmdate('H:i:s',$d->waktu_telp),
                        $d->username,
                        date('d-m-Y H:i:s',strtotime($d->created_at)),
                    ],'','A'.$i);
//                $xls->getActiveSheet()->setCellValue('A'.$i,$d->no_booking);
//                $xls->getActiveSheet()->setCellValue('B'.$i,$d->nama);
//                $xls->getActiveSheet()->setCellValue('C'.$i,$d->no_telp);
//                $xls->getActiveSheet()->setCellValue('D'.$i,$d->no_pol);
//                $xls->getActiveSheet()->setCellValue('E'.$i,$d->model_kendaraan);
//                $xls->getActiveSheet()->setCellValue('F'.$i,$d->tahun_kendaraan);
//                $xls->getActiveSheet()->setCellValue('G'.$i,date('d-m-Y',strtotime($d->tgl_booking)));
//                $xls->getActiveSheet()->setCellValue('H'.$i,$d->jam_booking);
//                $xls->getActiveSheet()->setCellValue('I'.$i,$d->tipe_service);
//                $xls->getActiveSheet()->setCellValue('J'.$i,$d->keluhan);
//                $xls->getActiveSheet()->setCellValue('K'.$i,$d->username);
//                $xls->getActiveSheet()->setCellValue('L'.$i,$d->user_fu);
//                $xls->getActiveSheet()->setCellValue('M'.$i,($d->isDatang==0)?'Tidak Datang':'datang');
//                $xls->getActiveSheet()->setCellValue('N'.$i,date('d-m-Y H:i:s',strtotime($d->created_at)));
            }
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
        $writer = new Xlsx($xls);

        $response =  new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="ExportSalesProspect.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}

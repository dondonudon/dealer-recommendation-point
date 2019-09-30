<?php

namespace App\Http\Controllers;

use App\bookingGrTrn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function Composer\Autoload\includeFile;

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
                    ->whereBetween('tgl_booking', [$startDate, $endDate])
                    ->get();
            } else {
                $menu = DB::table('booking_gr_mst')
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
        $user = Session::get('username');
        $hasilFU = $request->hasil_fu;
        $noBooking = $request->no_booking;
        ($request->catatan == null)?$catatan = '':$catatan = $request->catatan;
        if ($hasilFU == 2) {
            $catatan .= ' - (Reschedule ke '.date('d-m-Y',strtotime($request->tgl_reschedule)).')';
        }
        try {
            $update['status_fu'] = $hasilFU;
            if ($hasilFU == 2) {
                $update['tgl_booking'] = $request->tgl_reschedule;
                $update['jam_booking'] = $request->jam_reschedule;
            }
            DB::table('booking_gr_mst')
                ->where('no_booking','=',$noBooking)
                ->update($update);

            $history = new bookingGrTrn();
            $history->no_booking = $noBooking;
            $history->user_fu = $user;
            $history->status_fu = $hasilFU;
            $history->note = $catatan;
            $history->save();
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }

    public function updateDatang(Request $request) {
        try {
            DB::table('booking_gr_mst')
                ->where('no_booking','=',$request->no_booking)
                ->update(['isDatang' => $request->status]);
        } catch (\Exception $ex) {
            dd('Exception Block',$ex);
        }
        return 'success';
    }

    public function export($start,$end,$status) {
        try {
            if ($status == 'all') {
                $menu = DB::table('booking_gr_mst')
                    ->whereBetween('tgl_booking', [$start, $end])
                    ->get();
            } else {
                $menu = DB::table('booking_gr_mst')
                    ->where('status_fu', '=', $status)
                    ->whereBetween('tgl_booking', [$start, $end])
                    ->get();
            }
            $result['data'] = $menu;
            $xls = new Spreadsheet();
//            $xls->getActiveSheet()->setCellValue('A1','NoBooking');
            $xls->getActiveSheet()->fromArray(['No Booking','Nama','No Telp','No Pol','Model Kendaraan','Tahun Kendaraan','Tgl Booking','Jam Booking','Tipe Service','Target User FU','Status FU','User Input','Status Kehadiran','Tgl Input']);

            $i=1;
            foreach ($result['data'] as $d) {
                $i++;
                $xls->getActiveSheet()->setCellValue('A'.$i,$d->no_booking);
                $xls->getActiveSheet()->setCellValue('B'.$i,$d->nama);
                $xls->getActiveSheet()->setCellValue('C'.$i,$d->no_telp);
                $xls->getActiveSheet()->setCellValue('D'.$i,$d->no_pol);
                $xls->getActiveSheet()->setCellValue('E'.$i,$d->model_kendaraan);
                $xls->getActiveSheet()->setCellValue('F'.$i,$d->tahun_kendaraan);
                $xls->getActiveSheet()->setCellValue('G'.$i,date('d-m-Y',strtotime($d->tgl_booking)));
                $xls->getActiveSheet()->setCellValue('H'.$i,$d->jam_booking);
                $xls->getActiveSheet()->setCellValue('I'.$i,$d->tipe_service);
                $xls->getActiveSheet()->setCellValue('J'.$i,$d->keluhan);
                $xls->getActiveSheet()->setCellValue('K'.$i,$d->username);
                $xls->getActiveSheet()->setCellValue('L'.$i,$d->user_fu);
                $xls->getActiveSheet()->setCellValue('M'.$i,($d->isDatang==0)?'Tidak Datang':'datang');
                $xls->getActiveSheet()->setCellValue('N'.$i,date('d-m-Y H:i:s',strtotime($d->created_at)));
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
        $response->headers->set('Content-Disposition', 'attachment;filename="ExportBooking.xlsx"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}

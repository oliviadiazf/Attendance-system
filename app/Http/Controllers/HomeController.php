<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Absen;
use DateTime;
use Illuminate\Support\Facades\Auth as Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function timeZone($location) {
        // set TimeZone Indonesia - Jakarta
        return date_default_timezone_set($location);
    }

    public function index()
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d");

        $cek_absen = Absen::where(['user_id' => $user_id, 'date' => $date])
                            ->get()
                            ->first();
        if (is_null($cek_absen)) {
            $info = array(
                "status" => "Anda belum mengisi absen masuk",
                "btnIn" => "",
                "btnOut" => "disabled"
            );
        } elseif ($cek_absen->time_out == NULL) {
            $info = array(
                "status" => "Jangan lupa absen keluar",
                "btnIn" => "disabled",
                "btnOut" => ""
            );
        } else {
            $info = array(
                "status" => "Absensi hari ini telah selesai",
                "btnIn" => "disabled",
                "btnOut" => "disabled"
            );
        }

        $data_absen = Absen::where('user_id', $user_id)
                    ->orderBy('date','desc')
                    ->paginate(50);
        return view('home', compact('data_absen', 'info'));
    }

    public function absen(Request $request){
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d");  //2017-02-01
        $time_in = date("H:i:s"); //12:31:20
        $time_out = date("H:i:s"); //12:31:20

        $note_tugas = $request->note_tugas;
        $note_kendala = $request->note_kendala;

        $absen = new Absen;

        // absensi masuk
        if (isset($request["btnIn"])) {
            // cek double data
            $cek_double = $absen->where(['date' => $date, 'user_id' => $user_id])
                                ->count();
            if ($cek_double > 0) {
                return redirect()->back();
            }
            $absen->create([
                'user_id'=> $user_id,
                'date' => $date,
                'time_in' => $time_in,
                'note_tugas' => $note_tugas,
                'note_kendala' => $note_kendala
            ]);
            return redirect()->back();
        }
        // absensi keluar
        elseif (isset($request["btnOut"])) {
            $absen->where(['date' => $date, 'user_id' => $user_id])
                ->update([
                    'time_out' => $time_out,
                    'note_tugas' => $note_tugas,
                    'note_kendala' => $note_kendala
            ]);
            return redirect()->back();
        }
        $hasil = date("H:i:s");
        $absen->create(['time_total' => $hasil]);
        return $request->all();
    }

    public function hitung() {
        $time_in = Absen::where('time_in')->get()->first();
        $time_out = Absen::where('time_out')->get()->first();
        $result = Absen::where(['time_in' => $time_in, 'time_out' => $time_out])
                            ->get()
                            ->first()
                            ->date_diff($time_out,$time_in);
        $hasil = Absen::create(['time_total' => $result]);
        return $hasil;
    }
}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $info['status']}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-responsive">
                        <form action="/absen" method="POST">
                            {{ csrf_field() }}
                            <tr>
                                <td>
                                    <input type="text" class="form-control" placeholder="Tugas ..." name="note_tugas">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" class="form-control" placeholder="Kendala ..." name="note_kendala">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="submit" class="btn btn-flat btn-primary" name="btnIn" {{$info['btnIn']}}>
                                        JAM MASUK
                                    </button>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-flat btn-primary" name="btnOut" {{$info['btnOut']}}>
                                        JAM PULANG
                                    </button>
                                </td>
                            </tr>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Riwayat Absensi</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-responsive table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Total Jam Kerja</th>
                                <th>Tugas</th>
                                <th>Kendala</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data_absen as $absen)
                            <tr>
                                <th>{{$absen->date}}</th>
                                <th>{{$absen->time_in}}</th>
                                <th>{{$absen->time_out}}</th>
                                <th>{{$absen->time_total}}</th>
                                <th>{{$absen->note_tugas}}</th>
                                <th>{{$absen->note_kendala}}</th>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4"><b><i>TIDAK ADA DATA UNTUK DITAMPILKAN</i></b></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {!! $data_absen->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

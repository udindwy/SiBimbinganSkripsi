@extends('layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Monitoring Mahasiswa</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Monitoring Mahasiswa</li>
    </ol>
</div>

<div class="card">
    <div class="card-body">
        <div class="table table-responsive">
            <table class="table table-bordered text-nowrap" id="table">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Dosen Pembimbing 1</th>
                        <th>Dosen Pembimbing 2</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item[0]->mahasiswa->mahasiswa->npm }}</td>
                        <td>{{ $item[0]->mahasiswa->nama }}</td>
                        <td>{{ $item[0]->pembimbing_utama->dosen->nama }}</td>
                        <td>{{ $item[0]->pembimbing_pendamping->dosen->nama }}</td>
                        <td>{{ $item[0]->mahasiswa->mahasiswa->status_bimbingan }}</td>
                        <td>
                            <a href="{{ route('bimbingan.show-monitoring-bimbingan', $item[0]->mahasiswa->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            @if ($item[0]->mahasiswa->mahasiswa->status_bimbingan == 'Masih Bimbingan')
                            <a href="{{ route('bimbingan.selesaikan-bimbingan', $item[0]->mahasiswa->id) }}" class="btn btn-sm btn-warning btn-selesai">Selesai Bimbingan</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="7">-- Data Masih Kosong --</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
    <script>
        $(document).ready( function () {
            $('#table').DataTable({
                ordering: false
            });
        });
    </script>

    <script src="{{ url('js/sweetalert2.all.min.js') }}"></script>

    <script>
        $('.btn-selesai').on('click', function (event) {
            event.preventDefault(); // prevent form submit
            var form = $(this).attr('href');
            Swal.fire({
                title: 'Yakin Menyelesaikan Bimbingan?',
                text: "Pastikan Telah Melakukan Bimbingan Sebanyak 10x",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = form;
                }else {
                    //
                }
            });
        });
    </script>
@endpush

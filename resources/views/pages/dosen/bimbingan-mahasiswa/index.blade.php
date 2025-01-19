@extends('layouts.admin')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Bimbingan Mahasiswa</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Bimbingan Mahasiswa</li>
    </ol>
</div>

<div class="card">
    <div class="card-body">
        @if ($items->count() >= 1)
            <div class="table table-responsive">
                <table class="table table-bordered text-nowrap" id="table">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>NPM</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->mahasiswa->mahasiswa->npm }}</td>
                            <td>{{ $item->mahasiswa->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                <a href="{{ route('bimbingan.detail_bimbingan', $item->id) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                        @empty

                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <h4>Belum terdapat bimbingan dari mahasiswa.</h4>
        @endif
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

    @if ($message = Session::get('success-balas-bimbingan'))
        <script>
            Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ $message }}'
        })
        </script>
    @endif
@endpush

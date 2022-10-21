@extends('layouts.main')
@section('container')
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ $title }} Perpustakaan SD Negeri 017 Senayang</h6>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="table-responsive">
                <a href="/data-peminjaman/create" class="btn btn-primary mb-3">
                    <span class="text">Tambah Data Peminjaman</span>
                </a>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <th>No.</th>
                            <th>No. Peminjaman</th>
                            <th>No. Anggota</th>
                            <th>Nama</th>
                            <th>Judul Buku</th>
                            <th>ISBN</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Ubah</th>
                            <th>Hapus</th>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrows as $borrow)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $borrow->member->member_id }}</td>
                                <td>{{ $borrow->member->name }}</td>
                                <td>{{ $borrow->rack->name }}</td>
                                <td>{{ $borrow->book->title }}</td>
                                <td>{{ $borrow->book->isbn }}</td>
                                <td>{{ $borrow->tgl_pinjam }}</td>
                                <td>{{ $borrow->tgl_kembali }}</td>
                                <td>
                                    <a href="/data-buku/{{ $borrow->id }}/edit" class="btn btn-primary mb-2"
                                        class="btn btn-secondary"><i class="fas fa-user-edit"></i></a>
                                </td>
                                <td>
                                    <form action="/data-buku/{{ $borrow->id }}" method="POST">
                                        @method('delete')
                                        @csrf
                                        <button class="btn btn-danger mb-2" class="btn btn-secondary"
                                            onclick="return confirm('Data akan hilang ketika dihapus, apakah anda yakin?')"><i
                                                class="fas fa-user-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dasar')

@section('title', 'MySDM-Dashboard')

@section('content')
<div class="container">
    <h1 class="my-4">Dashboard</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Statistik Pengguna</h4>
                </div>
                <div class="card-body">
                    <p>Menampilkan data statistik pengguna atau informasi lainnya di sini.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Grafik Tren</h4>
                </div>
                <div class="card-body">
                    <p>Menampilkan grafik tren atau data visual lainnya di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
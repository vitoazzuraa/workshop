@extends('layouts.master')

@section('title', 'Dashboard')

@section('style-page')
    @endsection

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-home"></i>
    </span> Dashboard
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Selamat Datang!</h4>
                <p class="card-description"> Kamu berhasil login ke sistem <strong>Koleksi Buku</strong>. </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
    @endsection

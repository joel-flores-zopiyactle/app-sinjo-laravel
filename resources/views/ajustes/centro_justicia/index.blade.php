@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="con d-flex justify-content-between align-items-center">
        <h4>Lista de centros de justicia</h4>
        <div>
            <a class="btn btn-primary btn-sm" href="{{ route('create-centro') }}">Agregar nuevo centro</a>
        </div>
    </div>

    <hr>
    
</div>
@endsection

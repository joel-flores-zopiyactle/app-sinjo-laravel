@extends('layouts.dashboard')

@section('content')
<div class="container">

   <div class="bg-white shadow mb-3 p-3">

      <nav>
         <div class="nav nav-tabs" id="nav-tab" role="tablist">
           <button class="nav-link active" id="nav-audiencia-tab" data-bs-toggle="tab" data-bs-target="#nav-audiencia" type="button" role="tab" aria-controls="nav-audiencia" aria-selected="true">Audiencia</button>
           <button class="nav-link" id="nav-asistencia-tab" data-bs-toggle="tab" data-bs-target="#nav-asistencia" type="button" role="tab" aria-controls="nav-asistencia" aria-selected="false">Toma de lista</button>
         </div>
       </nav>

       <div class="tab-content mt-2" id="nav-tabContent">
          {{-- audiencia --}}
         <div class="tab-pane fade show active" id="nav-audiencia" role="tabpanel" aria-labelledby="nav-audiencia-tab">
            <div class="mb-2">
               <section>
                 <h3> <b>Audiencia</b>: {{$expediente->audiencia->tipoAudiencia->nombre }}</h3>
               </section>
            </div>
      
            <div class="row">
               <div class="col-5">
                  <div class="w-100 overflow-hidden">
                     <img src="{{ asset('img/sinjo_logo.png') }}" class="img-fluid rounded border" alt="logo">
                  </div>
         
                  <div>
                     <div class="d-flex justify-content-center mt-2">
                        <button class="btn btn-primary me-2 d-flex justify-content-center align-items-center"><span class="iconify h4 m-0" data-icon="akar-icons:play"></span></button>
                        <button class="btn btn-warning d-flex justify-content-center align-items-center me-2"><span class="iconify h4 m-0" data-icon="clarity:pause-solid"></span></button>
                        <button class="btn btn-danger d-flex justify-content-center align-items-center"><span class="iconify h4 m-0" data-icon="bi:stop-circle-fill"></span></button>
                     </div>
                  </div>
               </div>
         
               <div class="col-7">
                 {{-- Info de la audiencia --}}
               </div>
            </div>
      
            {{-- Registros de datos --}}
      
            <hr class="my-2">
      
            <div class="row">
               <ul class="nav nav-tabs" id="myTab" role="tablist">
                  {{-- notes --}}
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="note-tab" data-bs-toggle="tab" data-bs-target="#note" type="button" role="tab" aria-controls="note" aria-selected="true">Notas</button>
                  </li>
                  {{-- file --}}
                  <li class="nav-item" role="presentation">
                    <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">Archivos</button>
                  </li>
                  
                </ul>
      
                <div class="tab-content" id="myTabContent">
                   {{-- Notas --}}
                  <div class="tab-pane fade show active" id="note" role="tabpanel" aria-labelledby="note-tab">
                        <x-sala.form-nota :participantes="$expediente->audiencia->participantes" :audienciaid="$expediente->audiencia->id"/>
                  </div>
                  {{-- Archivos --}}
                  <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="file-tab">
                        <x-sala.form-file :participantes="$expediente->audiencia->participantes" :audienciaid="$expediente->audiencia->id"/>
                  </div>
   
                </div>
            </div>
         </div>

         <!-- asistencia -->
         <div class="tab-pane fade" id="nav-asistencia" role="tabpanel" aria-labelledby="nav-asistencia-tab">
           <x-sala.table-asistencia :participantes="$expediente->audiencia->participantes" :audienciaid="$expediente->audiencia->id" />
         </div>
        
       </div>    

   </div>
   
   


</div>
@endsection

@section('js')
    <script src="{{ asset('js/sala/form-notas.js') }}"></script>
    <script src="{{ asset('js/sala/form-files.js') }}"></script>
@endsection

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Controllers\CommandsConfigController;
use App\Http\Controllers\DiskController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Modules\CentroJusticiaController;
use App\Http\Controllers\Modules\RolController;
use App\Http\Controllers\Modules\SalaController;
use App\Http\Controllers\Modules\TipoAudienciaController;
use App\Http\Controllers\Modules\JuiciosController;
use App\Http\Controllers\ConfigConexionObsController;

use App\Http\Controllers\User\UsuariosController;
use App\Http\Controllers\User\TipoUsuariosController;

use App\Http\Controllers\Audiencia\ReservaSalaController;
use App\Http\Controllers\Audiencia\ParticipanteController;
use App\Http\Controllers\Audiencia\BuscarExpedienteController;
use App\Http\Controllers\Audiencia\ExpedientePDFController;

use App\Http\Controllers\AgendaController;

use App\Http\Controllers\Auditoria\AuditoriasController;
use App\Http\Controllers\Auditoria\NotaController;
use App\Http\Controllers\Auditoria\VideoAudienciaController;
use App\Http\Controllers\Auditoria\ArchivoController;
use App\Http\Controllers\Auditoria\InvitadoController;
use App\Http\Controllers\Auditoria\ReporteController;

use App\Http\Controllers\Analisis\AnalisisController;







try {

    Route::get('/', [CommandsConfigController::class, 'welcome']); // inicio
      
    $users = User::all(); // Si no existe table users vamos a las configuraciones

    if($users->count() > 0) {   // Si existe un usuario desactivamos la opcion de register del Auth

        Auth::routes(["register" => false]);
        //Artisan::call('storage:link'); // Se publica el storage a la carpeta public

    } else {

        Auth::routes();
        Artisan::call('storage:link'); // Se publica el storage a la carpeta public
       
    }
} catch (\Illuminate\Database\QueryException $th) {

    Route::get('/', [CommandsConfigController::class, 'configDB']); // configuraciones
    Route::get('/migrate', [CommandsConfigController::class, 'migrateDB'])->name('migrate'); // migraciones
    
}


Route::middleware(['auth'])->group( function() {
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Centro de justicia
    Route::get('/ajustes/centro-de-justicia', [CentroJusticiaController::class, 'index'])->name('centro.justicia');
    Route::get('/ajustes/centro-de-justicia/nuevo', [CentroJusticiaController::class, 'create'])->name('create.centro');
    Route::post('/ajustes/centro-de-justicia/nuevo', [CentroJusticiaController::class, 'store'])->name('post.centro');
    Route::get('/ajustes/centro-de-justicia/editar/{id}', [CentroJusticiaController::class, 'edit'])->name('edit.centro');
    Route::put('/ajustes/centro-de-justicia/editar/{id}', [CentroJusticiaController::class, 'update'])->name('update.centro');
    Route::delete('/ajustes/centro-justicia/{id}', [CentroJusticiaController::class, 'destroy'])->name('delete.centro');
    
    // Roles
    Route::get('/ajustes/roles', [RolController::class, 'index'])->name('roles');
    Route::get('/ajustes/rol/nuevo',[RolController::class, 'create'])->name('create.rol');
    Route::post('/ajustes/rol/nuevo', [RolController::class, 'store'])->name('post.rol');
    Route::get('/ajustes/rol/editar/{id}', [RolController::class, 'edit'])->name('edit.rol');
    Route::put('/ajustes/rol/editar/{id}', [RolController::class, 'update'])->name('update.rol');
    Route::delete('/ajustes/rol/{id}', [RolController::class, 'destroy'])->name('delete.rol');
    // Get roles vue js
    Route::get('/ajustes/roles/show', [RolController::class, 'show'])->name('get.roles');
    
    
    // Sala
    Route::get('/ajustes/sala', [SalaController::class, 'index'])->name('salas');
    Route::get('/ajustes/sala/nuevo', [SalaController::class, 'create'])->name('create.sala');
    Route::post('/ajustes/sala/nuevo', [SalaController::class, 'store'])->name('post.sala');
    Route::get('/ajustes/sala/editar/{id}', [SalaController::class, 'edit'])->name('edit.sala');
    Route::put('/ajustes/sala/editar/{id}', [SalaController::class, 'update'])->name('update.sala');
    Route::delete('/ajustes/sala/{id}', [SalaController::class, 'destroy'])->name('delete.sala');
    
    // Tipo de Audiencia
    Route::get('/ajustes/audiencias', [TipoAudienciaController::class, 'index'])->name('audiencias');
    Route::get('/ajustes/audiencia/nuevo', [TipoAudienciaController::class, 'create'])->name('create.audiencia');
    Route::post('/ajustes/audiencia/nuevo', [TipoAudienciaController::class, 'store'])->name('post.audiencia');
    Route::get('/ajustes/audiencia/editar/{id}', [TipoAudienciaController::class, 'edit'])->name('edit.audiencia');
    Route::put('/ajustes/audiencia/editar/{id}', [TipoAudienciaController::class, 'update'])->name('update.audiencia');
    Route::delete('/ajustes/audiencia/{id}', [TipoAudienciaController::class, 'destroy'])->name('delete.audiencia');
    
    
    // Tipo juicio
    Route::get('/ajustes/juicios', [JuiciosController::class, 'index'])->name('juicios');
    Route::get('/ajustes/juicio/nuevo', [JuiciosController::class, 'create'])->name('create.juicio');
    Route::post('/ajustes/juicio/nuevo', [JuiciosController::class, 'store'])->name('post.juicio');
    Route::get('/ajustes/juicio/editar/{id}', [JuiciosController::class, 'edit'])->name('edit.juicio');
    Route::put('/ajustes/juicio/editar/{id}', [JuiciosController::class, 'update'])->name('update.juicio');
    Route::delete('/ajustes/juicio/{id}', [JuiciosController::class, 'destroy'])->name('delete.juicio');
    
    
    // Control de usuarios
    Route::get('/ajustes/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::get('/ajustes/usuarios/nuevo-usuario', [UsuariosController::class, 'create'])->name('create.usuario');
    Route::post('/ajustes/usuarios/nuevo-usuario', [UsuariosController::class, 'store'])->name('post.usuario');
    Route::get('/ajustes/usuarios/nuevo-usuario/{id}', [UsuariosController::class, 'edit'])->name('edit.usuario');
    Route::put('/ajustes/usuarios/actualizar/{id}', [UsuariosController::class, 'update'])->name('update.usuario');
    Route::put('/ajustes/usuarios/actualizar/password/{id}', [UsuariosController::class, 'updatePassword'])->name('update.password');
    Route::delete('/ajustes/usuarios/nuevo-usuario/{id}', [UsuariosController::class, 'destroy'])->name('delete.usuario');
    
    
    // Roles de usuarios 
    Route::get('/ajustes/usuarios/roles', [TipoUsuariosController::class, 'index'])->name('roles.usuarios');
    Route::get('/ajustes/usuario/nuevo-rol', [TipoUsuariosController::class, 'create'])->name('create.roles.usuarios');
    Route::post('/ajustes/usuario/roles', [TipoUsuariosController::class, 'store'])->name('post.roles.usuarios');
    Route::get('/ajustes/usuario/rol/{id}', [TipoUsuariosController::class, 'edit'])->name('edit.roles.usuarios');
    Route::put('/ajustes/usuario/rol/{id}', [TipoUsuariosController::class, 'update'])->name('update.roles.usuarios');
    Route::delete('/ajustes/usuario/rol/{id}', [TipoUsuariosController::class, 'destroy'])->name('delete.roles.usuarios');


     // Confugurar OBS
     Route::get('/ajustes/conexión/remota/obs', [ConfigConexionObsController::class, 'index'])->name('config.obs');
     Route::post('/ajustes/obs', [ConfigConexionObsController::class, 'store'])->name('config.obs.post');
     Route::put('/ajustes/obs/{id}', [ConfigConexionObsController::class, 'update'])->name('config.obs.update');
     Route::get('/ajustes/obs/ip/address', [ConfigConexionObsController::class, 'getIPAddress'])->name('config.obs.ip.address');
    
    
    // Reserva se sala
    Route::get('/lista/audiencias/agendadas', [ReservaSalaController::class, 'index'])->name('lista.audiencias');
    Route::get('/audiencia/agendar-nueva-audiencia', [ReservaSalaController::class, 'create'])->name('book.new.room');
    Route::post('/audiencia/agendar-nueva-audiencia', [ReservaSalaController::class, 'store'])->name('post.room');
    Route::get('/audiencia/buscar', [ReservaSalaController::class, 'show'])->name('search.room');
    Route::get('/audiencia/reagendar/{id}', [ReservaSalaController::class, 'edit'])->name('edit.room');
    Route::put('/audiencia/reagendar/{id}', [ReservaSalaController::class, 'update'])->name('update.room');
    Route::put('/audiencia/cancelar/{id}', [ReservaSalaController::class, 'cancelarAudiencia'])->name('cancelar.room');
    Route::delete('/audiencia/agendada/{id}', [ReservaSalaController::class, 'destroy'])->name('delete.room');
    
    Route::put('/audiencia/expediente/pausar/{id}', [ReservaSalaController::class, 'pausarAudiencia'])->name('pausar.room');
    Route::put('/audiencia/expediente/finalizar/{id}', [ReservaSalaController::class, 'stopAudiencia'])->name('stop.room');
    Route::get('/audiencia/expediente/estado/{id}', [ReservaSalaController::class, 'getEstadoAudiencia'])->name('stop.room.estado');
    
    // Participantes
    Route::get('/agregar/participantes/{id}/{expediente_id}', [ParticipanteController::class, 'create'])->name('add.participante');
    Route::post('/agregar/participantes/', [ParticipanteController::class, 'store'])->name('post.participante');
    Route::get('/participantes/{id}', [ParticipanteController::class, 'show'])->name('show.participantes');
    Route::get('/personal/asistencia/{id}', [ParticipanteController::class, 'showAsistentesPersonal'])->name('show.personal.asistencia'); // Peronal son los que se registran al principio de la audiencia en agendar
    Route::delete('/agregar/participantes/{id}', [ParticipanteController::class, 'destroy'])->name('delete.participante');
    // Tomar asistencia
    Route::put('/asistencia/participante/{id}', [ParticipanteController::class, 'asistencia'])->name('asistencia.participante');
    Route::put('/asistencia/personal/{id}', [ParticipanteController::class, 'asistenciaPersonal'])->name('asistencia.personal');
    
    
    // Buscar Expediente
    Route::get('/buscar/audiencia', [BuscarExpedienteController::class, 'expediente'])->name('buscar.expediente');
    Route::get('/buscar/expediente/tipo-audiencias/all', [BuscarExpedienteController::class, 'getTipoAudiencia'])->name('get.tipo.audiencias');
    Route::get('/buscar/audiencia', [BuscarExpedienteController::class, 'buscarExpediente'])->name('buscar.expediente');
    
    // PDF Expediente
    Route::get('/expediente/pdf/descargar/{id}', [ExpedientePDFController::class, 'index'])->name('show.pdf.imprimir');
    Route::get('/expediente/pdf/{id}', [ExpedientePDFController::class, 'show'])->name('show.pdf.expediente');
    Route::get('/expediente/pdf/show/pdf/{id}', [ExpedientePDFController::class, 'showPDFDownload'])->name('show.pdf.expediente.download');
    
    
    // Agenda
    Route::get('/agenda', [AgendaController::class, 'show'])->name('agenda');
    Route::get('/agenda/eventos', [AgendaController::class, 'getEventosAudiencia'])->name('agenda.eventos');
    
    
    // Celebrar evento
    Route::get('/ingresar/evento', [AuditoriasController::class, 'login'])->name('ingresar.evento');
    Route::post('/ingresar/evento/singIn', [AuditoriasController::class, 'singInAudiencia'])->name('evento.singIn');
    Route::get('/evento/{id}', [AuditoriasController::class, 'showEvento'])->name('celebracion.evento');
    Route::get('/evento/salir/{id}', [AuditoriasController::class, 'exitAudiencia'])->name('salir.evento');
    
    
    // Notas
    Route::post('/nota', [NotaController::class, 'store'])->name('post.nota');
    Route::post('/nota/audiencia', [NotaController::class, 'addNota'])->name('post.nota.add'); // La nota es subida desde el reporte final de la audiencia
    Route::get('/notas/{id}', [NotaController::class, 'show'])->name('show.notas');
    Route::get('/nota/{id}', [NotaController::class, 'edit'])->name('edit.notas');
    Route::put('/nota/{id}', [NotaController::class, 'update'])->name('update.notas');
    Route::delete('/nota/delete/{id}', [NotaController::class, 'destroy'])->name('delete.notas');
    
    // Archivos
    Route::post('/archivo', [ArchivoController::class, 'store'])->name('post.archivo');
    Route::post('/archivo/audiencia', [ArchivoController::class, 'addFile'])->name('post.archivo.add');
    Route::get('/archivos/{id}', [ArchivoController::class, 'show'])->name('show.archivos');
    Route::get('/archivo/{id}', [ArchivoController::class, 'edit'])->name('edit.archivo');
    Route::put('/archivo/{id}', [ArchivoController::class, 'update'])->name('update.archivo');
    Route::delete('/archivo/delete/{id}', [ArchivoController::class, 'destroy'])->name('delete.archivo');
    Route::get('/archivo/decargar/{id}', [ArchivoController::class, 'dowload'])->name('dowload.archivo');

    // Subida de Video de la audiencia grabada
    Route::post('/evento/video', [VideoAudienciaController::class, 'store'])->name('video.store');
    Route::post('/evento/video/stream', [VideoAudienciaController::class, 'storeVideo'])->name('video.store.stram.video');
    Route::get('/evento/video/{id}', [VideoAudienciaController::class, 'dowloadVideoAudiencia'])->name('video.download.audiencia');
    
    
    // Invitado
    Route::get('/invitado/login', [InvitadoController::class, 'show'])->name('invitado.login');
    Route::get('/invitado/login/acceso/audiencia', [InvitadoController::class, 'singIn'])->name('invitado.singIn');
    
    // Reportes de auditorias
    Route::get('/lista/audiencias', [ReporteController::class, 'index'])->name('auditoria.lista');
    Route::get('/audiencia/lista/buscar', [ReporteController::class, 'showExpediente'])->name('auditoria.lista.buscar');
    Route::get('/audiencia/lista/ver/{id}', [ReporteController::class, 'show'])->name('auditoria.lista.ver');

    // Analisis estadistico
    Route::get('/analisis/estadistico', [AnalisisController::class, 'index'])->name('analisis.index');
    Route::get('/analisis/estadistico/audiencias-celebredas', [AnalisisController::class, 'audienciasCelebreadasAlYear'])->name('analisis.audiencias.celebradas');
    Route::get('/analisis/estadistico/videoconferencias-celebredas', [AnalisisController::class, 'totalDeVideoconferenciasSiyNo'])->name('analisis.audiencias.videoconferencias');
    Route::get('/analisis/estadistico/vide/consumo/mb', [AnalisisController::class, 'totalDatosConsumidoPorYear'])->name('analisis.audiencias.video.consumo.mb');

    // Disk
    Route::get('/info/disk', [DiskController::class, 'get_disk_total_space']);
});


Route::get('/pruebas', [DiskController::class, 'pruebas']);



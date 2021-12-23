<?php

namespace App\Http\Controllers;

use App\Models\Audiencia as AudienciaModel;
use App\Models\CentroJusticia as CentroJusticiaModel;
use App\Models\Expediente as ExpedienteModel;
use App\Models\Sala as SalaModel;
use App\Models\TipoAudiencia as TipoAudienciaModel;
use App\Models\TipoJuicio as TipoJuicioModel;
use App\Models\TokenAudiencia as TokenAudienciaModel;
use Illuminate\Http\Request;


class ReservaSalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO: Necesito obetner Expediente, SALA, AUDIENCIA, TIPO AUDIENCIA, Y ESTADO AUDIENCIA

        $expedientes = ExpedienteModel::orderBy('id', 'desc')->paginate(15);

        return view('reservas.index', compact('expedientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listOptions = [];

        // Centro de justicia
        $listaCentroJusticia =  CentroJusticiaModel::where('estado', 1)->select('id', 'nombre')->orderBy('nombre')->get();
        $salas               =  SalaModel::where('estado', 1)->select('id', 'sala')->orderBy('sala')->get();
        $listaTipoAudiencia  =  TipoAudienciaModel::where('estado', 1)->select('id', 'nombre')->orderBy('nombre')->get();
        $listaTipoJuicio     =  TipoJuicioModel::where('estado', 1)->select('id', 'nombre')->orderBy('nombre')->get();
        
        $audienciaLast      =  ExpedienteModel::all()->last(); // Obtiene el ultimo registro a la BD del expediente
        $numeroDeExpediente =  isset($audienciaLast->id)  ? $audienciaLast->id + 1:  $audienciaLast + 1; // Si id expediente existe accedemos al id y sumanos uno al siguiente expdiente si no asigamos 1 por defecto
        

        return view('reservas.create', compact(['listaCentroJusticia','salas', 'listaTipoAudiencia', 'listaTipoJuicio', 'numeroDeExpediente']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            // Expediente
            'folio'         => ['required', 'unique:expedientes'],
            'juez'          => ['required'],
            'juzgado'       => ['required'],
            'actor'         => ['required'],
            'demandado'     => ['required'],
            'secretario'    => ['required'],
            'juicio_id'     => ['required', 'numeric'],
            // Audiencia
            'centroJusticia_id' => ['required', 'numeric'],
            'sala_id' => ['required', 'numeric'],
            'tipo_id' => ['required', 'numeric'],
            //'expediente_id' => ['required'],
            //'estadoAudiencia_id' => ['required'],
            'fechaCelebracion'  => ['required'],
            'horaInicio'        => ['required'],
            'horaFinalizar'     => ['required'],
        ]);

        try {
            
                $newExpediente = new ExpedienteModel;
                $newExpediente->folio   = $request->folio;
                $newExpediente->juez    = $request->juez;
                $newExpediente->juzgado = $request->juzgado;
                $newExpediente->actor   = $request->actor;
                $newExpediente->demandado  = $request->demandado;
                $newExpediente->secretario = $request->secretario;
                $newExpediente->juicio_id  = $request->juicio_id;

                if($newExpediente->save()) {

                    $newAudiencia = new AudienciaModel;
                    $newAudiencia->centroJusticia_id  = $request->centroJusticia_id;
                    $newAudiencia->sala_id            = $request->sala_id;
                    $newAudiencia->tipo_id            = $request->tipo_id;
                    $newAudiencia->expediente_id      = $newExpediente->id;
                    $newAudiencia->estadoAudiencia_id = 1; //Por Defecto es el Uno "Programado" ['Programado, 'En progreso', 'Cancelado', 'Reprogramado', 'Finalizado']
                    $newAudiencia->fechaCelebracion   = $request->fechaCelebracion;
                    $newAudiencia->horaInicio         = $request->horaInicio;
                    $newAudiencia->horaFinalizar      = $request->horaFinalizar;

                    if($newAudiencia->save()) {
                        $audiencia_id = $newAudiencia->id;
                        
                        //return view(('reservas.participantes'));
                        // Generamos i¿un Token para la audiencia
                        $token = $this->tokenExpediente($newAudiencia->id);
                        $tokenAudiencia = new TokenAudienciaModel;
                        $tokenAudiencia->token = $token;
                        $tokenAudiencia->expediente_id =  $newAudiencia->id;
                        $tokenAudiencia->save();

                        return redirect("/agregar/participantes/$audiencia_id/$newExpediente->id");
                        
                        //return back()->with('success', 'Audiencia programada exitosamente!');
                    } 
                    // Si no se pudo registrar los datos a la tabla Audiencia Elimanos el expediente anterior registrado y repetimos el proceso
                    $deleteExpediente = ExpedienteModel::find($newExpediente->id);
                    $deleteExpediente->delete();

                    return back()->with('warning', 'No se pudo programar la audiencia Intente de nuevo por favor y verifique sus datos!');
                }

                return back()->with('warning', 'No se pudo programar la audiencia Intente de nuevo por favor!');

        } catch (\Throwable $th) {
            /* 
            Si occurre un errro al registar datos a la tabla AudienciaModel
            Elimino los datos del expediente para poder generar una nueva y mantener limpio la BD
            */
            $deleteExpediente = ExpedienteModel::findOrFail($newExpediente->id);
            $deleteExpediente->delete();

            return back()->with('error', 'Hubo un error al guaradar los datos. Verifique su conexion a Internte o recarga la página!'.$th);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            
            $expedientes = ExpedienteModel::where('id', $request->num)->get(); // el id es la referencia del numero de expdiente

            if(count($expedientes) > 0) {
                return view('reservas.resultado-busqueda', compact('expedientes'));
            }

            return back()->with('warning', 'No resultados del expediente numero: '. $request->num);

        } catch (\Throwable $th) {
            return back()->with('error', 'Hubo un error al consultar los datos!. Intentalo mas tarde..');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $expedienteDelete = ExpedienteModel::find($id);

            if($expedienteDelete->delete()) {
                return back()->with('success', "Expediente numero $expedienteDelete->id eliminado correctamente!");
            } 

            return back()->with('warning', "Expediente numero $expedienteDelete->id no se pudo eliminar, Intente de nuevo!");

        } catch (\Throwable $th) {
            return back()->with('error', 'Hubo un error al eliminar el dato.');
       }
    }

    
    // Genera un token para el expediente y es usado para acceder a la celebracion del audiecia
    public function tokenExpediente($id)
    {
        $cadena = "Sinjo_ABCDFGHIJKLMNOPQRSTVWXYZabcdefghijklmnopqrstvwxyz0123456789_".$id;
        $token = "";

        for($i = 0; $i < 60; $i++) {
            $token .= $cadena[rand(0,60)];       
        }

        return $token;

    }

}

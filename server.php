<?php
header('Content-type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

$arc_consecutivo="consecutivo.json";
$arc_dependencias="dependencias.json";

class SubDependencia
{
    public $codigo;
    public $nombre;
    public $color;
    public function __construct($codigo, $nombre, $color)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->color = $color;
    }
}
class Dependencia
{
    public $codigo;
    public $nombre;
    public $color;
    public $sub_dependencias;
    public function __construct($codigo, $nombre, $color, $sub_dependencias)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->color = $color;
        $this->sub_dependencias = $sub_dependencias;
    }
}

//Clase con formato de respuesta
class Respuesta{
    /*
        $status > Estado de la respuesta, 0 => Error, 1 => Success
    */
    public $status;
    public $mensaje;
    public $datos;
    public function __construct($status,$mensaje,$datos=null) {
        $this->status = $status;
        $this->mensaje = $mensaje;
        $this->datos = $datos;
    }
}

// verificamos si el archivo no existe o esta vacio
if (!file_exists($arc_consecutivo) || filesize($arc_consecutivo) == 0) {
    $codigo = new stdClass();
    $codigo->valor = 0;
    escribirArchivo($arc_consecutivo,$codigo);
}

// verificamos si el archivo no existe o esta vacio
if (!file_exists($arc_dependencias) || filesize($arc_consecutivo) == 0) {
    $archivo = new stdClass();
    $archivo->dependencias=[
        new Dependencia(1, "Decanatura", "000080", 0),
        new Dependencia(4, "Departamento de Sistemas", "800080", 1),
        new Dependencia(5, "Departamento de Electrónica", "008080", 1)
    ];
    $archivo->sub_dependencias=[[
        new SubDependencia(1, "Decano", "800000"),
        new SubDependencia(2, "Secretaría General", "9999ff")
    ],[
        new SubDependencia(1, "Jefe de Departamento", "808000"),
        new SubDependencia(2, "Tesorería", "ffff00")
    ]];
    escribirArchivo($arc_dependencias, $archivo);
}

// SI LA PETICION ES POR EL METODO GET
if(strtoupper($_SERVER['REQUEST_METHOD']) === 'GET'){

    // SI HAY UNA SUB_DEPENDENCIA SE QUIERE CONSULTAR
    if(isset($_GET["cod_dependencia"])){
        $codigo = intval($_GET["cod_dependencia"]);
        $esta = false;
        $datos = leerArchivo($arc_dependencias);
        $res =new stdClass();
        foreach ($datos->dependencias as $dependencia) {
            if($dependencia->codigo == $codigo){
                $res->color = $dependencia->color;
                $res->sub_dependencias = $datos->sub_dependencias[$dependencia->sub_dependencias];
                $esta = true;
                break;
            }
        }

        if ($esta) {
            responder($res);
        }
        error("El código de la dependencia no existe!");
    }

    if(isset($_GET["cod_subdependencia"]) && isset($_GET["dependencia"])){
        $codSub = intval($_GET["cod_subdependencia"]);
        $codDep = intval($_GET["dependencia"]);
        $estaDep = false;
        $estaSub = false;
        $datos = leerArchivo($arc_dependencias);
        $res =new stdClass();
        foreach ($datos->dependencias as $dependencia) {
            if($dependencia->codigo == $codDep){
                $res->sub_dependencias = $datos->sub_dependencias[$dependencia->sub_dependencias];
                $estaDep = true;
                break;
            }
        }
        if(!$estaDep){
            error("El código de la dependencia no existe!");
        }
        foreach ($res->sub_dependencias as $sub_dependencia) {
            if($sub_dependencia->codigo == $codSub){
                $res->sub_dependencias = null;
                $res->color = $sub_dependencia->color;
                $estaSub = true;
                break;
            }
        }

        if (!$estaSub) {
            error("El código de la subdependencia no existe!");
        }
        responder($res);
    }

    // OCURRE SIEMPRE Y CUANDO NO HAYA PASO DE VARIABLES
    // EN LA URL, EJEMPLO ACCESO AL INDEX
    $todo = leerArchivo($arc_dependencias);
    responder($todo->dependencias);
}

// SE EJECUTA SI LA PETICION ES POR EL METODO POST
if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
    $datos = leerArchivo($arc_consecutivo);
    $datos->valor++;
    escribirArchivo($arc_consecutivo,$datos);
    responder($datos->valor);
}

// FUNCIONES LECTURA ESCRITURA ARCHIVOS
function leerArchivo($nombreArchivo){
    return json_decode(file_get_contents($nombreArchivo));
}
function escribirArchivo($nombreArchivo,$datos){
    file_put_contents($nombreArchivo, json_encode($datos));
}

// Funciones de respuesta
function error($mensajeError = "Error")
{
    $respuesta = new Respuesta(0,$mensajeError);
    exit(json_encode($respuesta));
}
function responder($datos, $mensaje = "Success")
{
    $respuesta = new Respuesta(1,$mensaje, $datos);
    exit(json_encode($respuesta));
}
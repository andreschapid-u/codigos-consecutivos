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
        new Dependencia(1, "Decanatura", "0000080", 0),
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

// FUNCIONES LECTURA ESCRITURA ARCHIVOS
function leerArchivo($nombreArchivo){
    return json_decode(file_get_contents($nombreArchivo));
}
function escribirArchivo($nombreArchivo,$datos){
    file_put_contents($nombreArchivo, json_encode($datos));
}
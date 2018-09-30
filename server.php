<?php
$arc_consecutivo="consecutivo.json";
$arc_dependencias="dependencias.json";

class SubDependencia{
    public $codigo;
    public $nombre;
    public $color;
    public function __construct($codigo, $nombre, $color) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->color = $color;
    }
}
class Dependencia extends SubDependencia{
    public $codigo;
	public $nombre;
    public $color;
    public $sub_dependencias;
    public function __construct($codigo, $nombre, $color, $sub_dependencias) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->color = $color;
        $this->sub_dependencias = $sub_dependencias;
    }
}

if(!file_exists($arc_consecutivo)){
    $codigo = new stdClass();
    $codigo->valor = 0;
    file_put_contents($arc_consecutivo, json_encode($codigo));
}

if(!file_exists($arc_dependencias)){
    $archivo = new stdClass();
    $archivo->dependencias=[
        new Dependencia(1,"Decanatura","0000080",0),
        new Dependencia(4,"Departamento de Sistemas","800080",1),
        new Dependencia(5,"Departamento de Electrónica","008080",1)
    ];
    $archivo->sub_dependencias=[[
        new SubDependencia(1,"Decano","800000"),
        new SubDependencia(2,"Secretaría General","9999ff")
    ],[
        new SubDependencia(1,"Jefe de Departamento","808000"),
        new SubDependencia(1,"Tesorería","ffff00")
    ]];
    file_put_contents($arc_dependencias, json_encode($archivo));
}
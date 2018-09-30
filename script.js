// URL DEL SCRIPT SERVER
const urlServer = './server.php';

function ajax(metodos, url, funcionEjecutar, datos = '') {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200) {
                funcionEjecutar(JSON.parse(this.responseText));
                // funcionEjecutar(this.responseText);
            } else {}
        }
    };
    xmlhttp.open(metodos, url, true);
    xmlhttp.send(datos);
}

function log(data) {
    console.log(data);
}

// ajax('GET', urlServer, log);
ajax('GET', urlServer, llenarDependencias);

// CARGA CON DATOS EL SELECT DE DEPENDENCIAS
function llenarDependencias(datos) {
    var select = document.querySelector('#select1');
    select.innerHTML = '';
    var option = document.createElement('option');
    option.text = 'Seleccione';
    option.value = '-1';
    select.appendChild(option);
    for (const x in datos.datos) {
        var dep = datos.datos[x];
        var opt = document.createElement('option');
        opt.text = dep.nombre;
        opt.value = dep.codigo;
        select.appendChild(opt);
        // console.log(dep.nombre);
        // console.log(dep.sub_dependencias);
    }
}

function llenarSubDependencias(datos) {
    // log(select1);
    if (datos.status == 1) {
        var select1 = document.querySelector('#select1').value;
        var select = document.querySelector('#select2');
        select.innerHTML = '';
        var option = document.createElement('option');
        option.text = 'Seleccione';
        option.value = '-1';
        select.appendChild(option);
        var dependencia = document.querySelector('#dep');
        var subdependencia = document.querySelector('#subDep');

        dependencia.style.backgroundColor = '#' + datos.datos.color;
        dependencia.style.color = '#ffffff';
        dependencia.innerHTML = select1;
        subdependencia.style.backgroundColor = 'transparent';
        subdependencia.innerHTML = 'Elegir';
        // dependencia.sty = datos.datos.color;
        var sub_dependencias = datos.datos.sub_dependencias;
        for (const x in sub_dependencias) {
            var dep = sub_dependencias[x];
            var opt = document.createElement('option');
            opt.text = dep.nombre;
            opt.value = dep.codigo;
            select.appendChild(opt);
            // console.log(dep.nombre);
            // console.log(dep.codigo);
        }
    } else {
        mostrarError(datos.mensaje);
    }
}

function elegirSubDependencia(datos) {
    if (datos.status == 1) {
        var select = document.querySelector('#select2');
        var subdependencia = document.querySelector('#subDep');
        subdependencia.innerHTML = select.value;
        subdependencia.style.backgroundColor = '#' + datos.datos.color;
        subdependencia.style.color = '#ffffff';
    } else {
        mostrarError(datos.mensaje);
    }
}

document.querySelector('#select1').addEventListener('change', function() {
    if (this.value != -1) {
        console.log(this.value);
        // ajax('GET', urlServer + '?cod_dependencia=' + this.value, log);
        ajax('GET', urlServer + '?cod_dependencia=' + this.value, llenarSubDependencias);
    } else {
        var dependencia = document.querySelector('#dep');
        var subdependencia = document.querySelector('#subDep');
        dependencia.innerHTML = 'Elegir';
        dependencia.style.backgroundColor = 'transparent';
        dependencia.style.color = '#000';
        subdependencia.innerHTML = 'Elegir';
        subdependencia.style.backgroundColor = 'transparent';
        subdependencia.style.color = '#000';
        var select = document.querySelector('#select2');
        select.innerHTML = '';
        var option = document.createElement('option');
        option.text = 'Seleccione';
        option.value = '-1';
        select.appendChild(option);
        document.querySelector('#generado').innerHTML = 'Generar';
    }
});

document.querySelector('#select2').addEventListener('change', function() {
    if (this.value != -1) {
        var dep = document.querySelector('#select1').value;
        console.log(this.value);
        ajax('GET', urlServer + '?cod_subdependencia=' + this.value + '&dependencia=' + dep, elegirSubDependencia);
        // ajax('GET', urlServer + '?cod_subdependencia=' + this.value + '&dependencia=' +
        //     dep, log);
    } else {
        var subdependencia = document.querySelector('#subDep');
        subdependencia.innerHTML = 'Elegir';
        subdependencia.style.backgroundColor = 'transparent';
        subdependencia.style.color = '#000';
        document.querySelector('#generado').innerHTML = 'Generar';
    }
});

document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    var dep = document.querySelector('#select1').value;
    var subDep = document.querySelector('#select2').value;
    if (dep == -1 || subDep == -1) {
        mostrarError('Debe seleccionar una dependecia y subdepencia primero!!');
    } else {
        ajax('POST', urlServer, consecutivoGenerado);
    }
});

function consecutivoGenerado(datos) {
    if (datos.status == 1) {
        document.querySelector('#generado').innerHTML = datos.datos;
        log(datos.datos.datos);
    } else {
        mostrarError(datos.mensaje);
    }
}

function mostrarError(mensaje) {
    document.querySelector('#error').innerHTML = mensaje;
    document.querySelector('#error').style.display = 'block';
    setTimeout(() => {
        document.querySelector('#error').style.display = 'none';
    }, 4500);
}
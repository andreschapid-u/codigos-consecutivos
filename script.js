// URL DEL SCRIPT SERVER
const urlServer = './server.php';

function ajax(metodos, url, funcionEjecutar) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == XMLHttpRequest.DONE) {
            if (this.status == 200) {
                funcionEjecutar(JSON.parse(this.responseText));
                // funcionEjecutar(this.responseText);
            } else {

            }
        }
    };
    xmlhttp.open(metodos, url, true);
    xmlhttp.send();
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
    var select1 = document.querySelector('#select1').value;
    log(select1)
    var select = document.querySelector('#select2');
    select.innerHTML = '';
    var option = document.createElement('option');
    option.text = 'Seleccione';
    option.value = '-1';
    select.appendChild(option);
    var dependencia = document.querySelector("#dep");
    if (datos.status == 1) {
        // log(datos)
        dependencia.style.backgroundColor = "#" + datos.datos.color;
        dependencia.style.color = "#ffffff";
        dependencia.innerHTML = select1;
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

    }
}


document.querySelector('#select1').addEventListener('change', function() {
    if (this.value != -1) {
        console.log(this.value);
        // ajax('GET', urlServer + '?cod_dependencia=' + this.value, log);
        ajax('GET', urlServer + '?cod_dependencia=' + this.value, llenarSubDependencias);
    }
});

document.querySelector('#select2').addEventListener('change', function() {
    if (this.value != -1) {
        var dep = document.querySelector("#select1").value;
        console.log(this.value);
        ajax('GET', urlServer + '?cod_subdependencia=' + this.value + '&dependencia=' +
            dep, log);
        // ajax('GET', urlServer + '?cod_subdependencia=' + this.value + '&dependencia=' +
        //     dep, log);
    }
});

document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    ajax('POST', urlServer, log);
});
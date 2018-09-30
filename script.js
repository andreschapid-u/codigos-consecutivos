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
                alert('ERROR');
            }
        }
    };
    xmlhttp.open(metodos, url, true);
    xmlhttp.send();
}

function log(data) {
    console.log(data);
}

ajax('GET', urlServer, log);
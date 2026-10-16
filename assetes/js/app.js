/*document.addEventListener('DOMContentLoaded', function(){
    const formulario = document.getElementById('frmLogin');
    
    formulario.addEventListener('submit',function(e){
        e.preventDefault();

        const user = document.getElementById('user').value.trim();
        const pass = document.getElementById('pass').value.trim();

        if(user==="tito" && pass==="123"){        
            window.location.href="home.php";
        }else{
            alert("dato incorrectos");
        }
    });

 
}); */

function calcularCuadrado() {
    const ladoInput = document.getElementById('txtLado');
    const areaInput = document.getElementById('txtA');
    const perimetroInput = document.getElementById('txtP');

    if (!ladoInput || !areaInput || !perimetroInput) {
        return;
    }

    const lado = parseFloat(ladoInput.value);

    if (isNaN(lado) || lado <= 0) {
        alert('Ingrese un lado válido mayor a cero');
        ladoInput.focus();
        return;
    }

    areaInput.value = (lado * lado).toFixed(2);
    perimetroInput.value = (lado * 4).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const ladoInput = document.getElementById('txtLado');

    if (ladoInput) {
        ladoInput.addEventListener('input', calcularCuadrado);
    }
});

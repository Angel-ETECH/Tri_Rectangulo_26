function calcularTriangulo(mostrarError = false) {
    const aInput = document.getElementById('txtCatetoA');
    const bInput = document.getElementById('txtCatetoB');
    if (!aInput || !bInput) return;
    const a = Number(aInput.value);
    const b = Number(bInput.value);
    const valido = Number.isFinite(a) && Number.isFinite(b) && a > 0 && b > 0 && a <= 1e9 && b <= 1e9;
    const aviso = document.getElementById('avisoCalculo');
    if (!valido) {
        ['txtH', 'txtA', 'txtP'].forEach(id => document.getElementById(id).value = '');
        aviso.textContent = mostrarError ? 'Ingresa ambos catetos mayores a cero y hasta 1000000000.' : 'Completa ambos catetos con valores positivos.';
        return;
    }
    const h = Math.hypot(a, b);
    document.getElementById('txtH').value = h.toFixed(2);
    document.getElementById('txtA').value = (a * b / 2).toFixed(2);
    document.getElementById('txtP').value = (a + b + h).toFixed(2);
    aviso.textContent = 'Hipotenusa y perímetro en la unidad ingresada; área en unidades cuadradas. Resultados redondeados a 2 decimales.';
}
document.addEventListener('DOMContentLoaded', () => {
    ['txtCatetoA', 'txtCatetoB'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', () => calcularTriangulo());
    });
    document.getElementById('btnCalcular')?.addEventListener('click', () => calcularTriangulo(true));
    calcularTriangulo();
});

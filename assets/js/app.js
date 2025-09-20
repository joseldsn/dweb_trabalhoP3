(function enableValidation() {
  document.addEventListener('submit', function (e) {
    const form = e.target.closest('form.needs-validation');
    if (!form) return;
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    form.classList.add('was-validated');
  }, true);
})();


function toast(msg = "Pronto!") {
  let t = document.getElementById('toastLite');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toastLite';
    t.className = 'toast-lite';
    t.style.display = 'none';
    document.body.appendChild(t);
  }
  t.textContent = msg || 'Pronto!';
  t.style.display = 'block';
  void t.offsetWidth;
  t.classList.add('show');
  setTimeout(() => {
    t.classList.remove('show');
    t.style.display = 'none';
    t.textContent = '';
  }, 1800);
}

document.addEventListener('DOMContentLoaded', () => {
  const q = document.getElementById('buscaPublica');
  const tipo = document.getElementById('filtroTipoPublico');
  if (q && tipo) {
    const form = tipo.closest('form') || q.closest('form');
    let timer;
    const go = () => { if (form) form.requestSubmit ? form.requestSubmit() : form.submit(); };
    q.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(go, 350); });
    tipo.addEventListener('change', go);
  }

  const flash = document.getElementById('flash-data');
  if (flash && flash.dataset.msg) { toast(flash.dataset.msg); }
});


export function btnWaitSubmit () {
  document.querySelectorAll('.btn-wait-submit').forEach((elemento) => {
    elemento.onclick = function () {
      if (this.form.checkValidity()) {
        this.innerHTML = this.getAttribute('data-wait')
        this.disabled = true
        this.form.submit()
      }
    }
  })
}

export function reloadPage () {
  window.location.reload()
}


export function createDatatables (jQuery) {
  jQuery(function () {
    jQuery('.ajax-datatables').each(function (i, datatable) {
      const dataUrl = jQuery(datatable).data('url')
      const columns = jQuery(datatable).data('url-columns')

      jQuery(datatable).DataTable({
        processing: true,
        serverSide: true,
        ajax: dataUrl,
        columns: generateDatatableColumns(columns)
      })
    })
  })
}

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

function generateDatatableColumns (columns) {
  const out = []
  const columnsArray = columns.split(',')
  columnsArray.forEach(function (column) {
    out.push({
      data: column
    })
  })
  return out
}

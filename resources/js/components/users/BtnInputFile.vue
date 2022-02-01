<template>
    <button ref="btnUpload" type="button" @click="handleClick">
        {{text}}
    </button>
</template>

<script type="module">
import Swal from 'sweetalert2'
import { ref } from '@vue/reactivity'
import { postFileApi } from '../../api'
import { changeStatusBtn, completeException, completeSuccess } from '../../helpers/functions'

export default {
  props: ['text', 'action', 'maxfilesize'],

  setup (props) {
    const showSwal = ref(false)
    const btnUpload = ref(null)

    const handleClick = () => {
      showSwal.value = true
      modalUpload(props.maxfilesize).then((result) => {
        if (result.isConfirmed && result.value) {
          changeStatusBtn(btnUpload.value, true, 'Wait...')
          showUploading()
          sendFileToServer(props, result, btnUpload, showSwal)
        }
      }).finally(r => { showSwal.value = false })
    }
    return {
      handleClick,
      props,
      showSwal,
      btnUpload
    }
  }
}

const sendFileToServer = (props, result, btnUpload, showSwal) => {
  postFileApi(props.action, result.value)
    .then(completeSuccess)
    .catch(completeException)
    .finally(r => {
      changeStatusBtn(btnUpload.value, false, props.text)
      showSwal.value = false
    })
}

const modalUpload = (maxFileSize) => {
  return Swal.fire({
    title: 'Select a excel file',
    input: 'file',
    confirmButtonText: 'Upload',
    showCancelButton: true,
    allowOutsideClick: false,
    inputAttributes: {
      accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'aria-label': 'Upload your profile picture'
    },
    inputValidator: (value) => {
      return new Promise((resolve) => {
        if (!value) {
          resolve('You must select a file')
        } else if (value.size > parseInt(maxFileSize)) {
          resolve('The file exceeds the allowed weight of: ' + maxFileSize + ' bytes')
        } else {
          resolve()
        }
      })
    }
  })
}

const showUploading = () => {
  Swal.fire({
    title: 'Uploading...',
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  })
}

</script>

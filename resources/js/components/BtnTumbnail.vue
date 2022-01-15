<template>
  <a class="cursor-pointer" target="_blank" data-bs-toggle="modal" :data-bs-target="'#showImg' + id">
      <img :src="linktumbnail" :class="imgClass" alt="img">
  </a>

  <button class="btn btn-xs btn-danger text-light py-0 mt-1 d-flex align-items-center justify-content-center w-100"
   type="button" @click="removeImg" v-if="textButtonDelete" :disabled="buttonDeleteDisabled">
      <em class="fas fa-trash-alt"></em> <span class="ms-1">{{ textButtonDelete }}</span>
  </button>

  <div class="modal fade" :id="'showImg' + id" tabindex="-1" :aria-labelledby="'showImg' + id" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <img :src="linkimg" alt="img" class="w-100" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from 'sweetalert2'
import { deleteApi } from '../api'
import { ref } from '@vue/reactivity'
import global from '../store/global'

export default {
  props: ['linkdelete', 'textbuttondelete', 'linkimg', 'linktumbnail', 'id', 'imgclass'],

  setup (props) {
    const showSwal = ref(false)
    const textButtonDelete = ref(props.textbuttondelete)
    const buttonDeleteDisabled = ref(false)
    const imgClass = props.imgclass + ' img-thumbnail'

    const removeImg = () => {
      showSwal.value = true

      showModalConfirm().then((result) => {
        showSwal.value = false
        if (result.isConfirmed) {
          textButtonDelete.value = 'Wait...'
          buttonDeleteDisabled.value = true
          removeImgServer(props.linkdelete, props.id).finally(r => {
            textButtonDelete.value = props.textbuttondelete
            buttonDeleteDisabled.value = false
          })
        }
      })
    }

    return {
      removeImg,
      props,
      showSwal,
      textButtonDelete,
      buttonDeleteDisabled,
      imgClass
    }
  }
}

const showModalConfirm = () => {
  return Swal.fire({
    title: 'Are you sure?',
    text: 'Are you sure of remove image?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  })
}

const removeImgServer = (link, id) => {
  global.setState('numfiles', global.state.numfiles - 1)

  return deleteApi(link)
    .then(res => {
      if (res.data.status === 200) {
        Swal.fire(res.data.message)
        document.querySelector('#col_id_' + id)?.remove()
      } else {
        Swal.fire('Error', res.data.message)
      }
    })
    .catch(() => {
      Swal.fire('Operation cancelled')
    })
}
</script>

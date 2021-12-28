<template>
    <input ref="inputRef" @change="handleFiles" class="form-control" type="file" :id="id" :name="name" :accept="accept" v-bind:multiple="multiple">
    <div class="mt-1"><span class="text-muted">(Number of photos allowed: {{ allowed }})</span></div>
</template>

<script>
import Swal from 'sweetalert2'
import { computed, ref } from '@vue/reactivity'
import { inject } from '@vue/runtime-core'

export default {
  props: ['id', 'name', 'maxfiles', 'numfiles', 'multiple', 'accept'],

  setup (props) {
    const global = inject('global')
    global.setState('numfiles', props.numfiles ?? 0)

    const showSwal = ref(false)
    const inputRef = ref(null)
    const allowed = computed(() => props.maxfiles - global.state.numfiles)

    const handleFiles = (event) => {
      const numAddFiles = event.target.files.length
      const totalFiles = numAddFiles + parseInt(global.state.numfiles)
      const allowedFiles = props.maxfiles - global.state.numfiles

      if (totalFiles > props.maxfiles && numAddFiles > 0) {
        showSwal.value = true
        inputRef.value.value = null
        Swal.fire('You can not upload more than ' + (allowedFiles) + ' files').then(r => (showSwal.value = false))
      }
    }

    return {
      handleFiles,
      inputRef,
      props,
      showSwal,
      global,
      allowed
    }
  }
}

</script>

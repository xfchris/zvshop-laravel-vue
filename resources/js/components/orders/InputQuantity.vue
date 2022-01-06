<template>
    <form ref="form" :action="action" method="post">
        <input type="hidden" name="_token" :value="_token">
        <div class="form-group">
            <div class="input-group mb-3 w-140px cursor-pointer" @click="handleClick">
                <span class="input-group-text">-</span>
                <input ref="inputQuantity" type="text" class="form-control text-center" name="quantity"
                    :value="value"
                    :title="inputtitle" readonly>
                <span class="input-group-text">+</span>
            </div>
        </div>
    </form>
</template>

<script>
import Swal from 'sweetalert2'
import { ref } from '@vue/reactivity'

export default {
  props: ['value', 'inputtitle', 'action', '_token', 'maxquantity'],

  setup (props) {
    const showSwal = ref(false)
    const form = ref(null)
    const inputQuantity = ref(null)
    const handleClick = (event) => {
      modalChangeQuantity(props.maxquantity, props.value).then((result) => {
        if (result.isConfirmed) {
          inputQuantity.value.value = result.value
          form.value.submit()
        }
      })
    }

    return {
      form,
      inputQuantity,
      handleClick,
      props,
      showSwal
    }
  }
}

const modalChangeQuantity = (maxQuantity, inputQuantity) => {
  return Swal.fire({
    title: 'Change quantity',
    input: 'number',
    inputValue: inputQuantity,
    inputAttributes: {
      min: 1,
      max: maxQuantity
    },
    showCancelButton: true,
    inputValidator: (value) => {
      if (parseInt(value) < 1 || value > parseInt(maxQuantity)) {
        return 'Can not add more than ' + maxQuantity + ' products'
      }
    }
  })
}

</script>

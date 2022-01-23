<template>
    <button ref="btn" @click="handleClick">
        <slot></slot>
    </button>
</template>

<script type="module">
import { ref } from '@vue/reactivity'
import { postApi } from '../api'
import { changeStatusBtn, completeException, completeSuccess, submitForm } from '../functions'

export default {
  props: ['action'],

  setup (props) {
    const btn = ref(null)

    const handleClick = () => {
      if (!props.action) {
        submitForm(btn.value)
      } else {
        submitAjax(btn.value, props, btn.value.innerHTML)
      }
    }

    return {
      handleClick,
      props,
      btn
    }
  }
}

const submitAjax = (btn, props, text) => {
  changeStatusBtn(btn, true, 'Wait...')
  postApi(props.action)
    .then(completeSuccess)
    .catch(completeException)
    .finally(r => changeStatusBtn(btn, false, text))
}

</script>

<template>
    <button ref="btn" @click="handleClick">
        <slot></slot>
    </button>
</template>

<script type="module">
import { ref } from '@vue/reactivity'
import { changeStatusBtn, completeException, completeSuccess } from '../helpers/functions'
import { postApi } from '../api'

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

function submitForm (btn) {
  if (btn.form.checkValidity()) {
    changeStatusBtn(btn, true, 'Wait...')
    btn.form.submit()
  }
}

function submitAjax (btn, props, text) {
  changeStatusBtn(btn, true, 'Wait...')
  postApi(props.action)
    .then(completeSuccess)
    .catch(completeException)
    .finally(r => changeStatusBtn(btn, false, text))
}

</script>

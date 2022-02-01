<template>
    <button ref="btn" @click="handleClick">
        <slot></slot>
    </button>
</template>

<script type="module">
import { ref } from '@vue/reactivity'
import { submitForm, submitAjax } from '../helpers/functions'

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

</script>

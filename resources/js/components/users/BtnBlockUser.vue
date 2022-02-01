<template>
  <button
    @click="activeInactiveUser"
    class="
      btn btn-xs btn-secondary
      py-0
      d-flex
      align-items-center
      ms-1
      text-nowrap
      w-100
      justify-content-center
    "
  >
    <slot />
  </button>
</template>

<script>
import Swal from 'sweetalert2'
import { postApi } from '../../api'
import { reloadPage } from '../../helpers/functions'
import { ref } from '@vue/reactivity'

export default {
  props: ['link', 'banned_until'],

  setup (props) {
    const showSwal = ref(false)

    const activeInactiveUser = () => {
      showSwal.value = true

      if (props.banned_until) {
        modalActiveUser().then((result) => {
          showSwal.value = false
          if (result.isConfirmed) {
            setUserBlock(props.link, null)
          }
        })
      } else {
        modalInactiveUser(props.link).then(() => {
          showSwal.value = false
        })
      }
    }

    return {
      activeInactiveUser,
      props,
      showSwal
    }
  }
}

const modalActiveUser = () => {
  return Swal.fire({
    title: 'Are you sure?',
    text: 'Are you sure of activate user?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  })
}

const modalInactiveUser = (link) => {
  return Swal.fire({
    title: 'Inactivate a user',
    input: 'select',
    inputOptions: {
      Days: {
        5: '5 days',
        10: '10 days',
        20: '20 days'
      },
      Months: {
        30: '1 month',
        90: '3 months',
        182: '6 months'
      },
      Years: {
        365: '1 year',
        1095: '3 years',
        1825: '5 years',
        3650: '10 years'
      }
    },
    inputValue: 5,
    inputPlaceholder: 'Select a option',
    showCancelButton: true,

    inputValidator: (value) => {
      return new Promise((resolve) => {
        resolve()
        setUserBlock(link, value)
      })
    }
  })
}

const setUserBlock = (link, value) => {
  postApi(link, { banned_until: value }).then(res => {
    if (res.data.status === 200) {
      Swal.fire('User ' + ((value === null) ? 'Activated' : 'Inactivated')).then(reloadPage)
    } else {
      Swal.fire('Error', res.data.message)
    }
  }).catch(() => {
    Swal.fire('Operation cancelled')
  })
}
</script>

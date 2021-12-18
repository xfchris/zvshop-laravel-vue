import BtnBlockUser from '../../../../resources/js/components/users/BtnBlockUser'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'

describe('Inactive a user by 5 days', () => {
  const wrapper = mount(BtnBlockUser, {
    props: {
      link: 'fakeLink',
      banned_until: null
    }
  })

  it('can inactivate a user by 5 days', () => {
    expect(wrapper.exists()).toBe(true)
    expect(wrapper.html()).toContain('button')

    wrapper.find('button').trigger('click')

    setTimeout(() => {
      expect(Swal.isVisible()).toBeTruthy()
      expect(Swal.getText().textContent).toEqual('Inactivate a user')
    })
  })
})

describe('Active user', () => {
  const wrapper = mount(BtnBlockUser, {
    props: {
      link: 'fakeLink',
      banned_until: 5
    }
  })

  it('can activate a user', () => {
    expect(wrapper.exists()).toBe(true)
    expect(wrapper.html()).toContain('button')

    wrapper.find('button').trigger('click')

    setTimeout(() => {
      expect(Swal.isVisible()).toBeTruthy()
      expect(Swal.getTitle().textContent).toEqual('Are you sure?')
    })
  })
})

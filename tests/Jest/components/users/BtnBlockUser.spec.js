import BtnBlockUser from '../../../../resources/js/components/users/BtnBlockUser'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'
import axios from 'axios'

jest.mock('axios')
window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})

describe('Inactive a user by 5 days', () => {
  const wrapper = mount(BtnBlockUser, {
    props: {
      link: 'fakeLink',
      banned_until: null
    }
  })

  it('can inactivate a user by 5 days', (done) => {
    axios.post.mockResolvedValue({ data: { status: 'success' } })

    expect(wrapper.html()).toContain('button')
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('Inactivate a user')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toEqual('User Inactivated')
      Swal.clickConfirm()
      expect(wrapper.vm.showSwal).toBe(false)
      done()
    })
  })

  it('can no inactivate a admin user', (done) => {
    const message = 'The account role is admin'
    axios.post.mockResolvedValue({ data: { status: 'error', message } })

    expect(wrapper.html()).toContain('button')
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('Inactivate a user')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toEqual('Error')
      expect(Swal.getHtmlContainer().textContent).toEqual(message)

      Swal.clickConfirm()
      expect(wrapper.vm.showSwal).toBe(false)
      done()
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

  it('can activate a user', (done) => {
    axios.post.mockResolvedValue({ data: { status: 'success' } })

    expect(wrapper.html()).toContain('button')
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('Are you sure?')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toEqual('User Activated')
      Swal.clickConfirm()
      expect(wrapper.vm.showSwal).toBe(false)
      done()
    })
  })
})

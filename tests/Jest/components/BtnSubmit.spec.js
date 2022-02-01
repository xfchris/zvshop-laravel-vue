import BtnSubmit from '../../../resources/js/components/BtnSubmit.vue'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'
import axios from 'axios'

jest.mock('axios')
window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})

describe('button sumit ajax wait', () => {
  const wrapper = mount(BtnSubmit, {
    props: {
      action: 'fakeLink'
    }
  })

  it('disable button when clicked and request is ajax', () => {
    axios.post.mockResolvedValue({ data: { status: 200 } })
    wrapper.vm.btn.click()

    expect(wrapper.vm.btn.disabled).toBe(true)
    expect(wrapper.vm.btn.innerText).toContain('Wait...')
  })

  it('cannot disable button when clicked with a general error', (done) => {
    axios.post.mockResolvedValue({ response: null })
    wrapper.vm.btn.click()

    expect(wrapper.vm.btn.disabled).toBe(true)
    expect(wrapper.vm.btn.innerText).toContain('Wait...')

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toContain('Operation cancelled')
      Swal.close()
      done()
    })
  })

  it('cannot disable button when clicked with a validation error', (done) => {
    const errorsToken = ['It\'s not authorized']
    axios.post.mockResolvedValue({
      response: {
        data: {
          errors: {
            errorsToken
          }
        }
      }
    })
    wrapper.vm.btn.click()

    expect(wrapper.vm.btn.disabled).toBe(true)
    expect(wrapper.vm.btn.innerText).toContain('Wait...')

    setTimeout(() => {
      expect(Swal.getHtmlContainer().textContent).toContain(errorsToken[0])
      Swal.close()
      done()
    })
  })
})

describe('Button sumit wait', () => {
  const wrapper = mount(BtnSubmit)

  it('disable button when clicked', (done) => {
    axios.post.mockResolvedValue({ data: { status: 200 } })

    Object.defineProperty(wrapper.vm.btn, 'form', {
      value: {
        checkValidity: () => true,
        submit: () => true
      }
    })

    wrapper.vm.btn.click()

    setTimeout(() => {
      expect(wrapper.vm.btn.disabled).toBe(true)
      expect(wrapper.vm.btn.innerText).toContain('Wait...')
      done()
    })
  })
})

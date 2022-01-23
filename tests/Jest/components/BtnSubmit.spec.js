import BtnSubmit from '../../../resources/js/components/BtnSubmit.vue'
import { mount } from '@vue/test-utils'

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
  it('disable button when clicked and request is ajax', (done) => {
    wrapper.find('button').trigger('click')
    setTimeout(() => {
      done()
    })
  })
})

describe('Button sumit wait', () => {
  const wrapper = mount(BtnSubmit)

  it('disable button when clicked', (done) => {
    wrapper.find('button').trigger('click')
    setTimeout(() => {
      expect(wrapper.vm.btn.disabled).toBe(false)
      done()
    })
  })
})

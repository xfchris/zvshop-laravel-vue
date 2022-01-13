import BtnBlockUser from '../../../../resources/js/components/orders/InputQuantity'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'

window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})
window.HTMLFormElement.prototype.submit = () => jest.fn()

describe('Change Quantity', () => {
  const wrapper = mount(BtnBlockUser, {
    props: {
      value: '1',
      inputtitle: 'Quantity',
      action: 'fakeLink',
      _token: 'fakeHash',
      maxquantity: '3'
    }
  })

  it('can change the product quantity', (done) => {
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('.input-quantity').trigger('click')
    expect(wrapper.vm.showSwal).toBe(true)

    expect(Swal.getTitle().textContent).toEqual('Change quantity')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(wrapper.vm.showSwal).toBe(false)
      done()
    })
  })
})

describe('Change Quantity errors', () => {
  const wrapper = mount(BtnBlockUser, {
    props: {
      value: '4',
      inputtitle: 'Quantity',
      action: 'fakeLink',
      _token: 'fakeHash',
      maxquantity: '3'
    }
  })

  it('can no change the product quantity', () => {
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('.input-quantity').trigger('click')
    expect(wrapper.vm.showSwal).toBe(true)

    expect(Swal.getTitle().textContent).toEqual('Change quantity')
    Swal.clickConfirm()
    expect(wrapper.vm.showSwal).toBe(true)
  })
})

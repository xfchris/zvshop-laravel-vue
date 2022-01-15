import BtnTumbnail from '../../../resources/js/components/BtnTumbnail'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'
import axios from 'axios'

jest.mock('axios')
window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})

describe('Remove Tumbnail', () => {
  const wrapper = mount(BtnTumbnail, {
    props: {
      linkdelete: 'https://fakelink.com/image/1/delete',
      textbuttondelete: 'Remove',
      linkimg: 'https://fakelink.com/image.jpg',
      linktumbnail: 'https://fakelink.com/imageTumbnail.jpg',
      id: null
    }
  })

  it('can remove a image', (done) => {
    const message = 'Image removed'
    axios.delete.mockResolvedValue({ data: { status: 200, message } })

    expect(wrapper.html()).toContain('button')
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('Are you sure?')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toEqual(message)
      Swal.clickConfirm()
      expect(wrapper.vm.showSwal).toBe(false)
      done()
    })
  })

  it('can no remove a image', (done) => {
    const message = 'Image no removed'
    axios.delete.mockResolvedValue({ data: { status: 400, message } })

    expect(wrapper.html()).toContain('button')
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('Are you sure?')
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

import InputFile from '../../../resources/js/components/InputFile'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'

jest.mock('axios')
window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})
describe('Select image from system', () => {
  const wrapper = mount(InputFile, {
    props: {
      id: 1,
      name: 'test',
      maxfiles: '8',
      numfiles: '6',
      multiple: true,
      accept: 'image/*'
    }
  })

  it('can show max allowed files', () => {
    expect(wrapper.html()).toContain('Number of photos allowed: ' + 2)
    expect(wrapper.vm.showSwal).toBe(false)
  })

  it('can no upload more than 3 images', (done) => {
    const event = {
      target: {
        files: [
          { name: 'image.png', size: 50000, type: 'image/png' },
          { name: 'image.png', size: 50000, type: 'image/png' },
          { name: 'image.png', size: 50000, type: 'image/png' }
        ]
      }
    }
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.vm.handleFiles(event)

    expect(wrapper.vm.showSwal).toBe(true)
    expect(Swal.getTitle().textContent).toEqual('You can not upload more than 2 files')
    Swal.clickConfirm()
    setTimeout(() => {
      expect(wrapper.vm.showSwal).toBe(false)
      done()
    })
  })
})

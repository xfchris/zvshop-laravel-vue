import BtnInputFile from '../../../../resources/js/components/users/BtnInputFile.vue'
import { mount } from '@vue/test-utils'
import Swal from 'sweetalert2'
import axios from 'axios'
import MockAdapter from 'axios-mock-adapter'

// jest.mock('axios')
window.scrollTo = jest.fn()
Object.defineProperty(window, 'location', {
  writable: true,
  value: { reload: jest.fn() }
})
window.HTMLFormElement.prototype.submit = () => jest.fn()

describe('File upload', () => {
  const wrapper = mount(BtnInputFile, {
    props: {
      text: 'Upload',
      action: 'fakeLink',
      maxfilesize: '3000'
    }
  })
  it('cannot upload a file that exceeds the maximum size allowed', (done) => {
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')
    expect(wrapper.vm.showSwal).toBe(true)

    expect(Swal.getTitle().textContent).toEqual('Select a excel file')

    Object.defineProperty(Swal.getInput(), 'files', {
      value: [{ name: 'excel.xlsx', size: 3001, type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }],
      writable: false
    })
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getValidationMessage().textContent).toContain('The file exceeds the allowed weight of:')
      Swal.close()
      done()
    })
  })

  it('cannot upload a empty file', (done) => {
    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')
    expect(wrapper.vm.showSwal).toBe(true)

    expect(Swal.getTitle().textContent).toEqual('Select a excel file')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getValidationMessage().textContent).toContain('You must select a file')
      Swal.close()
      done()
    })
  })

  it('can upload a file', (done) => {
    (new MockAdapter(axios, { delayResponse: 2000 })).onPost().reply(200, { status: 200, message: '' })

    expect(wrapper.vm.showSwal).toBe(false)
    wrapper.find('button').trigger('click')
    expect(wrapper.vm.showSwal).toBe(true)

    Object.defineProperty(Swal.getInput(), 'files', {
      value: [{ name: 'excel.xlsx', size: 3000, type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }],
      writable: false
    })

    expect(Swal.getTitle().textContent).toEqual('Select a excel file')
    Swal.clickConfirm()

    setTimeout(() => {
      expect(Swal.getTitle().textContent).toContain('Uploading...')
      Swal.close()
      done()
    })
  })
})

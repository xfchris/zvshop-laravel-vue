import axios from 'axios'

export async function postApi (url, data = null) {
  try {
    return await axios.post(url, data)
  } catch (error) {
    return error
  }
}

export async function postFileApi (url, file) {
  try {
    const formData = new FormData()
    formData.append('file', file)
    return await axios.post(url, formData, {
      headers: {
        'content-type': 'multipart/form-data'
      }
    })
  } catch (error) {
    return error
  }
}

export async function deleteApi (url, data) {
  try {
    return await axios.delete(url, data)
  } catch (error) {
    return error
  }
}

import axios from 'axios'

export async function postApi (url, data) {
  try {
    return await axios.post(url, data)
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

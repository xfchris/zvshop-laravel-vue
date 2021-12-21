import axios from 'axios'

export async function postApi (url, data) {
  try {
    return await axios.post(url, data)
  } catch (error) {
    return error
  }
}

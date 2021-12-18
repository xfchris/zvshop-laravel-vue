import Axios from 'axios'

export async function activeInactiveUserApi (url, data) {
  try {
    return await Axios.post(url, data)
  } catch (error) {
    return error
  }
}

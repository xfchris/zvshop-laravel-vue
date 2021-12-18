import Axios from 'axios'

export function activeInactiveUserApi (url, data) {
  try {
    return Axios.post(url, data)
  } catch (error) {
    console.log('error', error)
  }
}

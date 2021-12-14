import { reactive, readonly } from 'vue'

const state = reactive({
})
const setState = function (id, newData) {
  state[id] = newData
}
export default { state: readonly(state), setState }

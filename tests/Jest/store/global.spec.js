import global from '../../../resources/js/store/global'

test('it change the state correctly', () => {
  global.setState('num', 2)
  expect(global.state.num).toBe(2)
})

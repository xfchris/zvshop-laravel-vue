import global from './global'

test('it change the state correctly', () => {
  global.setState('num', 2)
  expect(global.state.num).toBe(2)
})

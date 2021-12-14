module.exports = {
  testRegex: 'tests/Jest/.*.spec.js$',
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/resources/js/$1'
  },
  moduleFileExtensions: ['js', 'json', 'vue'],
  transform: {
    '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
    '.*\\.(vue)$': '<rootDir>/node_modules/vue3-jest'
  },
  collectCoverageFrom: [
    'resources/js/**/*.{js,jsx,ts,tsx,vue}',
    '!resources/js/app.js',
    '!resources/js/bootstrap.js',
    '!resources/js/functions.js',
    '!**/node_modules/**'
  ],
  collectCoverage: true,
  coverageReporters: ['html', 'lcov', 'text-summary'],
  coverageDirectory: '<rootDir>/build/jest/coverage'
}

module.exports = {
  verbose: true,
  testEnvironment: 'node',
  setupFilesAfterEnv: ['<rootDir>/tests/Functional/setup/spec-helper.js'],
  globalSetup: '<rootDir>/tests/Functional/setup/global-preprocess.js',
};

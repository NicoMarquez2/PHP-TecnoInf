module.exports = {
  use: {
    headless: true,
    baseURL: 'http://localhost',
    trace: 'on',
    video: 'on',
    screenshot: 'only-on-failure'
  },
  testDir: 'tests',
  timeout: 60000
};

// Add additional helpers to the global namespace.
// noinspection JSConstantReassignment

const { request } = require('../support/request');
const database = require('../support/database');

global.request = request;
global.database = database;

//add constants
const testServiceName = 'test-suite-service'
global.testServiceName = testServiceName;

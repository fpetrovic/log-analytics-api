beforeAll(() => {
    return database.seedTestCaseLogs();
});

afterAll(() => {
    return database.deleteTestCaseLogs();
});

describe('Log test suite', () => {
    it('should count all logs per service type', async () => {
        const expectedLogCount = 20;
        const response = await request().get(`/api/logs/count?serviceNames[]=${testServiceName}`);

        expect(response.statusCode).toBe(200);
        console.log(response.body.counter);
        expect(response.body.counter).toEqual(expectedLogCount);

    });

    it('should count logs from the start date', async () => {
        const expectedLogCount = 20;
        const startDate = '2021-08-17';
        let response = await request().get(`/api/logs/count?serviceNames[]=${testServiceName}&
        startDate=${startDate}`);

        expect(response.statusCode).toBe(200);
        expect(response.body.counter).toEqual(expectedLogCount);
    });

    it('should count logs to the end date', async () => {
        const expectedLogCount = 14;
        const endDate = '2021-08-18';
        let response = await request().get(`/api/logs/count?serviceNames[]=${testServiceName}&
        endDate=${endDate}`);

        expect(response.statusCode).toBe(200);
        expect(response.body.counter).toEqual(expectedLogCount);
    });

    it('should count logs per response code', async () => {
        const expectedLogCount = 4;
        const responseCode = 400;
        let response = await request().get(`/api/logs/count?serviceNames[]=${testServiceName}&
        statusCode=${responseCode}`);

        expect(response.statusCode).toBe(200);
        expect(response.body.counter).toEqual(expectedLogCount);
    });
});
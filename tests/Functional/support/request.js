const got = require('got');

const makeClient = () => {
    const client = got
        .extend({
            responseType: 'json',
            https: {
                rejectUnauthorized: false,
            },
        });

    return {
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        delete: (path, opts = {}) => client.delete(process.env.APP_BASE_URL + path, opts),
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        get: (path, opts = {}) => client.get(process.env.APP_BASE_URL + path, opts),
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        options: (path, opts = {}) =>
            client(process.env.APP_BASE_URL + path, { method: 'options', ...opts }),
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        patch: (path, opts = {}) => client.patch(process.env.APP_BASE_URL + path, opts),
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        post: (path, opts = {}) => client.post(process.env.APP_BASE_URL + path, opts),
        /**
         * @param {string} path
         * @param {got.OptionsOfJSONResponseBody} opts
         * @return {got.CancelableRequest<Response<string>>}
         */
        put: (path, opts = {}) => client.put(process.env.APP_BASE_URL + path, opts),
    };
};

const request = () =>
    makeClient();

module.exports = {
    makeClient,
    request,
};

const fs = require('fs');
const mysql = require('mysql2/promise');

class Database {
      static async getConnection() {
         return mysql.createConnection({
             host: process.env.DATABASE_HOST,
             port: process.env.DATABASE_PORT,
             user: process.env.DATABASE_USER,
             password: process.env.DATABASE_PASSWORD,
             database: process.env.DATABASE_NAME,
             multipleStatements: true,
             supportBigNumbers: true,
         })
     }

     static async seedTestCaseLogs() {
        const logSeedSourceFile = `${__dirname}/scripts/add-logs.sql`;
        const connection = await this.getConnection();

        const source = fs.readFileSync(logSeedSourceFile, 'utf8');
        await connection.query(source);

        await connection.end();
    }

    static async deleteTestCaseLogs() {
        const connection = await this.getConnection();
        await connection.execute(`DELETE FROM log WHERE service_name = '${testServiceName}'`);
        await connection.end();
     }
}

module.exports = Database;

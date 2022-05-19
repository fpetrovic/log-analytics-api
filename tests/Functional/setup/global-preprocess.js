const dotenv = require('dotenv');

module.exports = async () => {
  // Load .env.local first and fallback to .env if it doesn't exist.
  dotenv.config({ path: '.env.local' });
  dotenv.config({ path: '.env' });
};

Log Analytics API:

Pre-requirements:
- php 8.1.5
- docker (the latest version has been used (20.10.15))

Setup steps:
- `git clone git@github.com:fpetrovic/log-analytics-api.git`
- From the project root: `docker-compose up` or `docker-compose up -d` ( for detached)
- From the project root: `npm ci`

Optional: 
- If you want to add local base url to hosts: `echo '127.0.0.1 local.log-analytics-api.de' | sudo tee -a /etc/hosts`

Automated testing steps:
- `npm run test`

Important!!!:
- Make sure you are using text files without boom characters. File that was sent to me had boom characters in it, so I had
to clean it, firstly. I assume that log files in production will not have boom characters. 

Manual testing steps:
- Add the file you want to test in tests/Support/files/ folder.
- Execute `docker-compose exec app bin/console log:import ${fileRelativePathFromTheProjectRoot}`
- example: `docker-compose exec app bin/console log:import tests/Support/files/one_specific_huge_log_file.txt`
- wait for the command to complete, depending on file size it will take some time. 1.5 GB was imported for around 11 minutes.
- use postman or any other client to manually test `count` GET request method:
- localhost:80/api/logs/count
- localhost:80/api/doc.json

Misc:
- PHP code is written following the latest standards.

Technical comments (please, read after the code review):
- I would move `LogImportCommand::LOG_FILE_PATH` to config
- I added optional parameter to `log:import` command, so different files can be tested easily on dev env. I would remove this option 
in production, so log:import can be imported only from the specific place in production.

use `log-analytics-api`;

INSERT INTO `log` (service_name, created_at, request_method, uri, status_code)
VALUES ('test-suite-service', '2021/08/17:09:21:53','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:21:54','POST','/users','400'),
       ('test-suite-service', '2021/08/17:09:21:55','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:21:56','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:21:57','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:22:58','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:22:59','POST','/users','400'),
       ('test-suite-service', '2021/08/17:09:23:53','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:23:54','POST','/users','400'),
       ('test-suite-service', '2021/08/17:09:23:55','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:26:51','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:26:53','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:29:11','POST','/users','201'),
       ('test-suite-service', '2021/08/17:09:29:13','POST','/invoices','201'),
       ('test-suite-service', '2021/08/18:09:30:54','POST','/invoices','400'),
       ('test-suite-service', '2021/08/18:09:31:55','POST','/invoices','201'),
       ('test-suite-service', '2021/08/18:09:31:56','POST','/invoices','201'),
       ('test-suite-service', '2021/08/18:10:26:53','POST','/invoices','201'),
       ('test-suite-service', '2021/08/18:10:32:56','POST','/invoices','201'),
       ('test-suite-service', '2021/08/18:10:33:59','POST','/invoices','201')
       ;
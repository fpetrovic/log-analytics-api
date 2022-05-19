<?php

namespace App\Decorator;

use Doctrine\DBAL\Connection;

class ConnectionImportDecorator
{
    public const INSERT_INTO_TABLE_COLUMNS_INIT_LINE = 'INSERT INTO `log` (service_name, created_at, request_method, uri, status_code) VALUES ';
    public const SEMICOLON_TO_COMPLETE_QUERY_BATCH = ';';
    public const COMMA_TO_CONTINUE_QUERY_BATCH = ',';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->optimizeConnection();
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    private function optimizeConnection(): void
    {
        $this->connection->getConfiguration()->setSQLLogger(null);
    }
}

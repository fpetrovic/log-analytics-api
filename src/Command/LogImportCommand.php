<?php

namespace App\Command;

use App\Decorator\ConnectionImportDecorator;
use App\Enum\LogRegexPatternMatching;
use App\Exception\InvalidRegexMatchingException;
use DateTime;
use Doctrine\DBAL\Exception;
use Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogImportCommand extends Command
{
    private const BATCH_SIZE = 10000;
    private const LOG_FILE_PATH = 'tests/Support/files/logs-template-file.txt';

    protected static $defaultName = 'log:import';

    private ConnectionImportDecorator $connectionImportDecorator;

    public function __construct(ConnectionImportDecorator $connectionImportDecorator)
    {
        parent::__construct(self::$defaultName);

        $this->connectionImportDecorator = $connectionImportDecorator;
        $this->connectionImportDecorator->getConnection()->getConfiguration()->setSQLLogger();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('logFileOverwrite', InputArgument::OPTIONAL, 'Overwrite log file path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $logFilePath = $input->getArgument('logFileOverwrite') ?? self::LOG_FILE_PATH;
            $logIterator = $this->getLinesSqlFromFileRead($logFilePath);

            $preparedSqlStatement = ConnectionImportDecorator::INSERT_INTO_TABLE_COLUMNS_INIT_LINE;
            foreach ($logIterator as $key => $preparedSqlValuesLine) {
                $lineCount = $key + 1;

                if ($lineCount % self::BATCH_SIZE === 0) {
                    $preparedSqlStatement .= $preparedSqlValuesLine . ConnectionImportDecorator::SEMICOLON_TO_COMPLETE_QUERY_BATCH;
                    $this->connectionImportDecorator->getConnection()->executeQuery($preparedSqlStatement);
                    $preparedSqlStatement = ConnectionImportDecorator::INSERT_INTO_TABLE_COLUMNS_INIT_LINE;
                } else {
                    $preparedSqlStatement .= $preparedSqlValuesLine . ConnectionImportDecorator::COMMA_TO_CONTINUE_QUERY_BATCH;
                }
            }

            if (str_ends_with($preparedSqlStatement, ConnectionImportDecorator::COMMA_TO_CONTINUE_QUERY_BATCH)) {
                $preparedSqlStatement = rtrim($preparedSqlStatement, ConnectionImportDecorator::COMMA_TO_CONTINUE_QUERY_BATCH);
                $preparedSqlStatement .= ConnectionImportDecorator::SEMICOLON_TO_COMPLETE_QUERY_BATCH;

                $this->connectionImportDecorator->getConnection()->executeQuery($preparedSqlStatement);
            }
        } catch (InvalidRegexMatchingException|Exception) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @throws InvalidRegexMatchingException
     */
    protected function getLinesSqlFromFileRead(string $logPath): Generator
    {
        $handle = fopen($logPath, 'r');

        while (!feof($handle)) {
            $line = fgets($handle);

            if (empty($line)) {
                return;
            }

            $createdAtString = LogRegexPatternMatching::createdAt->getMatchingItem($line);
            $createdAtDateTime = date_format(DateTime::createFromFormat('d/M/Y:H:i:s O', $createdAtString), 'Y/m/d:H:i:s');

            $serviceName = strtolower(LogRegexPatternMatching::serviceName->getMatchingItem($line));

            $requestMethod = LogRegexPatternMatching::requestMethod->getMatchingItem($line);
            $uri = LogRegexPatternMatching::uri->getMatchingItem($line);
            $responseCode = LogRegexPatternMatching::responseCode->getMatchingItem($line);

            yield "('{$serviceName}', '{$createdAtDateTime}','{$requestMethod}','{$uri}','{$responseCode}')";
        }

        fclose($handle);
    }
}

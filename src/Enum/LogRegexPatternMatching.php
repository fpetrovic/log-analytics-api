<?php

namespace App\Enum;

use App\Exception\InvalidRegexMatchingException;

enum LogRegexPatternMatching : string
{
    case serviceName = '/(.*-SERVICE)/';

    case createdAt = '/(?:\[)(\d+\/[a-zA-Z]+\/\d+:\d+:\d+:\d+\s\+\d...)(?:\])/';

    case requestMethod = '/(?:")([A-Z]+)\s(?:\/\w+)/';

    case uri = '/(?:")(?:[A-Z]+)\s(\/\w+)/';

    case responseCode = '/(?:HTTP\/\d\.?\d"\s)(\d..)/';
    private const MATCHED_ITEM_INDEX = 1;

    /**
     * @throws InvalidRegexMatchingException
     */
    public function getMatchingItem(string $line): string
    {
        preg_match($this->value, $line, $responseCodeMatch);

        return !empty($responseCodeMatch) ? $responseCodeMatch[self::MATCHED_ITEM_INDEX] : throw new InvalidRegexMatchingException('Unmatched log item on line: ' . $line);
    }
}

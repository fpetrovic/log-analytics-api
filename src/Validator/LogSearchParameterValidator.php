<?php

namespace App\Validator;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LogSearchParameterValidator
{
    /**
     * @param array<string,mixed> $requestPayload
     * @param array<int,string>   $supportedLogSearchingParameters
     *
     * @throws BadRequestHttpException
     */
    public function validate(array $requestPayload, array $supportedLogSearchingParameters): void
    {
        if (!empty($requestPayload)) {
            foreach ($requestPayload as $payloadItemName => $payloadItemValue) {
                if (!\in_array($payloadItemName, $supportedLogSearchingParameters, true)) {
                    throw new BadRequestHttpException('bad input parameter');
                }
            }
        }
    }
}

<?php

namespace App\Ship\Exceptions\Formatters;

use Apiato\Core\Exceptions\Formatters\ExceptionsFormatter as CoreExceptionsFormatter;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class ValidationExceptionFormatter
 *
 * @author Johannes Schobel <johannes.schobel@googlemail.com>
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class ValidationExceptionFormatter extends CoreExceptionsFormatter
{

    /**
     * Status Code.
     *
     * @var  integer
     */
    CONST STATUS_CODE = 422;

    /**
     * @param \Exception                    $exception
     * @param \Illuminate\Http\JsonResponse $response
     *
     * @return  array
     */
    public function responseData(Exception $exception, JsonResponse $response)
    {
        return [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'errors'      => $exception->errors(),
            'status_code' => self::STATUS_CODE,
        ];
    }

    /**
     * @param \Exception                    $exception
     * @param \Illuminate\Http\JsonResponse $response
     *
     * @return  mixed
     */
    function modifyResponse(Exception $exception, JsonResponse $response)
    {
        return $response;
    }

    /**
     * @return  int
     */
    public function statusCode()
    {
        return self::STATUS_CODE;
    }
}

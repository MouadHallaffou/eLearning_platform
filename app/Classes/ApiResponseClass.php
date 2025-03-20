<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ApiResponseClass
{
    /**
     * Envoyer une réponse JSON réussie.
     *
     * @param mixed $result Les données à retourner.
     * @param string $message Un message optionnel.
     * @param int $code Le code HTTP (200 par défaut).
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponse($result, $message = '', $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $result,
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * Envoyer une réponse JSON d'erreur.
     *
     * @param string $error Le message d'erreur.
     * @param array $errorMessages Les messages d'erreur détaillés (optionnel).
     * @param int $code Le code HTTP (400 par défaut).
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Rollback une transaction et lever une exception.
     *
     * @param \Exception $e L'exception capturée.
     * @param string $message Le message d'erreur.
     * @throws HttpResponseException
     */
    public static function rollback($e, $message = "Something went wrong! Process not completed")
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    /**
     * Logger une exception et lever une HttpResponseException.
     *
     * @param \Exception $e L'exception capturée.
     * @param string $message Le message d'erreur.
     * @throws HttpResponseException
     */
    public static function throw($e, $message = "Something went wrong! Process not completed")
    {
        Log::error($e);
        throw new HttpResponseException(response()->json(['message' => $message], 500));
    }
}
<?php

namespace App\Application\Actions;
trait ActionResponseTrait
{
    public function buildResponse(bool $success, array $data = [], ?string $message = null): array
    {
        $response = [
            'success' => $success
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return $response;
    }

    public function successResponse(array $data = [], ?string $message = null): array
    {
        return $this->buildResponse(true, $data, $message);
    }

    public function errorResponse(array $data = [], ?string $message = null): array
    {
        return $this->buildResponse(false, $data, $message);
    }
}

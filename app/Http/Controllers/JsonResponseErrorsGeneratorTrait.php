<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

trait JsonResponseErrorsGeneratorTrait
{
    protected function generateJsonResponseErrors(array $errors, int $code): JsonResponse
    {
        return new JsonResponse(['errors' => $errors], $code);
    }
}
<?php declare(strict_types = 1);

namespace App\Http\Requests;

class FeedRemoveRequest extends Request
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|int',
        ];
    }
}
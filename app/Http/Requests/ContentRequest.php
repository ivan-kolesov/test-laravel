<?php declare(strict_types = 1);

namespace App\Http\Requests;

class ContentRequest extends Request
{
    public const PER_PAGE = 10;

    public function rules(): array
    {
        return [
            'feed_id' => 'bail|required|int',
            'from_date' => 'nullable|date',
            'read' => 'nullable|boolean',
        ];
    }
}
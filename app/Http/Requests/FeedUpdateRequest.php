<?php declare(strict_types = 1);

namespace App\Http\Requests;

class FeedUpdateRequest extends Request
{
    use FeedModifyTrait;

    public function rules(): array
    {
        return [
            'id' => 'bail|required|int',
            'url' => 'bail|required|url|max:255',
        ];
    }
}
<?php declare(strict_types = 1);

namespace App\Http\Requests;

class FeedRequest extends Request
{
    use FeedModifyTrait;

    public function rules(): array
    {
        return [
            'url' => 'bail|required|url|max:255',
        ];
    }
}
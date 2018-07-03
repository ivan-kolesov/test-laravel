<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Repositories\FeedContentRepository;
use Illuminate\Http\JsonResponse;

class FeedContentController extends Controller
{
    protected $feedContentRepository;

    public function __construct(FeedContentRepository $feedContentRepository)
    {
        $this->feedContentRepository = $feedContentRepository;
    }

    public function getContent(ContentRequest $contentRequest): JsonResponse
    {
        return new JsonResponse($this->feedContentRepository->find($contentRequest));
    }

    public function markAsRead($id): JsonResponse
    {
        $this->feedContentRepository->markRead((int)$id, true);

        return new JsonResponse(200);
    }

    public function markAsUnread($id): JsonResponse
    {
        $this->feedContentRepository->markRead((int)$id, false);

        return new JsonResponse(200);
    }
}
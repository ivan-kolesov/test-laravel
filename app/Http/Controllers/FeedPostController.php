<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Repositories\FeedPostRepository;
use Illuminate\Http\JsonResponse;

class FeedPostController extends Controller
{
    protected $feedPostRepository;

    public function __construct(FeedPostRepository $feedPostRepository)
    {
        $this->feedPostRepository = $feedPostRepository;
    }

    public function getPosts(PostRequest $postRequest): JsonResponse
    {
        $posts = $this->feedPostRepository->getSimplePaginated($postRequest);
        return new JsonResponse(['posts' => $posts->items(), 'hasMore' => $posts->hasMorePages()]);
    }

    public function markAsRead(): JsonResponse
    {
        $id = (int)request('id');
        $this->feedPostRepository->markRead($id, true);

        return new JsonResponse();
    }

    public function markAsUnread(): JsonResponse
    {
        $id = (int)request('id');
        $this->feedPostRepository->markRead($id, false);

        return new JsonResponse();
    }
}
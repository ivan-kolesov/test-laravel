<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\FeedRemoveRequest;
use App\Http\Requests\FeedRequest;
use App\Http\Requests\FeedUpdateRequest;
use App\Repositories\FeedRepository;
use Illuminate\Http\JsonResponse;

class FeedController extends Controller
{
    private $feedRepository;

    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    public function index(string $feedId = null)
    {
        $feeds = $this->feedRepository->getAll();

        return view('index')
            ->with('feeds', $feeds)
            ->with('selectedFeed', $feedId);
    }

    public function add(FeedRequest $feedRequest): JsonResponse
    {
        $feedRequest->populateNameByUrl();

        if ($this->feedRepository->store($feedRequest->all())) {
            return new JsonResponse(['redirect' => route('home')]);
        }

        return new JsonResponse(null, 500);
    }

    public function update(FeedUpdateRequest $feedRequest): JsonResponse
    {
        $feed = $this->feedRepository->getById((int)$feedRequest->id);

        if ($feed === null) {
            return new JsonResponse(null, 404);
        }

        $this->feedRepository->update($feed, $feedRequest->all());

        return new JsonResponse();
    }

    public function remove(FeedRemoveRequest $feedRequest): JsonResponse
    {
        if ($this->feedRepository->remove((int)$feedRequest->id)) {
            return new JsonResponse(['redirect' => route('home')]);
        }

        return new JsonResponse(null, 500);
    }
}
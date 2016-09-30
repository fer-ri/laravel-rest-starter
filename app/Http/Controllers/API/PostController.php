<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PostRequest;
use App\Repositories\PostRepository;
use App\Transformers\PostTransformer;

class PostController extends APIController
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        $this->authorize('index', $this->postRepository->model());

        $items = $this->postRepository
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($items, new PostTransformer);
    }

    public function store(PostRequest $request)
    {
        $this->authorize('store', $this->postRepository->model());

        $attributes = $request->only(['title', 'content', 'status']);

        $attributes['user_id'] = $this->user->id;

        $this->postRepository->store($attributes);

        return $this->response->created();
    }

    public function show($uuid)
    {
        $item = $this->postRepository->findByUuid($uuid);

        $this->authorize('show', $item);

        return $this->response->item($item, new PostTransformer);
    }

    public function update(PostRequest $request, $uuid)
    {
        $item = $this->postRepository->findByUuid($uuid);

        $this->authorize('update', $item);

        $attributes = $request->only(['title', 'content', 'status']);

        $item->update($attributes);

        return $this->response->noContent();
    }

    public function destroy($uuid)
    {
        $item = $this->postRepository->findByUuid($uuid);

        $this->authorize('destroy', $item);

        $item->delete();

        return $this->response->noContent();
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
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
        $questions = $this->postRepository
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($questions, new PostTransformer);
    }

    public function store(PostRequest $request)
    {
        $attributes = $request->only(['title', 'content', 'status']);

        $attributes['user_id'] = $this->user->id;

        $this->postRepository->store($attributes);

        return $this->response->created();
    }

    public function show($uuid)
    {
        $question = $this->postRepository->findByUuid($uuid);

        return $this->response->item($question, new PostTransformer);
    }

    public function update(PostRequest $request, $uuid)
    {
        $attributes = $request->only(['title', 'content', 'status']);

        $this->postRepository->updateByUuid($uuid, $attributes);

        return $this->response->noContent();
    }

    public function destroy($uuid)
    {
        $this->postRepository->destroyByUuid($uuid);
        
        return $this->response->noContent();
    }
}

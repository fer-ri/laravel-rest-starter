<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;

class UserController extends APIController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', $this->userRepository->model());

        $items = $this->userRepository
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($items, new UserTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('store', $this->userRepository->model());

        $attributes = $request->only([]);

        $this->userRepository->store($attributes);

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param  string                    $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        $this->authorize('show', $user);

        return $this->response->item($user, new UserTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $request
     * @param  string                         $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        $this->authorize('update', $user);

        $attributes = $request->only([]);

        $this->userRepository->updateByUuid($uuid, $attributes);

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string                    $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        $this->authorize('destroy', $user);

        $this->userRepository->destroyByUuid($uuid);

        return $this->response->noContent();
    }

    /**
     * Return current logged in user.
     *
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    public function me()
    {
        return $this->user;
    }
}

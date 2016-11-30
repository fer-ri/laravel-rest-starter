<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PermissionRequest;
use App\Repositories\PermissionRepository;
use App\Transformers\PermissionTransformer;

class PermissionController extends APIController
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->permissionRepository
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($items, new PermissionTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $attributes = $request->only([]);

        $this->permissionRepository->store($attributes);

        return $this->response->created();
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $item = $this->permissionRepository->findByUuid($uuid);

        return $this->response->item($item, new PermissionTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PermissionRequest  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, $uuid)
    {
        $attributes = $request->only([]);

        $this->permissionRepository->updateByUuid($uuid, $attributes);

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $this->permissionRepository->destroyByUuid($uuid);
        
        return $this->response->noContent();
    }
}

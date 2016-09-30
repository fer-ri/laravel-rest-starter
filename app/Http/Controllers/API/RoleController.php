<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RoleRequest;
use App\Repositories\RoleRepository;
use App\Transformers\RoleTransformer;

class RoleController extends APIController
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', $this->roleRepository->model());

        $items = $this->roleRepository
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->response->paginator($items, new RoleTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('store', $this->roleRepository->model());

        $attributes = [
            'name' => $request->get('name'),
            'display_name' => $request->get('displayName'),
            'description' => $request->get('description'),
            'permissions' => $request->get('permissions', []),
            'level' => $request->get('level', 1),
        ];

        $this->roleRepository->store($attributes);

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
        $item = $this->roleRepository->findByUuid($uuid);

        $this->authorize('show', $item);

        return $this->response->item($item, new RoleTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\RoleRequest $request
     * @param  string                         $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $uuid)
    {
        $item = $this->roleRepository->findByUuid($uuid);

        $this->authorize('update', $item);

        $attributes = [
            'name' => $request->get('name'),
            'display_name' => $request->get('displayName'),
            'description' => $request->get('description'),
            'permissions' => $request->get('permissions', []),
            'level' => $request->get('level', 1),
        ];

        $item->update($attributes);

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
        $role = $this->roleRepository->findByUuid($uuid);

        $this->authorize('destroy', $role);

        $role->delete();

        return $this->response->noContent();
    }
}

<?php

namespace App\Http\ApiControllers;

use App\Actions\UsersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct(
        public UsersService $usersService
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function index(Request $request): Response
    {
        return response($this->usersService->showAll($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request): Response
    {
        return response($this->usersService->save($request));
    }

    /**
     * Display the specified resource.
     *
     * @param string|int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function show(string|int $id, Request $request): Response
    {
        return response($this->usersService->showOne($id, $request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function update(int $id, Request $request): Response
    {
        return response($this->usersService->update($id, $request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string|int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy(string|int $id, Request $request): Response
    {
        return response($this->usersService->delete($id, $request));
    }
}

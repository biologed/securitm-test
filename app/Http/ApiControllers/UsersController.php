<?php

namespace App\Http\ApiControllers;

use App\Actions\UsersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

use OpenApi\Attributes as OAT;

#[OAT\Info(version: '1.0', description: 'Description of users api endpoints', title: 'Users API')]
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
    #[OAT\Get(path: '/api/user')]
    #[OAT\Response(response: '200', description: 'All users', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'page', description: 'The page number', required: false, schema: new OAT\Schema(type: 'integer'))]
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
    #[OAT\Post(path: '/api/user')]
    #[OAT\Response(response: '200', description: 'Create user', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'name', description: 'The user name', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'ip', description: 'The user ip', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'comment', description: 'The comment for user description', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'email', description: 'The user email', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'password', description: 'The user password', required: false, schema: new OAT\Schema(type: 'string'))]
    public function store(Request $request): Response
    {
        return response($this->usersService->save($request));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    #[OAT\Get(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Show user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    public function show(int $id, Request $request): Response
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
    #[OAT\Put(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Update user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    #[OAT\Parameter(name: 'name', description: 'The user name', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'ip', description: 'The user ip', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'comment', description: 'The comment for user description', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'email', description: 'The user email', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'password', description: 'The user password', required: false, schema: new OAT\Schema(type: 'string'))]
    public function update(int $id, Request $request): Response
    {
        return response($this->usersService->update($id, $request));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    #[OAT\Delete(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Delete user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    public function destroy(int $id, Request $request): Response
    {
        return response($this->usersService->delete($id, $request));
    }
}

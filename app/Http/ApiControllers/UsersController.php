<?php

namespace App\Http\ApiControllers;

use Illuminate\Http\Response;
use App\Http\Requests\Api\Users\DeleteRequest;
use App\Http\Requests\Api\Users\GetUserRequest;
use App\Http\Requests\Api\Users\GetUsersRequest;
use App\Http\Requests\Api\Users\SaveRequest;
use App\Http\Requests\Api\Users\UpdateRequest;
use App\Http\ApiControllers\Repositories\UsersRepository;

use OpenApi\Attributes as OAT;

#[OAT\Info(version: '1.0', description: 'Description of users api endpoints', title: 'Users API')]
class UsersController extends Controller
{
    public function __construct(
        public UsersRepository $usersService
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param GetUsersRequest $request
     *
     * @return Response
     */
    #[OAT\Get(path: '/api/user')]
    #[OAT\Response(response: '200', description: 'All users', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'page', description: 'The page number', required: false, schema: new OAT\Schema(type: 'integer'))]
    public function index(GetUsersRequest $request): Response
    {
        $validated = $request->validated();
        $response = $this->usersService->all($validated);
        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveRequest $request
     *
     * @return Response
     */
    #[OAT\Post(path: '/api/user')]
    #[OAT\Response(response: '200', description: 'Create user', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'name', description: 'The user name', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'ip', description: 'The user ip', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'comment', description: 'The comment for user description', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'email', description: 'The user email', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'password', description: 'The user password', required: false, schema: new OAT\Schema(type: 'string'))]
    public function store(SaveRequest $request): Response
    {
        $validated = $request->validated();
        $response = $this->usersService->save($validated);
        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param GetUserRequest $request
     *
     * @return Response
     */
    #[OAT\Get(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Show user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    public function show(GetUserRequest $request): Response
    {
        $validated = $request->validated();
        $response = $this->usersService->getById($validated);
        return response($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     *
     * @return Response
     */
    #[OAT\Put(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Update user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    #[OAT\Parameter(name: 'name', description: 'The user name', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'ip', description: 'The user ip', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'comment', description: 'The comment for user description', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'email', description: 'The user email', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'password', description: 'The user password', required: false, schema: new OAT\Schema(type: 'string'))]
    public function update(UpdateRequest $request): Response
    {
        $validated = $request->validated();
        $this->usersService->update($validated);
        return response([]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRequest $request
     *
     * @return Response
     */
    #[OAT\Delete(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Delete user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    public function destroy(DeleteRequest $request): Response
    {
        $validated = $request->validated();
        $this->usersService->delete($validated);
        return response([]);
    }
}

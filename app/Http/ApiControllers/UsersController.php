<?php

namespace App\Http\ApiControllers;

use App\Http\Requests\Api\Users\DeleteRequest;
use App\Http\Requests\Api\Users\GetRequest;
use App\Http\Requests\Api\Users\GetAllRequest;
use App\Http\Requests\Api\Users\PostRequest;
use App\Http\Requests\Api\Users\PutRequest;
use App\Services\UsersService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OAT;

#[OAT\Info(version: '1.0', description: 'Description of users api endpoints', title: 'Users API')]
class UsersController extends Controller
{
    public function __construct(
        private readonly UsersService $usersService
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param GetAllRequest $request
     *
     * @return Response
     */
    #[OAT\Get(path: '/api/user')]
    #[OAT\Response(response: '200', description: 'All users', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'page', description: 'The page number', required: false, schema: new OAT\Schema(type: 'integer'))]
    #[OAT\Parameter(name: 'per_page', description: 'Number of users per page', required: false, schema: new OAT\Schema(type: 'integer'))]
    #[OAT\Parameter(name: 'order', description: 'Order users by tag', required: false, schema: new OAT\Schema(type: 'string'))]
    #[OAT\Parameter(name: 'sort', description: 'Sort users by order', required: false, schema: new OAT\Schema(type: 'string'))]
    public function index(GetAllRequest $request): Response
    {
        //get only validated data from FormRequest
        $data = $request->validated();
        $response = $this->usersService->getAll($data);
        return response($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
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
    public function store(PostRequest $request): Response
    {
        //get only validated data from FormRequest
        $data = $request->validated();
        $response = $this->usersService->save($data);
        return response($response);
    }

    /**
     * Display the specified resource.
     *
     * @param GetRequest $request
     *
     * @return Response
     */
    #[OAT\Get(path: '/api/user/{id}')]
    #[OAT\Response(response: '200', description: 'Show user by id', content: new OAT\JsonContent(ref: '#/components/schemas/users'))]
    #[OAT\Parameter(name: 'id', description: 'The user id', required: true, schema: new OAT\Schema(type: 'integer'))]
    public function show(GetRequest $request): Response
    {
        //get only validated data from FormRequest
        $data = $request->validated();
        $response = $this->usersService->getById($data['id']);
        return response($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
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
    public function update($id, Request $request): Response
    {
        //get only validated data from FormRequest
        $request->merge(['id' => $id]);
        $data = $request->all();
        try {
            $result['data'] = $this->usersService->update($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($result, $result['status'] ?? 200);
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
    public function destroy($id, Request $request): Response
    {
        $request->merge(['id' => $id]);
        //get only validated data from FormRequest
        $data = $request->all();
        try {
            $result['data'] = $this->usersService->delete($data['id']);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($result, $result['status'] ?? 200);
    }
}

<?php

namespace App\Http\ApiControllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    private const int ITEM_PER_PAGE = 5;

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
        $request->merge(['order', strtolower($request->get('order'))]);
        $validator = Validator::make($request->all(), [
            'page' => ['int'],
            'name' => ['string', 'present_with:order', 'max:255'],
            'order' => ['string', 'lowercase', 'in:desc,asc'],
        ]);
        if ($validator->passes()) {
            $validated = $validator->validated();
            $usersList = User::select(['id','name']);
            if (isset($validated['name'])) {
                $usersList = $usersList->where('name', 'LIKE', '%' . $validated["name"] . '%');
            }
            if (isset($validated['order'])) {
                $usersList = $usersList->orderBy('name', $validated['order']);
            }
            return response(
                $usersList->simplePaginate(self::ITEM_PER_PAGE, ['*'], 'page', $validated['page'] ?? 1)
            );
        }
        return response($validator->messages());
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'ip' => ['required', 'ip', 'max:255'],
            'comment' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::default()],
        ]);
        //Создаем пользователя только из валидных полей
        if ($validator->passes() && $validated = $validator->validated()) {
            $user = User::create([
                'name' => $validated['name'],
                'ip' => $validated['ip'],
                'comment' => $validated['comment'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
            return response($user);
        }
        return response($validator->messages());
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
        $request->merge(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int', "exists:users,id,id,$request->id"],
        ]);
        if ($validator->passes() && $validated = $validator->validated()) {
            return response(User::find($validated['id']));
        }
        return response($validator->messages());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string|int $id
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function update(string|int $id, Request $request): Response
    {
        $request->merge(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int', "exists:users,id,id,$request->id"],
            'name' => ['string', 'max:255'],
            'ip' => ['string', 'max:255'],
            'comment' => ['string', 'max:255'],
            'password' => ['string', Password::default(), 'confirmed'],
        ]);
        //Обновляем пользователя только из валидных полей
        if ($validator->passes() && $validated = $validator->validated()) {
            $user = User::find($validated['id']);
            $user->name = $validated['name'] ?? $user->name;
            $user->ip = $validated['ip'] ?? $user->ip;
            $user->comment = $validated['comment'] ?? $user->comment;
            $user->password = $validated['password'] ?? $user->password;
            $user->save();
            return response($user);
        }
        return response($validator->messages());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return Response
     */
    public function destroy(User $user): Response
    {
        $user->delete();
        return response();
    }
}

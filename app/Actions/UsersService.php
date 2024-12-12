<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UsersService
{
    /**
     * @link ApiUsersControllerTest::test_api_check_show_users_per_page_value_equals_five() fix the test if you change this value
     */
    private const int ITEM_PER_PAGE = 5;
    /**
     * @throws ValidationException
     */
    public function showAll(Request $request): MessageBag|Paginator
    {
        $request->merge(['order', strtolower($request->get('order'))]);
        $validator = Validator::make($request->all(), [
            'page' => ['int'],
            'name' => ['string', 'present_with:order', 'max:255'],
            'order' => ['string', 'lowercase', 'in:desc,asc'],
        ]);
        if ($validator->passes()) {
            $validated = $validator->validated();
            $usersList = User::orderBy('name', $validated['order'] ?? 'desc');
            if (isset($validated['name'])) {
                $usersList = $usersList->where('name', 'LIKE', '%' . $validated["name"] . '%');
            }
            return $usersList->simplePaginate(self::ITEM_PER_PAGE, ['*'], 'page', $validated['page'] ?? 1);
        }
        return $validator->messages();
    }

    /**
     * @throws ValidationException
     */
    public function showOne(string|int $id, Request $request): MessageBag|User
    {
        $request->merge(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int', "exists:users,id,id,$request->id"],
        ]);
        if ($validator->passes()) {
            $validated = $validator->validated();
            return User::find($validated['id']);
        }
        return $validator->messages();
    }

    /**
     * @throws ValidationException
     */
    public function save(Request $request, string|int $id = null): MessageBag|User
    {
        if ($id > 0) {
            //Для корректного обновления
            $request->merge(['id' => $id, 'save_method' => 'update']);
        }
        $validator = Validator::make($request->all(), [
            'id' => ['required_if:save_method,update', 'int', "exists:users,id,id,$request->id"],
            'name' => ['required_if:save_method,null', 'string', 'max:255'],
            'ip' => ['required_if:save_method,null', 'ip', 'max:255'],
            'comment' => ['required_if:save_method,null', 'string', 'max:255'],
            'email' => ['required_if:save_method,null', 'email', 'max:255', 'unique:users'],
            'password' => ['required_if:save_method,null', 'string', Password::default()],
        ]);

        //Создаем пользователя только из валидных полей
        if ($validator->passes()) {
            $validated = $validator->validated();
            if ($request->save_method === 'update') {
                $user = User::find($validated['id']);
                $user->name = $validated['name'] ?? $user->name;
                $user->ip = $validated['ip'] ?? $user->ip;
                $user->comment = $validated['comment'] ?? $user->comment;
                $user->password = $validated['password'] ?? $user->password;
                $user->save();
                return $user;
            }
            return User::create([
                'name' => $validated['name'],
                'ip' => $validated['ip'],
                'comment' => $validated['comment'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
        }

        return $validator->messages();
    }

    /**
     * @throws ValidationException
     */
    public function delete(string|int $id, Request $request): MessageBag
    {
        $request->merge(['id' => $id]);
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int', "exists:users,id,id,$request->id"],
        ]);
        if ($validator->passes()) {
            $user = User::find($id);
            $user->delete();
        }

        return $validator->messages();
    }
}

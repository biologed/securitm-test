<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class GetAllRequest extends FormRequest
{
    /**
     * @link ApiUsersControllerTest::test_api_check_show_users_per_page_value_equals_five() fix the test if you change this value
     */
    private const int DEFAULT_ITEMS_PER_PAGE = 10;
    private const int DEFAULT_PAGE = 1;
    private const string DEFAULT_ORDER = 'id';
    private const string DEFAULT_SORT = 'desc';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'per_page' => ['int'],
            'page' => ['int'],
            'order' => ['string', 'max:255'],
            'sort' => ['string', 'in:desc,asc'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->per_page ?? static::DEFAULT_ITEMS_PER_PAGE,
            'page' => $this->page ?? static::DEFAULT_PAGE,
            'order' => strtolower($this->order ?? static::DEFAULT_ORDER),
            'sort' => strtolower($this->sort ?? static::DEFAULT_SORT),
        ]);
    }
}

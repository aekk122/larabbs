<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class TopicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:3',
            'body' => 'required|string|min:5',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function attributes() {
        return [
            'title' => '标题',
            'body' => '话题内容',
            'category_id' => '分类',
        ];
    }
}

<?php

namespace App\Http\Requests\Blog;

use App\Core\Requests\AuditableRequest;
use App\Helper\Common;
use Illuminate\Foundation\Http\FormRequest;

class BlogUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'id' => ['required', 'integer'],
            'category' => ['required'],
            'author' => ['required', 'string'],
            'published_date' => ['required', 'string'],
            'status' => ['required', 'string'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string']
        ];

        return Common::setRuleAuthor($rules, new AuditableRequest());
    }

    public function prepareForValidation()
    {
        Common::setRequestAuthor($this, new AuditableRequest());
    }
}
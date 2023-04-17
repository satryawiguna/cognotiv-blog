<?php

namespace App\Http\Requests\Comment;

use App\Core\Requests\AuditableRequest;
use App\Helper\Common;
use Illuminate\Foundation\Http\FormRequest;

class CommentUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'id' => ['required'],
            'body' => ['required', 'string']
        ];

        return Common::setRuleAuthor($rules, new AuditableRequest());
    }

    public function prepareForValidation()
    {
        Common::setRequestAuthor($this, new AuditableRequest());
    }
}

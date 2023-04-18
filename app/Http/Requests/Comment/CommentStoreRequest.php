<?php

namespace App\Http\Requests\Comment;

use App\Core\Requests\AuditableRequest;
use App\Helper\Common;
use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'commentable_type' => ['string'],
            'commentable_id' => ['required', 'integer'],
            'author' => ['required', 'string'],
            'body' => ['required', 'string'],
            'comment_date' => ['sometimes', 'required', 'date']
        ];

        return Common::setRuleAuthor($rules, new AuditableRequest());
    }

    public function prepareForValidation()
    {
        Common::setRequestAuthor($this, new AuditableRequest());
    }
}

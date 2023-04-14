<?php

namespace App\Helper;

use App\Core\Request\AuditableRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Common
{
    public static function setRuleAuthor(array $rules, AuditableRequest $auditableRequest)
    {
        return array_merge($rules, $auditableRequest->rules());
    }

    public static function setRequestAuthor(FormRequest $request, AuditableRequest $auditableRequest): void
    {
        $request->merge(['request_by' => (Auth::user()) ? Auth::user()->username : $auditableRequest->request_by]);
    }
}

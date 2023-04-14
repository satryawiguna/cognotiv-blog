<?php

namespace App\Core\Request;

class AuditableRequest
{
    public string $request_by = "system";

    public function rules()
    {
        return [
            'request_by' => ['string']
        ];
    }
}

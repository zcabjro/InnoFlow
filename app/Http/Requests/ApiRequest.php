<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


abstract class ApiRequest extends FormRequest
{
    /**
     * Force response to be a JSON response
     *
     * @return bool
     */
    public function wantsJson()
    {
        return true;
    }
}

<?php

namespace App\Http\Requests\Services;

use App\Http\Requests\Request;
use Sentinel;

/**
 * Class PostServiceRequest
 * @package App\Http\Requests
 */
class PostServiceRequest extends Request
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
        ];
    }
}
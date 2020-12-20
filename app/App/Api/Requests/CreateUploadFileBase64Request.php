<?php

namespace App\Api\Requests;

use Domain\File\Models\File;
use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateUploadFileBase64Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(UserRepository $userRepository)
    {
        if ($this->has('user_id')) {
            $user = $userRepository->find($this->get('user_id'));
            return Auth::user()->can('ownResource', $user);
        }

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
            'file' => 'required',
            'type' => [
                'required',
                Rule::in([
                    File::TYPE_DOC,
                    File::TYPE_PROFILE_PICTURE,
                ])
            ],
            'name' => 'sometimes',
            'user_id' => 'sometimes|exists:users,id',
            'metadata' => 'sometimes|array'
        ];
    }
}

<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'            =>  (int)$user->id,
            'name'          =>  (string)$user->name,
            'email'         =>  $user->email,
            'isVerified'    =>  (int)$user->verified,
            'isAdmin'       =>  ($user->admin === 'true'),
            'creationDate'  =>  (string)$user->created_at,
            'lastChange'    =>  (string)$user->updated_at,
            'deleteDate'    =>  isset($user->deleted_at) ? (string)$user->deleted_at : null,

            'links'         => [
                'rel'   =>  'self',
                'href'  =>  route('users.show', $user->id)
            ],
        ];
    }

    /**
     * Preventing sort function from accessing the original names from database
     * @param $index
     * @return mixed|null
     */
    public static function originalAttribute($index)
    {
        $attribute = [
            'id'            =>  'id',
            'name'          =>  'name',
            'email'         =>  'email',
            'isVerified'    =>  'verified',
            'isAdmin'       =>  'admin',
            'creationDate'  =>  'created_at',
            'lastChange'    =>  'updated_at',
            'deleteDate'    =>  'deleted_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }


    public static function transformedAttribute($index)
    {
        $attribute = [
            'id'            =>'id',
            'name'          =>'name',
            'email'         =>'email',
            'verified'      =>'isVerified',
            'admin'         =>'isAdmin',
            'created_at'    =>'creationDate',
            'updated_at'    =>'lastChange',
            'deleted_at'    =>'deleteDate'
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}

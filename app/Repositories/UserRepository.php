<?php

namespace App\Repositories;

use App\Core\Entities\BaseEntity;
use App\Http\Requests\User\RegisterRequest;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\Contracts\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public User $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }

    public function register(RegisterRequest $request): BaseEntity
    {
        $user = new $this->user([
            "role_id" => 2,
            "username" => $request->username,
            "email" => $request->email,
        ]);

        $this->setAuditableInformationFromRequest($user, $request);

        $user->setAttribute('password', bcrypt($request->password));

        $user->save();

        $contact = new Contact([
            "full_name" => $request->full_name,
            "nick_name" => $request->nick_name,
        ]);

        $this->setAuditableInformationFromRequest($contact, $request);

        $user->contact()->save($contact);

        return $user->fresh();
    }

}

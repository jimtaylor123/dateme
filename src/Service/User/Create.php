<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use DateTime;

final class Create extends Base
{
    public function create(array $input): object
    {
        $data = $this->validateUserData($input);
        /** @var User $user */
        $user = $this->userRepository->create($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache((int) $user->getId(), $user->toJson());
        }

        $jsonUser = $user->toJson();

        // NB according to the specs we need to return the password to the user, so we return the unhashed password - this is intentional, not a mistake
        $jsonUser->password = $input['password'];

        return $jsonUser;
    }

    private function validateUserData(array $input): User
    {
        $user = json_decode((string) json_encode($input), false);

        $required = \App\Entity\User::REQUIRED_FIELDS;

        foreach($required as $requirement){
            if (! isset($user->$requirement)) {
                throw new \App\Exception\User("The field \"$requirement\" is required.", 400);
            }
        }

        $myuser = new User();
        $myuser->updateName(self::validateUserName($user->name));
        $myuser->updateEmail(self::validateEmail($user->email));
        $myuser->updatePassword(hash('sha512', $user->password));
        $this->userRepository->checkUserByEmail($user->email);
        $myuser->updateGender(self::validateGender($user->gender));
        $myuser->updateDateOfBirth(self::validateDateOfBirth($user->dateOfBirth));

        return $myuser;
    }
}

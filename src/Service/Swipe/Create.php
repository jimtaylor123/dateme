<?php

declare(strict_types=1);

namespace App\Service\Swipe;

use DateTime;
use App\Entity\Swipe;
use stdClass;

final class Create extends Base
{
    public function create(array $input): object
    {
        $data = $this->validateSwipeData($input);
        $swipe = $this->swipeRepository->createSwipe($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($swipe->getId(), $swipe->toJson());
        }

        $result = (new stdClass());

        if( $swipe->getPreference() === 'yes'){
            $result->match = $this->swipeRepository->checkForAMatch($swipe);
        } else {
            $result->match = false;
        }

        return $result;
    }

    private function validateSwipeData(array $input): Swipe
    {
        $data = json_decode((string) json_encode($input), false);

        $required = \App\Entity\Swipe::REQUIRED_FIELDS;

        foreach($required as $requirement){
            if (! isset($data->$requirement)) {
                throw new \App\Exception\Swipe("The field \"$requirement\" is required.", 400);
            }
        }

        try {
            $profileUser = $this->userRepository->getUser($data->profileId);
        } catch (\Throwable $th) {
            throw new \App\Exception\Swipe('The user you are trying to swipe does not exist.', 400);
        }

        $newSwipe = new Swipe();
        $newSwipe->updateUserId(self::validateUserId($data));
        $newSwipe->updateProfileId($profileUser->getId());
        $newSwipe->updatePreference(self::validatePreference($data->preference));
        $newSwipe->updateCreatedAt(new DateTime('today'));

        return $newSwipe;
    }
}

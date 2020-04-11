<?php

namespace App\Controller\Action;

use App\Controller\AppController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ResetPasswordAction
 * @package App\Controller\Action
 *
 * In API platform for custom operations,
 * we should create Action classes instead controller classes.
 */
class ResetPasswordAction extends AppController
{
    /**
     * @param User $data
     *
     * Validator is only called after we return the data from this action!
     * Only hear is checks for user current password, but we've just modified it!
     * @return JsonResponse
     */
    public function __invoke(User $data)
    {
        // Entity is persisted automatically, only if validaton pass
        $this->getValidator()->validate($data);

        $data->setPassword(
            $this->getPasswordEncoder()->encodePassword(
                $data, $data->getNewPassword()
            )
        );
        // After password change, old tokens are still valid
        $data->setPasswordChangeDate(time());

        $this->getManager()->flush();

        $token = $this->getTokenManager()->create($data);

        return new JsonResponse(['token' => $token]);
    }
}
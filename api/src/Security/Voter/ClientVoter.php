<?php

namespace App\Security\Voter;

use App\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientVoter extends Voter
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['SELF_AND_ADMIN'])
            && $subject instanceof Client;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Client $subject */

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'SELF_AND_ADMIN':
                // logic to determine if the user can EDIT
                // return true or false
//                dd($user->getId());
                if ($subject->getId() === $user->getId()) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                return false;
        }

        throw new \Exception(sprintf('unhandled attribute "$s"', $attribute));
    }
}

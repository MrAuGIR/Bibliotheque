<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\Comments;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const VIEW = "view";
    const EDIT = "edit";
    const CREATE = 'create';
    const DELETE = 'delete';
    const MANAGE = 'manage';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attributes, $subject)
    {

        //Si l'attribut fait partie de ceux supportés et 
        //on vote seulement avec un objet de class Comments
        return \in_array($attributes, [self::VIEW, self::EDIT, self::CREATE, self::DELETE, self::MANAGE]) && ($subject instanceof Comments);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            //si l'utilisateur n'est pas logger, deny access
            return false;
        }

        //Si je suis l'administrateur j'ai tous les droit
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        //si on est là, c'est que $subject est un objet Comments
        /** @var Comments $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::VIEW:
                return true;
            case self::CREATE:
                return true;
            case self::EDIT:
                return $comment->getAuthor() == $user;
            case self::DELETE:
                return $comment->getAuthor() == $user;
            case self::MANAGE: //form biblio
                return $comment->getAuthor() == $user;
        }

        return false;
    }

}

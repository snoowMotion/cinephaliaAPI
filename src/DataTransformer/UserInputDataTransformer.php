<?php

namespace App\DataTransformer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserInputDataTransformer implements DenormalizerInterface
{

    private \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher $passwordHasher;

    public function __construct(\Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @throws Exception
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if($type !== User::class)
        {
            throw new \Exception('The data is not of type User');
        }
        $user = new User();
        if(isset($data['password']))
        {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        }
        if(isset($data['email']))
        {
            $user->setEmail($data['email']);
        }
        if(isset($data['nom']))
        {
            $user->setNom($data['nom']);
        }
        if(isset($data['prenom']))
        {
            $user->setPrenom($data['prenom']);
        }

        return $user;

    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === User::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [User::class => true];
    }
}
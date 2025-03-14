<?php

namespace App\DataTransformer;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserInputDataTransformer implements DenormalizerInterface
{

    private \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher $passwordHasher;
    private EntityManager $entityManager;

    public function __construct(\Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher $passwordHasher, EntityManager $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
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
        $roleUser = $this->entityManager->getRepository(Role::class)->findOneBy(['libelle' => 'ROLE_USER']);
        $user->addRole($roleUser);
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
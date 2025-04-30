<?php

namespace App\DataTransformer;

use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class RoleDataTransformer implements DenormalizerInterface
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function denormalize($data, $type, $format = null, array $context = []): mixed
    {
        // Vérifie si les données sont un tableau d'identifiants
        if (!is_array($data)) {
            throw new UnexpectedValueException('Expected an array of role IDs.');
        }

        $roles = [];
        foreach ($data as $id) {
            $role = $this->entityManager->getRepository(Role::class)->find($id);
            if (!$role) {
                throw new UnexpectedValueException(sprintf('Role with ID %d not found.', $id));
            }
            $roles[] = $role;
        }

        return $roles;
    }

    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        // Vérifie que nous travaillons sur une relation avec Role
        return $type === 'Doctrine\Common\Collections\Collection' && isset($context['targetEntity']) && $context['targetEntity'] === Role::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return ['Doctrine\Common\Collections\Collection'];
    }
}

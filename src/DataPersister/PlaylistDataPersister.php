<?php

namespace App\DataPersister;

use App\Entity\Playlist;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

class PlaylistDataPersister implements ContextAwareDataPersisterInterface
{
    protected $security;
    protected $em;
    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    //Permet de savoir si nous sommes sur une requete de creation de playlist et de prendre la main uniquement si c'est le cas
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Playlist && $data->getId() === null;
    }

    public function persist($data, array $context = [])
    {
        $data->setUser($this->security->getUser());

        $this->em->persist($data);
        $this->em->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
        return $data;
    }
}

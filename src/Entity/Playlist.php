<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext= {"groups" : {"playlist:read"}}, denormalizationContext= { "disable_type_enforcement": true})
 * @ORM\Entity(repositoryClass="App\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"playlist:read", "tag:read", "user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"playlist:read", "tag:read", "user:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"playlist:read", "tag:read", "user:read"})
     */
    private $decription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"playlist:read", "tag:read"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="playlists")
     * @Groups({"playlist:read", "user:read"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Song", mappedBy="playlist", orphanRemoval=true)
     * @Groups({"playlist:read", "user:read"})
     */
    private $songs;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): self
    {
        $this->decription = $decription;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPlaylist($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removePlaylist($this);
        }

        return $this;
    }

    /**
     * @return Collection|Song[]
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    public function addSong(Song $song): self
    {
        if (!$this->songs->contains($song)) {
            $this->songs[] = $song;
            $song->setPlaylist($this);
        }

        return $this;
    }

    public function removeSong(Song $song): self
    {
        if ($this->songs->contains($song)) {
            $this->songs->removeElement($song);
            // set the owning side to null (unless already changed)
            if ($song->getPlaylist() === $this) {
                $song->setPlaylist(null);
            }
        }

        return $this;
    }
}

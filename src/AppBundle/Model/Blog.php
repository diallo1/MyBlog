<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * No needed anymore, replaced by AppBundle\Entity\Contact
 */
class Blog
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    private $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=10, max=2000)
     */
    private $contenu;

    private $publishedAt;

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
}

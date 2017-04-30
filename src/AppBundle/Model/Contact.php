<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * No needed anymore, replaced by AppBundle\Entity\Contact
 */
class Contact
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email(message="Email {{ value }} non valide.")
     */
    private $email;

    /**
     * @Assert\NotBlank()
     */
    private $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=10, max=2000)
     */
    private $message;

    private $sentAt;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setSentAt(\DateTime $sentAt)
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getSentAt()
    {
        return $this->sentAt;
    }
}

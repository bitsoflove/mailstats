<?php

namespace BitsOfLove\MailStats\ValueObjects\Emails;

abstract class Email
{

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * From constructor.
     * @param string$email
     * @param null|string $name
     */
    public function __construct($email, $name = null)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    public function __toString()
    {
        if (!is_null($this->name)) {
            return "{$this->name} <{$this->email}>";
        }

        return $this->email;
    }
}

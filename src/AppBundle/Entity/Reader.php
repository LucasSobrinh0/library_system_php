<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="Readers",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="cpf_unique", columns={"cpf"})}
 * )
 * @UniqueEntity(
 *     fields={"cpf"},
 *     message="Este CPF já está cadastrado."
 * )
 */
class Reader
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** 
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * */
    private $name;

    /** 
     * @ORM\Column(name="phone", type="string", length=15)
     * @Assert\NotBlank
     * @Assert\Length(max=15)
     */
    
    private $phone;

    /** 
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private $dateOfBirth;

    /** 
     * @ORM\Column(name="cpf", type="string", length=14)
     * @Assert\NotBlank
     * @Assert\Length(max=14)
     */
    private $cpf;

    public function __toString()
    {
        return $this->getName();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
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

    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

}

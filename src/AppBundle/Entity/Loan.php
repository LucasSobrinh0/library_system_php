<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Loan")
 */
class Loan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Reader")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $reader;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Assert\NotBlank
     */
    private $loanDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $returnDate;

    /**
     * @ORM\Column(type="string", length=20, options={"default":"NOT_RETURNED"})
     * @Assert\Choice(choices={"NOT_RETURNED","RETURNED"})
     */
    private $status = 'NOT_RETURNED';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set loanDate
     *
     * @param \DateTime $loanDate
     *
     * @return Loan
     */
    public function setLoanDate($loanDate)
    {
        $this->loanDate = $loanDate;

        return $this;
    }

    /**
     * Get loanDate
     *
     * @return \DateTime
     */
    public function getLoanDate()
    {
        return $this->loanDate;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     *
     * @return Loan
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return Loan
     */
    public function setBook(\AppBundle\Entity\Book $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \AppBundle\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set reader
     *
     * @param \AppBundle\Entity\Reader $reader
     *
     * @return Loan
     */
    public function setReader(\AppBundle\Entity\Reader $reader)
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * Get reader
     *
     * @return \AppBundle\Entity\Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

     public function __construct()
    {
        // assegura o valor padrão ao criar um novo empréstimo
        $this->status = 'NOT_RETURNED';
    }

    /**
     * @param string $status
     * @return Loan
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="Book",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="isbn_idx", columns={"isbn"})}
 * )
 * @UniqueEntity(
 *      fields={"isbn"},
 *      message="Já existe um ISBN cadastrado."
 * )
 */

 class Book{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=150)
     * @Assert\NotBlank
     * @Assert\Length(max=150)
     */
    private $title;

    /**
     * @ORM\Column(name="isbn", type="string", length=13)
     * @Assert\NotBlank
     * @Assert\Length(max=13)
     */
    private $isbn;

    /**
     * @ORM\Column(name="ano", type="integer")
     * @Assert\NotBlank
     * @Assert\Range(min=1000, max=9999)
     */
    private $ano;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(nullable=false, onDelete="RESTRICT")
     */
    private $author;

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
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set isbn
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return string
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set ano
     *
     * @param integer $ano
     *
     * @return Book
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }

    /**
     * Get ano
     *
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Book
     */
    public function setCategory(\AppBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\Author $author
     *
     * @return Book
     */
    public function setAuthor(\AppBundle\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }
}

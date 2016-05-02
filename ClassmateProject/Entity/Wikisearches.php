<?php

namespace Project\ClassMateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wikisearches
 */
class Wikisearches
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $term;

    /**
     * @var string
     */
    private $text;

    /**
     * @var integer
     */
    private $wikisearchId;


    /**
     * Set userId
     *
     * @param integer $userId
     * @return Wikisearches
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set term
     *
     * @param string $term
     * @return Wikisearches
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get term
     *
     * @return string 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Wikisearches
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get wikisearchId
     *
     * @return integer 
     */
    public function getWikisearchId()
    {
        return $this->wikisearchId;
    }
}

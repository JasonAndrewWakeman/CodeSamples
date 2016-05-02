<?php

namespace Project\ClassMateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enrollment
 */
class Enrollment
{
    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $courseName;

    /**
     * @var string
     */
    private $enrolled;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $courseId;

    /**
     * @var integer
     */
    private $enrollmentId;


    /**
     * Set role
     *
     * @param string $role
     * @return Enrollment
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set courseId
     *
     * @param integer $courseId
     * @return Enrollment
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;

        return $this;
    }

    /**
     * Get courseId
     *
     * @return integer 
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * Set courseName
     *
     * @param string $courseName
     * @return Enrollment
     */
    public function setCourseName($courseName)
    {
        $this->courseName = $courseName;

        return $this;
    }

    /**
     * Get courseName
     *
     * @return string 
     */
    public function getCourseName()
    {
        return $this->courseName;
    }


    /**
     * Set enrolled
     *
     * @param string $enrolled
     * @return Enrollment
     */
    public function setEnrolled($enrolled)
    {
        $this->enrolled = $enrolled;

        return $this;
    }

    /**
     * Get enrolled
     *
     * @return string 
     */
    public function getEnrolled()
    {
        return $this->enrolled;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Enrollment
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
     * Get enrollmentId
     *
     * @return integer 
     */
    public function getEnrollmentId()
    {
        return $this->enrollmentId;
    }
}

<?php

namespace Project\ClassMateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/*namespace Project\ClassMateBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;*/


/**
 * Courses
 */
class Courses
{
    /**
     * @var integer
     */
    private $creatorUserId;

    /**
     * @var string
     */
    private $courseName;

    /**
     * @var string
     */
    private $courseCode;

    /**
     * @var \DateTime
     */
    private $dateCreated;

    /**
     * @var \DateTime
     */
    private $dateModified;

    /**
     * @var \DateTime
     */
    private $dateDeleted;

    /**
     * @var integer
     */
    private $courseId;


    /**
     * Set creatorUserId
     *
     * @param integer $creatorUserId
     * @return Courses
     */
    public function setCreatorUserId($creatorUserId)
    {
        $this->creatorUserId = $creatorUserId;

        return $this;
    }

    /**
     * Get creatorUserId
     *
     * @return integer 
     */
    public function getCreatorUserId()
    {
        return $this->creatorUserId;
    }

    /**
     * Set courseName
     *
     * @param string $courseName
     * @return Courses
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
     * Set courseCode
     *
     * @param string $courseCode
     * @return Courses
     */
    public function setCourseCode($courseCode)
    {
        $this->courseCode = $courseCode;

        return $this;
    }

    /**
     * Get courseCode
     *
     * @return string 
     */
    public function getCourseCode()
    {
        return $this->courseCode;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Courses
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return Courses
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set dateDeleted
     *
     * @param \DateTime $dateDeleted
     * @return Courses
     */
    public function setDateDeleted($dateDeleted)
    {
        $this->dateDeleted = $dateDeleted;

        return $this;
    }

    /**
     * Get dateDeleted
     *
     * @return \DateTime 
     */
    public function getDateDeleted()
    {
        return $this->dateDeleted;
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
    *get path
    *@return string
    */

    protected function getUploadPath(){
        return '';
    }
    /**
    *get path
    *@return string
    */
    protected function getUploadAbsolutePath(){
        return '/home/classmate/archives';
        //return 'home/classmate/' . $this->getUPloadPath();    
    }
    /**
    *get web path
    *@return null|string
    */
    public function getArchiveWeb(){
        return NULL === $this->getCourseName()
            ? NULL 
            : $this->getUploadPath() . '/' . $this->getCourseName();
    }
    /**
    *get path
    *@return null|string
    */
     public function getArchiveWebAbsolute(){
        return NULL === $this->getCourseName()
            ? NULL 
            : $this->getUploadAbsolutePath() . '/' . $this->getCourseName();
    }
    /**
    *@Assert\File(maxSize="1000000")
    */
    private $file;
    /**
    *get path
    *@param \Symfony\Component\HttpFoundation\File\UploadedFile $file
    */
    public function setFile(UploadedFile $file = NULL){
        $this->file = $file;
    }
    /**
    *get path
    *@return UploadedFile
    */
    public function getFile(){
        return $this->file;
    }
    /**
    *get path
    *@return string
    */
    public function upload(){
        if(NULL === $this->getFile()){
            return;
        }

        $filename = $this->getFile()->getClientOriginalNAme();

        $this->getFile()->move($this->getUploadAbsolutePath(), $filename);

        $this->setCourseName($filename);

        $this->setFile();
    }
}

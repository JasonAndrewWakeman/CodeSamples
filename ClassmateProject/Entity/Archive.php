<?php

namespace Project\ClassMateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Archive
 */
class Archive
{
    /**
     * @var integer
     */
    private $archiveUserId;

    /**
     * @var integer
     */
    private $archiveCourseId;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileType;

    /**
     * @var string
     */
    private $fileLocation;

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
    private $archiveId;


    /**
     * Set archiveUserId
     *
     * @param integer $archiveUserId
     * @return Archive
     */
    public function setArchiveUserId($archiveUserId)
    {
        $this->archiveUserId = $archiveUserId;

        return $this;
    }

    /**
     * Get archiveUserId
     *
     * @return integer 
     */
    public function getArchiveUserId()
    {
        return $this->archiveUserId;
    }

    /**
     * Set archiveCourseId
     *
     * @param integer $archiveCourseId
     * @return Archive
     */
    public function setArchiveCourseId($archiveCourseId)
    {
        $this->archiveCourseId = $archiveCourseId;

        return $this;
    }

    /**
     * Get archiveCourseId
     *
     * @return integer 
     */
    public function getArchiveCourseId()
    {
        return $this->archiveCourseId;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Archive
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return Archive
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string 
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set fileLocation
     *
     * @param string $fileLocation
     * @return Archive
     */
    public function setFileLocation($fileLocation)
    {
        $this->fileLocation = $fileLocation;

        return $this;
    }

    /**
     * Get fileLocation
     *
     * @return string 
     */
    public function getFileLocation()
    {
        return $this->fileLocation;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Archive
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
     * @return Archive
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
     * @return Archive
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
     * Get archiveId
     *
     * @return integer 
     */
    public function getArchiveId()
    {
        return $this->archiveId;
    }

    protected function getUploadPath(){
        return '../archives';
    }
    protected function getUploadAbsolutePAth(){
        return _DIR_ . '/../../../../web/' . $this->getUPloadPAth();    
    }
    public function getArchiveWeb(){
        return NULL === $this->getArchiveCourseId()
            ? NULL 
            : $this->getUploadPath() . '/' . $this->getArchiveCourseId();
    }
     public function getArchiveWebAbsolute(){
        return NULL === $this->getArchiveCourseId()
            ? NULL 
            : $this->getUploadAbsolutePath() . '/' . $this->getArchiveCourseId();
    }
    /**
    *@Assert\File(maxSize="100000000")
    */
    private $file;

    public function setFile(UploadedFile $file = NULL){
        $this->file = $file;
    }
    public function getFile(){
        return $this->file;
    }
    public function upload(){
        if(NULL === $this->getFile()){
            return;
        }

        $filename = $this->getFile()->getClientOriginalNAme();

        $this->getFile()->move($this->getUploadPath(), $filename);

        $this->setArchiveCourseId($filename);
    }

    protected function getNewUploadPath($courseId){
        return 'media/' .  $courseId;
    }

    public function uploadFileNew($courseId){
        if(NULL === $this->getFile()){
            return;
        }

        $filename = $this->getFile()->getClientOriginalNAme();

        $this->getFile()->move($this->getNewUploadPath($courseId), $filename);

        $this->setArchiveCourseId($filename);
    }
}

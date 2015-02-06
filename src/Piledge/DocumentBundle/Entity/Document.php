<?php

namespace Piledge\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Document
 *
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="Piledge\DocumentBundle\Entity\DocumentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="document_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $document_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Piledge\AuthorBundle\Entity\Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="author_id", nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="document_ext", type="string", length=10)
     * @Assert\Length(min="2")
     */
    private $document_ext;

    /**
     * @var string
     *
     * @ORM\Column(name="document_title", type="string", length=255)
     * @Assert\Length(
     *       min="5",
     *       max="255",
     *       minMessage="The title must be at least {{ limit }} characters",
     *       maxMessage="The title can't be longer than {{ limit }} characters"
     *       )
     * @Assert\NotBlank(message="The title must not be empty. Please try again")
     */
    private $document_title;

    /**
     * @var string
     *
     * @ORM\Column(name="document_description", type="text")
     * @Assert\NotBlank(message="The description must not be empty. Please try again")
     */
    private $document_description;

    /**
     * @var string
     *
     * @ORM\Column(name="document_type", type="string", length=100)
     * @Assert\Length(min="2")
     */
    private $document_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="document_size", type="integer")
     * @Assert\Range(min=1)
     */
    private $document_size;

    /**
     * @var string
     *
     * @ORM\Column(name="document_file_name", type="text")
     */
    private $document_file_name;

    /**
     * @var string
     *
     * @ORM\Column(name="document_thumb_name", type="text")
     */
    private $document_thumb_name;

    /**
     * @var string
     *
     * @ORM\Column(name="document_pdf_name", type="text")
     */
    private $document_pdf_name;

    /**
     * @var integer
     *
     * @ORM\Column(name="document_number_of_page", type="integer")
     * @Assert\Range(min=1)
     */
    private $document_number_of_page;


    /**
     * @var integer
     *
     * @ORM\Column(name="document_number_of_comment", type="integer", nullable=true)
     * @Assert\Range(min=0)
     */
    private $document_number_of_comment;
  

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="document_created_at", type="datetime")
     * @Assert\DateTime()
     */
    private $document_created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="document_updated_at", type="datetime")
     * @Assert\DateTime()
     */
    private $document_updated_at;
    
    /**
     * @Assert\File(
     *      maxSize="20M", 
     *      mimeTypes={"application/pdf", "application/x-pdf"}, 
     *      mimeTypesMessage="Please Choose a pdf document valid. Try again"
     *      )
     */
    private $file;

    private $temp_file_name;

    private $document_path_name;
       

    public function __construct() {
        $this->document_created_at = new \Datetime;
        $this->document_updated_at = new \Datetime;
    }

    
    protected function get_thumb($file_name) {
        
        $this->document_thumb_name = $this->getUploadDir().'/thumb_document/'.$this->get_document_path_name().'.png';
        $thumb = new \Imagick($file_name . '[0]');
        $thumb->thumbnailImage(150, 200);
        $thumb->setImageFormat('png');
        $thumb->writeImage($this->document_thumb_name);
    }


    public function get_number_of_pages($document)  {

        $cmd = "/usr/bin/pdfinfo";          // Linux
        //$cmd = "C:\\location\\pdfinfo.exe"; // Windows

        // Parse entire output
        exec("$cmd \"$document\"", $output);

        // Iterate through lines
        $pagecount = 0;
        foreach($output as $op) {

            // Extract the number
            if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1) {
                $pagecount = intval($matches[1]);
                break;
            }
        }

       return $pagecount;
    }



    /**
     * @ORM\PreUpdate
     * -- Pre Update document: Callback for update the document at every update of the entity
     */
    public function update_document_updated_at() {
        $this->setDocumentUpdatedAt(new \Datetime());
    }


   /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload() {

        if ($this->file === null) return;
        
        $this->set_document_path_name($this->file->guessExtension()); 
        $this->document_ext = $this->file->guessExtension();
        $this->document_type = $this->file->getMimeType();
        $this->document_size = $this->file->getClientSize();

        try {

            $this->upload();

        } catch(Exception $e) {
          return;
        }

        $this->document_pdf_name = $this->getUploadDir().'/'.$this->document_path_name;
        $this->document_file_name = $this->document_pdf_name;
    }


    
     // @ORM\PostPersist()
     // @ORM\PostUpdate()
     
  

    public function upload() {

        if ($this->file === null) {
            return;
        }

        // If there is a old file, remove it
        if ($this->temp_file_name !== null) {
            $old_file = $this->getUploadRootDir().'/'.$this->get_document_path_name();
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        $this->file->move($this->getUploadRootDir(), $this->get_document_path_name()); 
        $this->get_thumb($this->getUploadRootDir().'/'.$this->get_document_path_name());
        $this->document_number_of_page = $this->get_number_of_pages($this->getUploadRootDir().'/'.$this->get_document_path_name());

    } 


    /**
     *  @ORM\PreRemove()
     */
    public function preRemoveUpload() {
        $this->temp_file_name = $this->getUploadRootDir().'/'.$this->get_document_path_name();

    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {

        if (file_exists($this->temp_file_name)) {
            unlink($this->temp_file_name);
        }
    }
   
    public function set_document_path_name($doc_ext) {

        $this->document_path_name = rand(0, 9999999).'_'.sha1(uniqid(mt_rand(), true)).'.'.$doc_ext;
        return $this;
    }

    public function get_document_path_name() {
        return $this->document_path_name;
    }


    public function getUploadDir() {

        return 'uploads/pdf';
    }

    
    protected function getUploadRootDir() {

        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }


    public function getWebPath() {

        return $this->getUploadDir().'/'.$this->document_path_name;
    }



    /**
     * Get document_id
     *
     * @return integer 
     */
    public function getDocumentId()
    {
        return $this->document_id;
    }

    /**
     * Set document_ext
     *
     * @param string $documentExt
     * @return Document
     */
    public function setDocumentExt($documentExt)
    {
        $this->document_ext = $documentExt;

        return $this;
    }

    /**
     * Get document_ext
     *
     * @return string 
     */
    public function getDocumentExt()
    {
        return $this->document_ext;
    }

    /**
     * Set document_title
     *
     * @param string $documentTitle
     * @return Document
     */
    public function setDocumentTitle($documentTitle)
    {
        $this->document_title = $documentTitle;

        return $this;
    }

    /**
     * Get document_title
     *
     * @return string 
     */
    public function getDocumentTitle()
    {
        return $this->document_title;
    }

    /**
     * Set document_description
     *
     * @param string $documentDescription
     * @return Document
     */
    public function setDocumentDescription($documentDescription)
    {
        $this->document_description = $documentDescription;

        return $this;
    }

    /**
     * Get document_description
     *
     * @return string 
     */
    public function getDocumentDescription()
    {
        return $this->document_description;
    }

    /**
     * Set document_type
     *
     * @param string $documentType
     * @return Document
     */
    public function setDocumentType($documentType)
    {
        $this->document_type = $documentType;

        return $this;
    }

    /**
     * Get document_type
     *
     * @return string 
     */
    public function getDocumentType()
    {
        return $this->document_type;
    }

    /**
     * Set document_size
     *
     * @param integer $documentSize
     * @return Document
     */
    public function setDocumentSize($documentSize)
    {
        $this->document_size = $documentSize;

        return $this;
    }

    /**
     * Get document_size
     *
     * @return integer 
     */
    public function getDocumentSize()
    {
        return $this->document_size;
    }

    /**
     * Set document_file_name
     *
     * @param string $documentFileName
     * @return Document
     */
    public function setDocumentFileName($documentFileName)
    {
        $this->document_file_name = $documentFileName;

        return $this;
    }

    /**
     * Get document_file_name
     *
     * @return string 
     */
    public function getDocumentFileName()
    {
        return $this->document_file_name;
    }

    /**
     * Set document_thumb_name
     *
     * @param string $documentThumbName
     * @return Document
     */
    public function setDocumentThumbName($documentThumbName)
    {
        $this->document_thumb_name = $documentThumbName;

        return $this;
    }

    /**
     * Get document_thumb_name
     *
     * @return string 
     */
    public function getDocumentThumbName()
    {
        return $this->document_thumb_name;
    }

    /**
     * Set document_pdf_name
     *
     * @param string $documentPdfName
     * @return Document
     */
    public function setDocumentPdfName($documentPdfName)
    {
        $this->document_pdf_name = $documentPdfName;

        return $this;
    }

    /**
     * Get document_pdf_name
     *
     * @return string 
     */
    public function getDocumentPdfName()
    {
        return $this->document_pdf_name;
    }

    /**
     * Set document_number_of_page
     *
     * @param integer $documentNumberOfPage
     * @return Document
     */
    public function setDocumentNumberOfPage($documentNumberOfPage)
    {
        $this->document_number_of_page = $documentNumberOfPage;

        return $this;
    }

    /**
     * Get document_number_of_page
     *
     * @return integer 
     */
    public function getDocumentNumberOfPage()
    {
        return $this->document_number_of_page;
    }

    /**
     * Set document_created_at
     *
     * @param \DateTime $documentCreatedAt
     * @return Document
     */
    public function setDocumentCreatedAt($documentCreatedAt)
    {
        $this->document_created_at = $documentCreatedAt;

        return $this;
    }

    /**
     * Get document_created_at
     *
     * @return \DateTime 
     */
    public function getDocumentCreatedAt()
    {
        return $this->document_created_at;
    }

    /**
     * Set document_updated_at
     *
     * @param \DateTime $documentUpdatedAt
     * @return Document
     */
    public function setDocumentUpdatedAt($documentUpdatedAt)
    {
        $this->document_updated_at = $documentUpdatedAt;

        return $this;
    }

    /**
     * Get document_updated_at
     *
     * @return \DateTime 
     */
    public function getDocumentUpdatedAt()
    {
        return $this->document_updated_at;
    }


    public function setFile($file) {

        $this->file = $file;

        if ($this->document_file_name !== null) {

            $this->temp_file_name = $this->document_file_name;

            $this->document_file_name = null;
        }
    }


    public function getFile() {

        return $this->file;
    }



    /**
     * Set author
     *
     * @param \Piledge\AuthorBundle\Entity\Author $author
     * @return Document
     */
    public function setAuthor(\Piledge\AuthorBundle\Entity\Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Piledge\AuthorBundle\Entity\Author 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set document_number_of_comment
     *
     * @param integer $documentNumberOfComment
     * @return Document
     */
    public function setDocumentNumberOfComment($documentNumberOfComment)
    {
        $this->document_number_of_comment = $documentNumberOfComment;

        return $this;
    }

    /**
     * Get document_number_of_comment
     *
     * @return integer 
     */
    public function getDocumentNumberOfComment()
    {
        return $this->document_number_of_comment;
    }
}

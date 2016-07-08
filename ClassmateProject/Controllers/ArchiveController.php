<?php

namespace Project\ClassMateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Project\ClassMateBundle\Entity\Archive;
use Project\ClassMateBundle\Form\ArchiveType;
use Project\ClassMateBundle\Entity\Courses;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Form;

/**
 * Archive controller.
 *
 */
class ArchiveController extends Controller
{

    /**
     * Lists all Archive entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ClassMateBundle:Archive')->findAll();

        return $this->render('ClassMateBundle:Archive:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Archive entity.
     *
     */
    public function createAction(Request $request, $courseID, $userRole, $courseName)
    {

 	$session=$this->getRequest()->getSession();
    /*    
        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            return $this->render('ClassMateBundle:Default:login.html.twig', array('name'=> '', 'successMessage'=>$message));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
            return $this->render('ClassMateBundle:Default:login.html.twig', array('name'=> '', 'errorMessage'=>$message));
        }
*/
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $courseConfirm = $repository -> findOneBy(array('userId' => $session->get('user')->getId() , 'role' => $userRole, 'courseId'=>$courseID, 'courseName'=>$courseName ));

        $repository2 = $em->getRepository('ClassMateBundle:Courses'); 
        $course = $repository2 -> findOneBy(array('courseId'=>$courseID));


        if(!$courseConfirm){
            $session->set('errorMessage' , "You do not have permission to access that page ");

            return $this->redirect($this->generateUrl('class_mate_next'), 301);
            //return $this->redirect($this->generateUrl('class_mate_next'), 301);
            //return $this->render('ClassMateBundle:User:course_home.html.twig', array('course'=>$course, 'enrollment'=>$courseConfirm, 'courseFiles'=>$courseFiles, 'userCourseFiles'=>$userCourseFiles));
        }

        


        if($returnPage = $this->checkSession()){
            return $returnPage;
        }
    	$dateCreated=date('Y-m-d H:i:s');
            $archive = new Archive();
    	$form = $this->createCreateForm($archive, $courseID, $userRole, $courseName);
       

        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            
            return $this->render('ClassMateBundle:Archive:new.html.twig', array(
                'course'=>$course, 
                'enrollment'=>$courseConfirm, 
                'entity' => $archive,
                'form'   => $form->createView(),
                'successMessage'=>$message
            ));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
             return $this->render('ClassMateBundle:Archive:new.html.twig', array(
                'course'=>$course, 
                'enrollment'=>$courseConfirm, 
                'entity' => $archive,
                'form'   => $form->createView(),
                'errorMessage'=>$message
            ));
        
        }


        $archive->setArchiveUserId($session->get('user')->getId());
        $archive->setArchiveCourseId($courseID);
        $archive->setDateCreated(new \DateTime($dateCreated));
        $archive->setFileLocation('media/$courseID');
        $form->handleRequest($request);
       

        if ($form->isValid()) {
            $file = $form->get('file');

            if($file == NULL ||     $file->getData() == NULL){
                $session->set('errorMessage' , "You must select a file to upload ");
                return $this->redirect($this->generateUrl('class_mate_new_file_upload', array('courseID'=> $courseID, 'courseName'=> $courseName, 'userRole'=> $userRole)));
            }

            $fileName=$file->getData()->getClientOriginalName();
          
            $fileArr = preg_split("/\./", $fileName);

         
           $fileName1 = $fileArr[0];
            $fileType = $fileArr[1];


            //jpeg, pdf, gzip, zip, ogg, pptx, ppsx, docx, xml, mpeg, mp4, avi, webm, gif, png, 
            if( $fileType != "jpeg" &&  $fileType != "pdf" && $fileType != "gzip" && $fileType != "zip" && $fileType != "ogg" && $fileType != "pptx" &&
                    $fileType != "ppsx" && $fileType != "doc" &&$fileType != "xml" &&$fileType != "mpeg" &&$fileType != "mp4" &&$fileType != "avi" &&
                    $fileType != "webm" && $fileType != "gif" &&$fileType != "png" && $fileType !="jpg"){
                

	            $session->set('errorMessage' , "This file format is not supported please use one of the following extensions: jpeg, pdf, gzip, zip, ogg, pptx, ppsx, docx, xml, mpeg, mp4, avi, webm, gif, png ");
            	     return $this->redirect($this->generateUrl('class_mate_new_file_upload', array('courseID'=> $courseID, 'courseName'=> $courseName, 'userRole'=> $userRole)));
            }    
	

		//'class_mate_course_page', {'courseName': course.courseName, 'userRole': enrollment.role, 'courseID': course.courseId }

    

            $archive->setFileType($fileType);
            $archive->setFileName($fileName1);
           // $archive->setFileLocation($fileLocation);

            $em = $this->getDoctrine()->getManager();
            $em->persist($archive);
            $em->flush();
            $archive->uploadFileNew($courseID);

            $session->set('successMessage' , "Your file was successfully uploaded ");
            return $this->redirect($this->generateUrl('class_mate_course_page', array('courseID'=> $courseID, 'courseName'=> $courseName, 'userRole'=> $userRole)));
        }

        return $this->render('ClassMateBundle:Archive:new.html.twig', array(
            'course'=>$course, 
            'enrollment'=>$courseConfirm, 
            'entity' => $archive,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Archive entity.
     *
     * @param Archive $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Archive $entity, $courseID, $userRole, $courseName)
    {
        $form = $this->createForm(new ArchiveType(), $entity, array(
            'action' => $this->generateUrl('archive_create', array(
            'courseID'=>$courseID,
            'courseName'=>$courseName,
            'userRole'=>$userRole
        )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Archive entity.
     *
     */
    public function newAction()
    {
        $entity = new Archive();
        $form   = $this->createCreateForm($entity);

        return $this->render('ClassMateBundle:Archive:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Archive entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:Archive')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archive entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:Archive:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Archive entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:Archive')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archive entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:Archive:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Archive entity.
    *
    * @param Archive $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Archive $entity)
    {
        $form = $this->createForm(new ArchiveType(), $entity, array(
            'action' => $this->generateUrl('archive_update', array('id' => $entity->getArchiveId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Archive entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:Archive')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archive entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
             $entity->upload();
            return $this->redirect($this->generateUrl('archive_edit', array('id' => $id)));
        }

        return $this->render('ClassMateBundle:Archive:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Archive entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClassMateBundle:Archive')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archive entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('class_mate_archive_list'));
    }

    /**
     * Creates a form to delete a Archive entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('archive_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function archivelistAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ClassMateBundle:Archive')->findAll();

        return $this->render('ClassMateBundle:Archive:list.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function getMaxArchive(){
         $entity-> getArchiveId();
         

    }

    public function newFileUploadAction($courseID, $userRole, $courseName) {
        if($returnPage = $this->checkSession())
        {
            return $returnPage;
        }
        $session = $this->getRequest()->getSession();


        $entity = new Archive();
        $entity->setArchiveCourseId($courseID);
        $entity->setArchiveUserId($session->get('user')->getId());

        
        $form   = $this->createCreateForm($entity);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $courseConfirm = $repository -> findOneBy(array('userId' => $session->get('user')->getId() , 'role' => $userRole, 'courseId'=>$courseID, 'courseName'=>$courseName ));

        $repository2 = $em->getRepository('ClassMateBundle:Courses'); 
        $course = $repository2 -> findOneBy(array('courseId'=>$courseID));

        $repository3 = $em->getRepository('ClassMateBundle:Archive'); 
        $courseFiles = $repository3 -> findBy(array('archiveCourseId'=>$courseID));

        if($courseConfirm){
            //return $this->redirect($this->generateUrl('class_mate_next'), 301);
            return $this->render('ClassMateBundle:User:upload.html.twig', array('form'=>$form->createView(), 'course'=>$course, 'enrollment'=>$courseConfirm, 'courseFiles'=>$courseFiles));
        }

        return $this->redirect($this->generateUrl('class_mate_next'), 301);
    }


    public function newFileUploadSubmitAction(Request $request, $courseName) {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Courses');
        $repository2 = $em->getRepository('ClassMateBundle:Enrollment');

        if($request->getMethod()=='POST'){

            $courseId=$request->get('courseId');
            $enrollmentId=$request->get('enrollmentId');
            
            $course = $repository->findOneBy(array('courseId'=>$courseId));

            
            $enrollment = $repository2->findOneBy(array('enrollmentId'=>$enrollmentId));

            $courseName = $course->getCourseName();
            $role = $enrollment->getRole();

            $dateCreated=date('Y-m-d H:i:s');
            $archive = new Archive();
            $archiveId = $courseId;
            $archive->setArchiveUserId(1);
            $archive->setArchiveCourseId($courseId);
            $archive->setFileLocation($archiveId);
            $archive->setFileType($archiveId);
            $archive->setFileName($archiveId);
            $archive->setDateCreated(new \DateTime($dateCreated));

            

            //$em2 = $this->getDoctrine()->getManager();
            

              $archive->upload();

            $em->persist($archive);
          
            $em->flush();


            return $this->redirect($this->generateUrl('class_mate_course_page', array('courseName'=> $courseName, 'userRole'=> $role, 'courseID'=>$courseId ), 301 ));
        }

        return $this->render('ClassMateBundle:User:upload.html.twig');

    }
}

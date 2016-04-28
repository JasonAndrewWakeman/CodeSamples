<?php

namespace Project\ClassMateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\BrowserKit\Response;
use Project\ClassMateBundle\Entity\User;
use Project\ClassMateBundle\Form\UserType;
use Project\ClassMateBundle\Entity\Wikisearches;
use Project\ClassMateBundle\Entity\Archive;
use Project\ClassMateBundle\Entity\Enrollment;
use Project\ClassMateBundle\Entity\Courses;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ClassMateBundle:User')->findAll();

        return $this->render('ClassMateBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));
        }

        return $this->render('ClassMateBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('ClassMateBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editActionOld($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ClassMateBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        return $this->render('ClassMateBundle:User:edit.html.twig', array(
            'entity'      => $entity
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();



        if($request->getMethod()=='POST') {
            $firstName=$request->get('firstname');
            $id=$request->get('id');
            $lastName=$request->get('lastname');
            $email=$request->get('email');
            $dateModified=date('Y-m-d H:i:s');

            
            $user = $em->getRepository('ClassMateBundle:User')->findOneBy(array('id' => $id));

            if(!$user) {
                $message = "Error - Unable to update the user";

                $session->set('errorMessage', $message);
                return $this->redirect($this->generateUrl('class_mate_user_list'), 301);
            }

            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setDateModified(new \DateTime($dateModified));

            $em->flush();

            $message = "Your updates were saved successfully";

            $session->set('successMessage', $message);
            return $this->redirect($this->generateUrl('class_mate_user_list'), 301);
        }

        $message = "Error - Something went wrong. Unable to update the user";

        $session->set('errorMessage', $message);
        return $this->redirect($this->generateUrl('class_mate_user_list'), 301);

    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClassMateBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }







    //Custom Functions
/*
    public function lisCoursesAction()
    {

	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $courseIDs = array();
        $courseNames = array();
        $returnData = array();
        $roleToCheck = $_POST['typeOf'];
        $session = $this->getRequest()->getSession();
        $uID=$session->get('user')->getId();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $objects = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => $roleToCheck ));
    
        foreach ($objects as $value) {
            $courseIDs[] = $value->getCourseId();
            $courseNames[] = $value->getCourseName();
        }

        $returnData['courseIDs']= $courseIDs;
        $returnData['courseNames']= $courseNames;
        return new Response(json_encode($returnData)); 
            exit();
        
    }
*/


   /**
    * If a course is chosen, renders this client's this course home page. Otherwise, redirects to nextAction in default controller, which takes care of some 
    *        accounting and then renders the chooseCourse.html page
    * @param $courseId: the unique database id of selected course
    * @param $userRole: the role of the user for the selected course
    * @param $courseName: the name of selected course
    *
    * @return \either course_home.html or  (eventually) chooseCourse.html
    */

    public function coursePageAction($courseID, $userRole, $courseName)
    {


    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }

        $session = $this->getRequest()->getSession();
    //    $session->set('cc', $courseID);
    //    $session->set('courseName', $courseName);

        $em = $this->getDoctrine()->getManager();
        
        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $courseConfirm = $repository -> findOneBy(array('userId' => $session->get('user')->getId() , 'role' => $userRole, 'courseId'=>$courseID, 'courseName'=>$courseName ));

        $repository2 = $em->getRepository('ClassMateBundle:Courses'); 
        $course = $repository2 -> findOneBy(array('courseId'=>$courseID));

        $repository3 = $em->getRepository('ClassMateBundle:Archive'); 
        $courseFiles = $repository3 -> findBy(array('archiveCourseId'=>$courseID, 'archiveUserId'=>$course->getCreatorUserId()));

        $repository4 = $em->getRepository('ClassMateBundle:Archive'); 
        $userCourseFiles = $repository3 -> findBy(array('archiveCourseId'=>$courseID, 'archiveUserId'=>$session->get('user')->getId()));

        if($courseConfirm){
            //return $this->redirect($this->generateUrl('class_mate_next'), 301);
            return $this->render('ClassMateBundle:User:course_home.html.twig', array('course'=>$course, 'enrollment'=>$courseConfirm, 'courseFiles'=>$courseFiles, 'userCourseFiles'=>$userCourseFiles));
        }

        return $this->redirect($this->generateUrl('class_mate_next'), 301);
    }

/*

    public function archivesAction(Request $request)
    {

	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $curCourse=$request->get('listen_course');
        $session = $this->getRequest()->getSession();
        $session->set('currentCourse', $curCourse);

        return $this->render('ClassMateBundle:User:archives.html.twig', array('name'=>$session->get('user')->getFullName(), 'cc' => $session->get('currentCourse'), 'pizza' => $session->get('user')->getId()));
            
    }
*/
/*

    public function lcourseAction(Request $request)
    {
	
	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $curCourse=$request->get('listen_course');
        $session = $this->getRequest()->getSession();
        $session->set('currentCourse', $curCourse);

        return $this->render('ClassMateBundle:User:lcourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'cc' => $session->get('currentCourse'), 'pizza' => $session->get('user')->getId()));
            
    }

    public function uploadfilesAction(Request $request)
    {

	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $curCourse=$request->get('listen_course');
        $session = $this->getRequest()->getSession();
        $session->set('currentCourse', $curCourse);

        return $this->render('ClassMateBundle:User:enroll.html.twig', array('name'=>$session->get('user')->getFullName(), 'cc' => $session->get('currentCourse'), 'pizza' => $session->get('user')->getId()));
            
    }

    public function pcourseAction(Request $request)
    {
	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $curCourse=$request->get('present_course');
        $session = $this->getRequest()->getSession();
        $session->set('currentCourse', $curCourse);
	
        return $this->render('ClassMateBundle:User:pcourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'cc' => $session->get('currentCourse'), 'pizza' => $session->get('user')->getId()));
            
    }

    public function enrollAction()
    {
	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        $session = $this->getRequest()->getSession();
        
        //  return $this->render('ClassMateBundle:User:listener.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId()));
        return $this->render('ClassMateBundle:User:enroll.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId()));
            
    }
*/

    public function chooseCourseAction()
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }

        $session = $this->getRequest()->getSession();
         $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
            $listenCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
            $presentCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'P' ));



        return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
    }


   /**
    * If a course is chosen, renders this courses 'live-stream', note-making page. Otherwise, redirects to nextAction in default controller, which takes care of some 
    *        accounting and then renders the chooseCourse.html page
    * @param $courseId: the unique database id of selected course
    * @param $courseName: the name of selected course
    *
    * @return \either listener.html or  (eventually) chooseCourse.html
    */

    public function listenerAction($courseID, $courseName)
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }


        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository2 = $em->getRepository('ClassMateBundle:Courses'); 
        $course = $repository2 -> findOneBy(array('courseId'=>$courseID));

        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $courseConfirm = $repository -> findOneBy(array('userId' => $session->get('user')->getId() , 'role' => 'L', 'courseId'=>$courseID, 'courseName'=>$course->getCourseName() ));

        if($courseConfirm){
            //return $this->redirect($this->generateUrl('class_mate_next'), 301);			
            return $this->render('ClassMateBundle:User:listener.html.twig', array('course'=>$course, 'cc' => $course->getCourseId(), 'courseName' => $course->getCourseName(), 'enrollment'=>$courseConfirm, 'cc' => $course->getCourseId()));
        }
        return $this->redirect($this->generateUrl('class_mate_next'), 301);
    }




/**
    * If a course is chosen, renders this client's 'begin-live-stream-presentation' page. Otherwise, redirects to nextAction in default controller, which takes care of some 
    *        accounting and then renders the chooseCourse.html page
    * @param $courseId: the unique database id of selected course
    *
    * @return \either present.html or  (eventually) chooseCourse.html
    */

    public function presenterAction($courseID)
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }
        
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository2 = $em->getRepository('ClassMateBundle:Courses'); 
        $course = $repository2 -> findOneBy(array('courseId'=>$courseID));

        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
        $courseConfirm = $repository -> findOneBy(array('userId' => $session->get('user')->getId() , 'role' => 'P', 'courseId'=>$courseID, 'courseName'=>$course->getCourseName() ));

        if($courseConfirm){
            //return $this->redirect($this->generateUrl('class_mate_next'), 301);
            return $this->render('ClassMateBundle:User:present.html.twig', array('course'=>$course, 'enrollment'=>$courseConfirm));
        }
        return $this->redirect($this->generateUrl('class_mate_next'), 301);
    }

    public function userlistAction()
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }

        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        $entities = $em->getRepository('ClassMateBundle:User')->findAll();

        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            return $this->render('ClassMateBundle:User:list.html.twig', array('successMessage'=>$message, 'entities' => $entities));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
            return $this->render('ClassMateBundle:User:list.html.twig', array('errorMessage'=>$message, 'entities' => $entities));
        }

        return $this->render('ClassMateBundle:User:list.html.twig', array('entities' => $entities ));

    }

    public function aboutAction()
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }

        return $this->render('ClassMateBundle:User:about.html.twig');
    }

   public function adminAction()
    {

      if($returnPage = $this->checkSession())
      {
            return $returnPage;
      } 
      
      $session=$this->getRequest()->getSession();
      $thisUser = $session->get('user');

      if ($thisUser->getUserType() == 'A'){
        return $this->render('ClassMateBundle:User:admin.html.twig');
      }
       
      else{ 
    
        $session->set('errorMessage', "Please login." );
      
        return $this->redirect($this->generateUrl('class_mate_login'), 301);
      }

    }

    /**
     * Called by ajax from course_home page. This locates the selected files url and returns this documents data to the AJAX call which then submits 
     *           it via a form to their web browser which instantiates the download script native to their browser.
     * @param $fileName: the name of the file to download with out the final extension
     * @param $fileType: a string defining the final extension. We assume it is correct and do not check the data to make sure
     *
     * @return a Response containing the documents data and the headers describing how to download/open it. 
     */

    public function forceDownloadAction($fileType, $fileName, $courseID){
   
      if($fileType == 'pdf'){
        $headers = array(
      '  Content-Type' =>  "application/pdf", // $entity->getDocument()->getMimeType(),  "'.$entity->getDocument()->getName().'"'
        '  Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
      }

    if($fileType =='jpg'){
        $headers = array(
      '  Content-Type' =>  "application/pdf", // $entity->getDocument()->getMimeType(),  "'.$entity->getDocument()->getName().'"'
        '  Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
}
    /*
	.xlsx   application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
	.xltx   application/vnd.openxmlformats-officedocument.spreadsheetml.template
	.potx   application/vnd.openxmlformats-officedocument.presentationml.template
	.ppsx   application/vnd.openxmlformats-officedocument.presentationml.slideshow
	.pptx   application/vnd.openxmlformats-officedocument.presentationml.presentation
	.sldx   application/vnd.openxmlformats-officedocument.presentationml.slide
	.docx   application/vnd.openxmlformats-officedocument.wordprocessingml.document
	.dotx   application/vnd.openxmlformats-officedocument.wordprocessingml.template
	.xlam   application/vnd.ms-excel.addin.macroEnabled.12
	.xlsb   application/vnd.ms-excel.sheet.binary.macroEnabled.12
	
	application/xhtml+xml
	*/

    if($fileType == 'pdf'){
      $headers = array(
      'Content-Type' =>  "application/pdf", 
        'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
      );  
    }

    if($fileType =='jpg'){
        $headers = array(
        'Content-Type' =>  "application/pdf", 
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
     }

    if($fileType =='gzip'){
        $headers = array(
        'Content-Type' =>  "application/gzip",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
     }

    if($fileType =='zip'){
        $headers = array(
        'Content-Type' =>  "application/zip",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
    }

    if($fileType =='ogg'){
        $headers = array(
        'Content-Type' =>  "application/ogg",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
     }

     if($fileType =='pptx'){
        $headers = array(
         'Content-Type' =>  "application/vnd.openxmlformats-officedocument.presentationml.presentation",
          'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
      }

    if($fileType =='ppsx'){
        $headers = array(
        'Content-Type' =>  "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
     }

    if($fileType =='docx'){
        $headers = array(
        'Content-Type' =>  "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
          'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
    }
    
     if($fileType =='doc'){
        $headers = array(
        'Content-Type' =>  "application/msword",
          'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
    }

    if($fileType =='xml'){
        $headers = array(
        'Content-Type' =>  "application/xml",
        'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
     }

    if($fileType =='mpeg'){
        $headers = array(
        'Content-Type' =>  "audio.mpeg",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
    }

    if($fileType =='mp4'){
        $headers = array(
        'Content-Type' =>  "video/mp4",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
    }


    //includes divx
    if($fileType =='avi' || $fileType =='divx'){
        $headers = array(
        'Content-Type' =>  "video/avi",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
    }

    if($fileType =='webm'){
        $headers = array(
      '  Content-Type' =>  "video/webm",
        ' Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
    }

    if($fileType =='xml'){
        $headers = array(
        'Content-Type' =>  "text/xml",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
    }

    if($fileType =='gif'){
        $headers = array(
        'Content-Type' =>  "image/gif",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
     }

    if($fileType =='jpeg'){
        $headers = array(
        'Content-Type' =>  "image/jpeg",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
        );  
        
     }

    if($fileType =='png'){
        $headers = array(
        'Content-Type' =>  "image/png",
         'Content-Disposition' => 'attachment; filename="'.$fileName.'.'.$fileType.'"'
       );  
        
    }
        
    
    //$filename = $entity->->getfileLocation().'/'.$entity->getName();
        if($newFilename = 'media/'.$courseID.'/'.$fileName.'.'.$fileType)
 	{
          return new Response(file_get_contents($newFilename), 200, $headers);
        }
        return new Response();
    }









    /**
    *
    * @return renders the user_files.html page if a course is chosen, or renders the user_home page (where user can choose a course) if not. 
    */
    public function myNotesAction(){

        if($returnPage = $this->checkSession())
        {
            return $returnPage;
        }

        //gets current session, should contain current class and user
        $session=$this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
            
        $repository = $em->getRepository('ClassMateBundle:Archive'); 
        $userNotes= $repository -> findBy(array('archiveUserId' => $session->get('user')->getId(), 'fileType'=>'note'));

        $repository2 = $em->getRepository('ClassMateBundle:Archive'); 
        $userWikis= $repository2 -> findBy(array('archiveUserId' => $session->get('user')->getId(), 'fileType'=>'wiki'));

        $repository3 = $em->getRepository('ClassMateBundle:Enrollment'); 
        $listenCourses = $repository3 -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
 
            

        //Gives the user an error if they are not in any courses
        if($listenCourses){
            return $this->render('ClassMateBundle:User:my_notes.html.twig', array('userNotes' => $userNotes, 'userWikis' => $userWikis, 'listenCourses'=> $listenCourses));
        }

        $message = "Error - You must be enrolled as a listener in at least one course to create and view notes";

        $session->set('errorMessage', $message);
        return $this->redirect($this->generateUrl('class_mate_next'), 301);
        
    }











   /**
    *  NOT BEING USED???
    * @return renders the user_files.html page if a course is chosen, or renders the user_home page (where user can choose a course) if not. 
    */
/*
    public function userFilesAction(){

        if($returnPage = $this->checkSession())
        {
            return $returnPage;
        }

        $jots = [];
        $wikiExcerpts = [];
        $pdfs = [];
        $otherFiles = [];
        
            //gets current session, should contain current class and user
            $session=$this->getRequest()->getSession();

        //if there is no currently selected course, render the chooseCourse page again with an error
            if(!($found = $session->get('cc')))
        { 
            $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
                $listenCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
                $presentCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'P' ));
            return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'error' => "You must a choose a course to view files from.", 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
        }

        $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ClassMateBundle:Archive'); 
           
            //allFiles is set to all of the files for this user for the currently selected course.
            if(!($allFiles= $repository -> findBy(array('archiveUserId' => $session->get('user')->getId() , 'archiveCourseId' => $session->get('cc'))))){
                return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'error' => "There are no files to download for this course.", 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
            }

            //iterating through $allFiles we sort them into wiki, note, pdf, or all other file arrays. 

            //wikiExcerpts is set to all of the saved wiki excerpts for this user for the currently selected course.
            foreach ($allFiles as $fil) {
                if($fil->getFileType()=='note'){
                    $jots[]= $fil;
                }
                else if($fil->getFileType()=='wiki'){
                    $wikiExcerpts[]= $fil;
                }
                else if($fil->getFileType()=='pdf'){
                    $pdfs[]= $fil;
                }
                else 
                    $otherFiles[] = $fil;

            }

            //renders the user_files page supplying all of the notes for the currently selected class
          
            return $this->render('ClassMateBundle:User:user_files.html.twig', array('name'=>$session->get('user')->getFullName(), 'courseName' => $session->get('courseName'), 'archiveCourseId' => $session->get('cc'), 'pizza' => $session->get('user')->getId(), 'fileList'=> $otherFiles, 'pdfs'=>$pdfs));
    }
*/
   /**
    *
    * @return renders the user_notes.html page if a course is chosen, or renders the user_home page (where user can choose a course) if not. 
    */
/*
    public function usernotesAction(){
            if($returnPage = $this->checkSession())
    	{
                return $returnPage;
            }

            //gets current session, should contain current class and user
            $session=$this->getRequest()->getSession();

    	//if there is no currently selected course, render the chooseCourse page again with an error
            if(!($found = $session->get('cc')))
    	{ $em = $this->getDoctrine()->getManager();
                        $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
                        $listenCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
                        $presentCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'P' ));



                    return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'error' => "You must a choose a course to view notes from.", 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
                }

    	$em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('ClassMateBundle:Archive'); 
           
        	//jots is set to all of the custom notes for this user for the currently selected course.
            $jots = $repository -> findBy(array('archiveUserId' => $session->get('user')->getId() , 'fileType' => 'note' , 'archiveCourseId' => $session->get('cc') ));
            //wikiExcerpts is set to all of the saved wiki excerpts for this user for the currently selected course.
    	$wikiExcerpts = $repository -> findBy(array('archiveUserId' => $session->get('user')->getId() , 'fileType' => 'wiki' , 'archiveCourseId' => $session->get('cc')));

            //renders the my_notes page supplying all of the notes for the currently selected class
          
            return $this->render('ClassMateBundle:User:user_notes.html.twig', array('name'=>$session->get('user')->getFullName(), 'courseName' => $session->get('courseName'), 'archiveCourseId' => $session->get('cc'), 'pizza' => $session->get('user')->getId(), 'jots'=> $jots, 'wikiExcerpts'=>$wikiExcerpts));
    }
*/

  /*
    * AJAX called archiveWiki() and archiveNote() in app2.js. persists the desired note or wiki 
    *
    */
    public function archiveThisAction($courseID){
 
        $returnData = array();
        $returnData['wasSuccessful'] = "false";
        
        //checks to make sure the _Post was achieved via the hidden field 'name' of the button which submitted the information to post.
        if(empty($_POST['tempString'])){
            // Adds name to array
            $returnData['wasSuccessful'] = "false";
            echo json_encode($returnData); 
            exit();
     
        }
        
        else{
            $typeOfArc = $_POST['typeOf'];
            $f_NAME = ($_POST['name']);
        
            $session = $this->getRequest()->getSession();
            $noteToArchive = $_POST['tempString'];
            $u_ID = $session->get('user')->getId(); 
            $c_ID = $courseID;
            $f_TYPE = $typeOfArc;
            $dateCreated=date('Y-m-d H:i:s');      

            $arc = new Archive();

            $arc->setArchiveUserId($u_ID);

            $arc->setArchiveCourseId($c_ID);

            $arc->setFileName($f_NAME);
   
            $arc->setFileType($f_TYPE);

            $arc->setFileLocation($noteToArchive);

            $arc->setDateCreated(new \DateTime($dateCreated));
   
            $arc->setDateModified(new \DateTime($dateCreated));

            $em = $this->getDoctrine()->getManager();
            $em->persist($arc);
            $em->flush();    
            $returnData['wasSuccessful'] = "true";
            $returnData['code'] = 100;
            return new Response(json_encode($returnData)); 
            exit();
        }   
      
       
    }      
       
 
   /*
    *AJAX called searchWiki() in app2.js   retrieves the wikipedia page for search_term called through get_some path
    */ 

     public function getSomeWikiAction(){
       
        $textBuffer = "";
        $returnData = array();
        $returnData['wasSuccessful'] = "false";
        $charsWritten = 0;
        $maxCharsToWrite= 900;

        //checks to make sure the _Post was achieved via the hidden field 'name' of the button which submitted the information to post.
        if(empty($_POST['search_term'])){
            // Adds name to array
            $missingValues[] = 'Search Term';
            $returnData['wasSuccessful'] = "false";
            echo json_encode($returnData); 
            exit();
        }
        else {
            // Trim white space from the name and store the name
            $searchTerm = trim($_POST['search_term']);
            //replace any amount of spaces with underscores
            $searchTerm = preg_replace('/\s+/', '_', $searchTerm);

        }
    

        $searchURL = "http://en.wikipedia.org/wiki/" . $searchTerm;

        $file = @fopen($searchURL, "r");
        if(!($file)){
          $returnData['wasSuccessful'] = "false";
          echo json_encode($returnData); 

          exit();
        }
       //opens the file to write to %%%%%%%SHOULD BE a variant of searchTerm or timestamp$$$$$$$$
      // $writtenFile = fopen("testText.txt","w");



        if (is_null($file)) {
           // echo "the man";
          echo json_encode($returnData);
          exit();
        }
        else{
            //echo "the file is not null";
            $returnData['wasSuccessful'] = "true";
            //while the end of file is not reached...
            while (!feof($file))
            {
                //saves all strings fitting a <p> </p> tag before the next newline and puts the complete one in arrayOfParagraphs[0]
                preg_match("%(^(<p>)(.*)+?(</p>)$)%", fgets($file), $arrayOfParagraphs);    
                //if a match was found in the file...
                if (!(empty($arrayOfParagraphs))){
                    //sets stringToWrite to the next iterated paragraph needing to be written
                    $stringToWrite = $arrayOfParagraphs[0];
                    //removes html and tags
                    $stringToWrite = strip_tags($stringToWrite);
                    $stringToWrite = "&nbsp;&nbsp;&nbsp;&nbsp;" . $stringToWrite;
                    //if the string is non empty...
                    if(!(empty($stringToWrite))){   

                        //updates the total number of characters written after the write. 
                        $charsWritten = $charsWritten + strlen($stringToWrite);

                        //if potential 'charswritten" exceeds maxCharsToWRite %%%%SHOULD BE CONSTANT%%%%%%%: BREAK!
                
                        if($charsWritten > $maxCharsToWrite) {

                            //sets number of charsWritten back to the actual number of characters written to text buffer
                            $charsWritten = $charsWritten - strlen($stringToWrite);
                            //sets charsNeeded to the exact number of chars still needed to get to the desired num of chars
                            $charsNeeded = $maxCharsToWrite - $charsWritten;
                            //writes the exact number of characters needed to the tempString
                            $tempString = substr($stringToWrite, 0, $charsNeeded);
                            //appends the tempString to the textBuffer containing the text to return
                            $textBuffer = $textBuffer . $tempString; 
                            
                            break;
                        } 
                        else{
                        //writes the string to file with an empty line after it 
                            $textBuffer = $textBuffer . $stringToWrite. "<br />" ; 
                        //  fwrite($writtenFile, "$stringToWrite\n\n", 5000 );
                        }

                    }
                }
            }               
        }
        $wikSearch = new Wikisearches();

        $wikSearch->setUserId(1);
        $wikSearch->setTerm($searchTerm);
        $wikSearch->setText($textBuffer);

        $em = $this->getDoctrine()->getManager();
        $em->persist($wikSearch);
        $em->flush();    
         
        $returnData['searchTerm'] = $searchTerm;
        $returnData['data1'] = $textBuffer;
        $returnData['code'] = 100;
        return new Response(json_encode($returnData)); 
        exit();
      
    }     

  
    function uploadAction(){

    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }


    }

    public function joinCourseAction()
    {
    	$session=$this->getRequest()->getSession();

        if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }

        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            return $this->render('ClassMateBundle:User:join_course.html.twig', array('successMessage'=>$message));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
            return $this->render('ClassMateBundle:User:join_course.html.twig', array('errorMessage'=>$message));
        }
        else{
            return $this->render('ClassMateBundle:User:join_course.html.twig');
        }
 
        
    }

    public function joinCourseActionAction(Request $request)
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }
 
        //creates the session object
        $session=$this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Courses'); 
        $repository2 = $em->getRepository('ClassMateBundle:Enrollment');

        if($request->getMethod()=='POST'){
            $courseCode=$request->get('courseCode');


            if(!($course = $repository->findOneBy(array('courseCode'=>$courseCode)))){
                $message = "Error - This is not a valid course ID";

                $session->set('errorMessage', $message);
                return $this->redirect($this->generateUrl('class_mate_join_course'), 301);
	    }
            $courseID=$course->getCourseId();
            $courseName=$course->getCourseName();


            //Makes sure the user is not enrolled in the course already
            $oldEnrollment = $repository2->findOneBy(array('courseId'=>$courseID, 'userId'=>$session->get('user')->getId()));

            if(!$oldEnrollment){

                $enrollment = new Enrollment();

                $enrollment->setRole('L');
                $enrollment->setCourseId($courseID);
                $enrollment->setCourseName($courseName);
                $enrollment->setEnrolled('t');
                $enrollment->setUserID($session->get('user')->getId());

                

                $em = $this->getDoctrine()->getManager();
                $em->persist($enrollment);
                $em->flush();

                $message = "You have successfully joined ".$courseName;
                

                $session->set('successMessage', $message);
                return $this->redirect($this->generateUrl('class_mate_next'), 301);
            }

            $message = "Error - You are already enrolled in this class";
            

            $session->set('errorMessage', $message);
            return $this->redirect($this->generateUrl('class_mate_join_course'), 301);

        }


        return $this->render('ClassMateBundle:User:join_course.html.twig');
    }



    public function createCourseAction()
    {
    	if($returnPage = $this->checkSession())
    	{
            return $returnPage;
        }
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Courses'); 

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $strRand = '';
        for ($i = 0; $i < 6; $i++) {
            $strRand .= $characters[rand(0, strlen($characters) - 1)];
        }
        $course = $repository->findOneBy(array('courseCode'=>$strRand));

        

            while($course){
                $strRand = '';
                for ($i = 0; $i < 6; $i++) {
                    $strRand .= $characters[rand(0, strlen($characters) - 1)];
                }

                $course = $repository->findOneBy(array('courseCode'=>$strRand));
            }



        return $this->render('ClassMateBundle:User:create_course.html.twig', array('courseCode' => $strRand));
    }


    public function createCourseActionAction(Request $request)
    {
	if($returnPage = $this->checkSession())
	{
            return $returnPage;
        }

        //creates the session object
        $session=$this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Courses'); 

        if($request->getMethod()=='POST'){

            $courseCode = $request->get('courseCode');
            $courseName = $request->get('courseName');
            $dateCreated=date('Y-m-d H:i:s');
            
            //Creates a new course
            $course = new Courses();

            $course->setCourseName($courseName);
            $course->setCreatorUserId($courseCode);
            $course->setCourseCode($courseCode);
            $course->setDateCreated(new \DateTime($dateCreated));

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            //Gets the course id of the course we just created
            $course2 = $repository->findOneBy(array('courseCode'=>$courseCode));
            $courseID=$course2->getCourseId();
            

            //Inserts a record into the enrollment table
            $enrollment = new Enrollment();

            $enrollment->setRole('P');
            $enrollment->setCourseId($courseID);
            $enrollment->setCourseName($courseName);
            $enrollment->setEnrolled('t');
            $enrollment->setUserID($session->get('user')->getId());

            

            $em = $this->getDoctrine()->getManager();
            $em->persist($enrollment);
            $em->flush();

            $message = "Your course has been successfully created";
                

            $session->set('successMessage', $message);
        }

        return $this->redirect($this->generateUrl('class_mate_next'), 301);
    }

   /*
    * AJAX called by displayExcerpt() in javascript of user_notes.html.  retrieves selected note/wiki and returns as part of json_encoded array.
    *
    */

    public function displayExcerptAction(){
 
        $returnData = array();
        $arcId = $_POST['archiveId'];
        $arc = new Archive();
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:Archive'); 

        if($arc = $repository -> findOneBy(array('archiveId' => $arcId)))
        {

            $htmlString = $arc->getFileLocation();
            $returnData['innerH'] = $htmlString;
            return new Response(json_encode($returnData)); 
            exit();
        }
        else
        {
            $returnData['innerH'] = "We could not locate this wiki excerpt.";
            return new Response(json_encode($returnData)); 
            exit();
        }

    }


    

}

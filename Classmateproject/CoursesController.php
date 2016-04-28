<?php

namespace Project\ClassMateBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Project\ClassMateBundle\Entity\Courses;
use Project\ClassMateBundle\Form\CoursesType;


/**
 * Courses controller.
 *
 */
class CoursesController extends Controller
{

    /**
     * Lists all Courses entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ClassMateBundle:Courses')->findAll();

        return $this->render('ClassMateBundle:Courses:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    public function courseslistAction()
    {
      if($returnPage = $this->checkSession())
      {
            return $returnPage;
      } 

        $em = $this->getDoctrine()->getManager();
        $session=$this->getRequest()->getSession();

      $thisUser = $session->get('user');

      if ($thisUser->getUserType() != 'A'){
	$session->set('errorMessage', "Please login." );
        return $this->redirect($this->generateUrl('class_mate_login'), 301);
      }
    

        $entities = $em->getRepository('ClassMateBundle:Courses')->findAll();
        
        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            return $this->render('ClassMateBundle:Courses:list.html.twig', array('successMessage'=>$message, 'entities' => $entities));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
            return $this->render('ClassMateBundle:Courses:list.html.twig', array('errorMessage'=>$message, 'entities' => $entities));
        }

        return $this->render('ClassMateBundle:Courses:list.html.twig', array('entities' => $entities ));
    }

    /**
     * Creates a new Courses entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Courses();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $entity->upload();

            return $this->redirect($this->generateUrl('courses_show', array('id' => $entity->getCourseId())));
        }

        return $this->render('ClassMateBundle:Courses:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Courses entity.
     *
     * @param Courses $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Courses $entity)
    {
        $form = $this->createForm(new CoursesType(), $entity, array(
            'action' => $this->generateUrl('courses_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Courses entity.
     *
     */
    public function newAction()
    {
        $entity = new Courses();
        $form   = $this->createCreateForm($entity);

        return $this->render('ClassMateBundle:Courses:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Courses entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:Courses')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Courses entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:Courses:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Courses entity.
     *
     */
    public function editOldAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClassMateBundle:Courses')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Courses entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ClassMateBundle:Courses:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing Courses entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ClassMateBundle:Courses')->find($id);

        if (!$entity) {
            $message = "Error - Unable to find Course";

            $session->set('errorMessage', $message);
            return $this->redirect($this->generateUrl('class_mate_courses_list'), 301);
        }


        return $this->render('ClassMateBundle:Courses:edit.html.twig', array('entity'=> $entity));
    }


    /**
    * Creates a form to edit a Courses entity.
    *
    * @param Courses $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Courses $entity)
    {
        $form = $this->createForm(new CoursesType(), $entity, array(
            'action' => $this->generateUrl('courses_update', array('id' => $entity->getCourseId())),
            'method' => 'PUT',
        ));

       // $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }


    /**
     * Edits an existing Courses entity.
     *
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();

        if($request->getMethod()=='POST') {
            $courseName=$request->get('courseName');
            $courseId=$request->get('courseId');
            $dateModified=date('Y-m-d H:i:s');

            
            //$repository = $em->getRepository('ClassMateBundle:Courses');

            $course = $em->getRepository('ClassMateBundle:Courses')->findOneBy(array('courseId' => $courseId));

            if(!$course) {
                $message = "Error - Unable to update the course";

                $session->set('errorMessage', $message);
                return $this->redirect($this->generateUrl('class_mate_courses_list'), 301);
            }

            $course->setCourseName($courseName);
            $course->setDateModified(new \DateTime($dateModified));

            $em->flush();

            $message = "Your updates were saved successfully";

            $session->set('successMessage', $message);
            return $this->redirect($this->generateUrl('class_mate_courses_list'), 301);
        }

        $message = "Error - Something went wrong.  Unable to update the course";

        $session->set('errorMessage', $message);
        return $this->redirect($this->generateUrl('class_mate_courses_list'), 301);
    }


    /**
     * Deletes a Courses entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClassMateBundle:Courses')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Courses entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('courses'));
    }

    /**
     * Creates a form to delete a Courses entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('courses_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

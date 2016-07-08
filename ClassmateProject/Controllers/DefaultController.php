<?php

namespace Project\ClassMateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Project\ClassMateBundle\Entity\User;
use Project\ClassMateBundle\Models\Login;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $session=$this->getRequest()->getSession();
        
        if($session->has('successMessage')){
            $message=$session->get('successMessage');
            $session->remove('successMessage');
            return $this->render('ClassMateBundle:Default:home.html.twig', array('successMessage'=>$message));
        }
        elseif($session->has('errorMessage')){
            $message=$session->get('errorMessage');
            $session->remove('errorMessage');
            return $this->render('ClassMateBundle:Default:home.html.twig', array('errorMessage'=>$message));
        }

        return $this->render('ClassMateBundle:Default:home.html.twig');
    }


    public function loginAction(Request $request){
        $session=$this->getRequest()->getSession();
        
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

        return $this->render('ClassMateBundle:Default:login.html.twig', array('name'=> ''));
    }


    public function welcomeAction(Request $request)
    {
        //creates the session object
        $session=$this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:User'); 


        if($request->getMethod()=='POST'){
            $session->clear();            

            $username=$request->get('username');
            $password=sha1($request->get('password'));
            $remember=$request->get('remember');
        

        	$user = $repository->findOneBy(array('username'=>$username, 'password'=>$password));


        	if($user)
            {
                $session->set('user', $user);
                if($remember == 'remember-me')
                {
                    $login = new Login();
                    $login->setUsername($username);
                    $login->setPassword($password);
                    $session->set('login', $login);
                }
                //return $this->render('ClassMateBundle:Default:welcome.html.twig', array('sess'=>$session));
                return $this->render('ClassMateBundle:Default:welcome.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId()));
        	}
            else
            {
                return $this->render('ClassMateBundle:Default:login.html.twig', array('name'=> 'Login Error'));
            }
        }

    	else
        {
    		if($session->has('login'))
            {
                $login = $session->get('login');
                $username=$login->getUsername();
                $password=$login->getPassword();

                $user = $repository->findOneBy(array('username'=>$username, 'password'=>$password));

                if($user)
                {
                    return $this->render('ClassMateBundle:Default:welcome.html.twig', array('name'=>$user->getFullName()));
                }
            }

            return $this->render('ClassMateBundle:Default:login.html.twig');
    	}
    }

    //This is what is called when the user first logs in
    public function nextAction(Request $request)
    {
        //creates the session object
        $session=$this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:User'); 


        if($request->getMethod()=='POST'){
            $session->clear();            

            $username=$request->get('username');
            $password=sha1($request->get('password'));
            //$remember=$request->get('remember');
        

            $user = $repository->findOneBy(array('username'=>$username, 'password'=>$password));


            if($user)
            {
                $session->set('user', $user);
                //if($remember == 'remember-me')
                //{
                    $login = new Login();
                    $login->setUsername($username);
                    $login->setPassword($password);
                    $session->set('login', $login);
                //}
                //return $this->render('ClassMateBundle:Default:welcome.html.twig', array('sess'=>$session));

                    $em = $this->getDoctrine()->getManager();
                    $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
                    $listenCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
                    $presentCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'P' ));


                //Sets message alerts
                if($session->has('successMessage')){
                    $message=$session->get('successMessage');
                    $session->remove('successMessage');
                    return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses, 'successMessage'=>$message));
                }
                elseif($session->has('errorMessage')){
                    $message=$session->get('errorMessage');
                    $session->remove('errorMessage');
                    return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses, 'errorMessage'=>$message));
                }
                else{
                    return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
                }
            }
            else
            {
                $session->set('errorMessage', "Error - The Username or Password you entered was incorrect");
                return $this->redirect($this->generateUrl('class_mate_login'), 301);
            }
        }

        else
        {
            if($session->has('login'))
            {
                $login = $session->get('login');
                $username=$login->getUsername();
                $password=$login->getPassword();

                $user = $repository->findOneBy(array('username'=>$username, 'password'=>$password));

                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('ClassMateBundle:Enrollment'); 
                $listenCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'L' ));
                $presentCourses = $repository -> findBy(array('userId' => $session->get('user')->getId() , 'role' => 'P' ));

                if($user)
                {
                    
                     //Sets message alerts
                    if($session->has('successMessage')){
                        $message=$session->get('successMessage');
                        $session->remove('successMessage');
                        return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses, 'successMessage'=>$message));
                    }
                    elseif($session->has('errorMessage')){
                        $message=$session->get('errorMessage');
                        $session->remove('errorMessage');
                        return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses, 'errorMessage'=>$message));
                    }
                    else{
                        return $this->render('ClassMateBundle:User:choosecourse.html.twig', array('name'=>$session->get('user')->getFullName(), 'pizza' => $session->get('user')->getId(), 'listenCourses'=>$listenCourses, 'presentCourses'=>$presentCourses));
                    }

                }
            }

            $session->set('errorMessage', "Error - The Username or Password you entered was incorrect");
            return $this->redirect($this->generateUrl('class_mate_login'), 301);
        }
    }


    public function signupAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('ClassMateBundle:User');

        if($request->getMethod()=='POST'){
            $firstname=$request->get('firstname');
            $lastname=$request->get('lastname');
            $email=$request->get('email');
            $accessLevel=$request->get('access_level');
            $username=$request->get('username');
            $password=$request->get('password');
            $confirm_password=$request->get('confirm_password');
            $dateCreated=date('Y-m-d H:i:s');



            //if the email is already registered with a username
            $emailCheck = $repository->findOneBy(array('email'=>$email));

            if($emailCheck){
                return $this->render('ClassMateBundle:Default:signup.html.twig', array('errorMessage'=>"Error - The email address that you entered is already registered as a user"));
            }

            //if the username is taken
            $usernameCheck = $repository->findOneBy(array('username'=>$username));

            if($usernameCheck){
                return $this->render('ClassMateBundle:Default:signup.html.twig', array('errorMessage'=>"Error - The Username you entered is already in use"));
            }

            $user = new User();

            $user->setFirstName($firstname);
            $user->setLastName($lastname);
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword(sha1($password));
            $user->setUserType($accessLevel);
            $user->setDateCreated(new \DateTime($dateCreated));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //return $this->render('ClassMateBundle:Default:login.html.twig', array('successMessage'=>"Your account has been created successfully!"));

            $session=$this->getRequest()->getSession();
        
            $session->set('successMessage', "Your account has been created successfully!");
            return $this->redirect($this->generateUrl('class_mate_login'), 301);
        }

        return $this->render('ClassMateBundle:Default:signup.html.twig');
    }


    public function logoutAction(Request $request)
    {
        $session=$this->getRequest()->getSession();
        $session->clear(); 

        //return $this->render('ClassMateBundle:Default:login.html.twig');
        return $this->redirect($this->generateUrl('class_mate_login'), 301);
    }
}
















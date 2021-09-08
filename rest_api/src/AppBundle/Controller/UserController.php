<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;

class UserController extends Controller
{

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
        ->find($request->get('id'));
        /* @var $user User */
        
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/register")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $user->setCreated(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->findAll();
        /* @var $users User[] */
        
        return $users;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{user_id}")
     */
    public function getUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->find($request->get('user_id'));
        /* @var $user User */
        
        if (empty($user)) {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        return $user;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    /**
     * @Rest\View()
     * @Rest\Put("/users/{id}");
     */
    public function putUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($request->get('id'));
        $user->setModificationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        /* @var $user User */

        if (empty($user)) {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
}
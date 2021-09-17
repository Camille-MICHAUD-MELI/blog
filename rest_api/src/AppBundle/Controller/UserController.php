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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT, serializerGroups={"user"})
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
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/register")
     */
    public function postUsersAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setCreated(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all()); // Validation des données

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $encoded = $encoder->encodePassword($user, $request->get('password'));
            $user->setPassword($encoded);
            $em->persist($user);
            $em->flush();
            $response = new JsonResponse($user);
            $response->setStatusCode(JsonResponse::HTTP_OK);
            return $response;
        } else {
            $response = new JsonResponse($user);
            $response->setStatusCode(JsonResponse::HTTP_CONFLICT);
            return $response;
        }
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/login")
     */
    public function postLoginAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->findBy(['username' => $request->get('username'), 'password' => $request->get('password')]);

        if (empty($user)) {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        } else {
            if ($user) {
                $response = new JsonResponse();
                $response->setStatusCode(JsonResponse::HTTP_OK);
                return $response;
            }
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK, serializerGroups={"user"})
     * @Rest\Get("/gettokenuser")
     */
    public function getUsertokeAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')
        ->getToken();

        return $user;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
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
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{user_id}")
     */
    public function getUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->find($request->get('user_id'));
        /* @var $user User */
        VarDumper::dump($user->getRoles());
        exit();
        
        if (empty($user)) {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        
        return $user;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
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
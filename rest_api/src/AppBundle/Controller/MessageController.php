<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Form\Type\MessageType;
use AppBundle\Entity\Message;
use AppBundle\Form\Type\CommentType;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;

class MessageController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/message-comment/{message_id}")
     */
    public function getMessageandcommentAction(Request $request)
    {
        $message = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Message')
        ->find($request->get('message_id'));
        /* @var $message Message */
        $comment = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Comment')
        ->findBy(['user' => $request->get('message_id')]);
        /* @var $comment Comment */
        
        if (empty($message)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }

        $results = array('message' => $message,
                         'comment' => $comment);
            
        if (empty($comment)) {
            return ($message);
        } else {
            return $results;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/message/{id}/{user_id}")
     */
    public function removeMessageAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $message = $em->getRepository('AppBundle:Message')
        ->find($request->get('id'));
        /* @var $message Message */
        $user = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:User')
        ->find($request->get('user_id'));

        if (($message->getId() == $user->getId()) || ($user->getAdmin() == 1)) {
            if ($message) {
                $em->remove($message);
                $em->flush();
            }
        } else {
            return \FOS\RestBundle\View\View::create(['message' => 'Permission denied'], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/messagepost")
     */
    public function postMessageAction(Request $request)
    {
        $message = new Message();
        $message->setPublicationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        $form = $this->createForm(MessageType::class, $message);
        $form->submit($request->request->all());
        $user = $this->container->get('security.token_storage')
        ->getToken()
        ->getUser();

        if ($form->isValid()) {
            $message->setUser($user);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($message);
            $em->flush();
            $response = new JsonResponse($message);
            $response->setStatusCode(JsonResponse::HTTP_CREATED);
            return $response;
        } else {
            $response = new JsonResponse($message);
            $response->setStatusCode(JsonResponse::HTTP_CONFLICT);
            return $response;
        }
    }

    /**
     * @Rest\View()
     * @Rest\Get("/message")
     */
    public function getMessagesAction(Request $request)
    {
        $message = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Message')
        ->findAll();
        /* @var $message Message[] */
        
        return $message;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/message/{message_id}")
     */
    public function getMessageAction(Request $request)
    {
        $message = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Message')
        ->find($request->get('message_id'));
        /* @var $message Message */
        
        if (empty($message)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }
        
        return $message;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/message/{id}")
     */
    public function patchMessageAction(Request $request)
    {
        return $this->updateMessage($request, false);
    }

    private function updateMessage(Request $request, $clearMissing)
    {
        $message = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Message')
                ->find($request->get('id'));
        $message->setModificationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        /* @var $message Message */

        if (empty($message)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Message not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(MessageType::class, $message);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($message);
            $em->flush();
            return $message;
        } else {
            return $form;
        }
    }

}

<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\VarDumper\VarDumper;
use AppBundle\Form\Type\LikeType;
use AppBundle\Entity\Like;

class LikeController extends Controller
{
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/message")
     */
    public function removeLikeAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $message = $em->getRepository('AppBundle:Message')
        ->find($request->get('id'));
        /* @var $like Like */

        if ($like) {
            $em->remove($like);
            $em->flush();
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/)
     */
}
<?php

namespace CoMiDolegaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController extends FOSRestController
{
    /**
    *
    *   @Post("/register")
    *
    */
    public function newUserAction(Request $request)
    {
        $data = $request->getContent();

        $user = $this->get('jms_serializer')->deserialize($data,'CoMiDolegaBundle\Entity\User','json');

        $repository = $this->getDoctrine()->getRepository("CoMiDolegaBundle:User");

        $checkuser = $repository->findOneBy(['username' => $user->getUsername()]);

        if(!empty($checkuser))
        {
            $response=array(

                'code'=>1,
                'message'=>'User found!',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();


        $response=array(

            'code'=>0,
            'message'=>'User created!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,Response::HTTP_CREATED);
    }
}

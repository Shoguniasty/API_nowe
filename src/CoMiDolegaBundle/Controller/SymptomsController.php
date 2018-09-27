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

class SymptomsController extends FOSRestController
{
    /**
    *
    *   @Post("/symptoms")
    *
    */
    public function newSymptomsAction(Request $request)
    {
        $data = $request->getContent();

        $symptoms = $this->get('jms_serializer')->deserialize($data,'CoMiDolegaBundle\Entity\Symptoms','json');

        $repository = $this->getDoctrine()->getRepository("CoMiDolegaBundle:Symptoms");

        $checksymptom = $repository->findOneBy(['symptoms' => $symptoms->getSymptomName()]);

        if(!empty($checksymptom))
        {
            $response=array(

                'code'=>1,
                'message'=>'Symptoms found!',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($symptoms);
        $em->flush();

        $response=array(

            'code'=>0,
            'message'=>'Symptoms created!',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,Response::HTTP_CREATED);
    }

    /**
    *
    *   @Post("/checksymptoms")
    *
    */
    public function symptomsAction(Request $request)
    {
        $data = $request->getContent();

        if(empty($data))
        {
            $response = [
                $data
            ];

            $response=array(

                'code'=>1,
                'message'=>'Nie podałeś symptomów',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $response = array(
            'code' => 0,
            'disease' => 'Grypa',
            'hint' => 'Musisz leżeć w łóżku, zalecamy wybrać się do swojego lekarza rodzinnego',
            'errors' => null,
            'result' => null
        );

        return new JsonResponse($response,Response::HTTP_CREATED);
    }

    /**
    *  
    *  @Get("/symptomslist")
    * 
    */
    public function symptomsListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
        'SELECT s.symptoms
        FROM CoMiDolegaBundle\Entity\Symptoms s');

        $symptoms = $query->getResult();

        if (empty($symptoms)){
            $response=array(
                'code'=>1,
                'message'=>'Symptoms not found!',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data=$this->get('jms_serializer')->serialize($symptoms,'json');

        $response=array(

            'code'=>0,

            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)

        );

        return new JsonResponse($response,200);
    }
}

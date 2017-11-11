<?php

namespace Marvel\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

class BaseController extends Controller
{

    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $em;
    protected $router;

    /**
     * @var \DateTime $timeInit
     */
    protected $timeInit;

    /**
     * @var array $listImageExt
     */
    protected $listImageExt = array(
        'image/gif' => 'gif',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    );

    /**
     * @var array $listUrlMethod
     */
    protected $listUrlMethod = array('GET', 'POST', 'PATCH', 'DELETE');

    /**
     * @var array $dataTemplate
     */
    protected $dataTemplate = array();

    protected function init()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->em = $this->container->get('doctrine')->getManager();
        $this->router = $this->container->get("router");
        $this->timeInit = new \DateTime;
    }

    protected function errorResponse($userMessage, $httpStatusCode = 400, array $customHeader = array(), $format = 'json'){
        switch ($format) {
            case 'json':
                $return = new JsonResponse(array(
                    'message' => $userMessage,
                    'success' => false,
                    'timestamp' => new \DateTime()
                ), $httpStatusCode, $customHeader);
                break;
        }

        return $return;
    }

    protected function successResponse(array $param, $httpStatusCode = 200, array $customHeader = array(), $format = 'json'){
        
        switch ($format) {
            case 'json':
                $param['timestamp'] = new \DateTime;
                $param['success'] = true;

                $return = new JsonResponse($param, $httpStatusCode, $customHeader);
                break;
        }

        return $return;
    }

}

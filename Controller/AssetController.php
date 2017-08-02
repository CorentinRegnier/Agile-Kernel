<?php

namespace AgileKernelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssetController
 *
 * @package AgileKernelBundle\Controller
 */
class AssetController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function jsAction(Request $request)
    {
        $views = $this->get('agile_kernel.templating.assets.stack')->getJavascriptViews();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');
        $maxAge = 3600 * 3;
        $response->setCache([
            'max_age'  => $maxAge,
            's_maxage' => $maxAge,
            'public'   => true,
        ]);

        return $this->render('AgileKernelBundle:asset:all.js.twig', [
            'views' => $views,
        ], $response);
    }
}

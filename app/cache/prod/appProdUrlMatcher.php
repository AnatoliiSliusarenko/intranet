<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);
        $context = $this->context;
        $request = $this->request;

        // intranet_main_homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'intranet_main_homepage');
            }

            return array (  '_controller' => 'Intranet\\MainBundle\\Controller\\IndexController::indexAction',  '_route' => 'intranet_main_homepage',);
        }

        // intranet_show_section
        if (0 === strpos($pathinfo, '/section') && preg_match('#^/section/(?P<section_id>[^/]++)/?$#s', $pathinfo, $matches)) {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'intranet_show_section');
            }

            return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_show_section')), array (  '_controller' => 'Intranet\\MainBundle\\Controller\\IndexController::showSectionAction',));
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}

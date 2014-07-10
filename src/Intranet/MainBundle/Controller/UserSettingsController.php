<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserSettingsController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntranetMainBundle:UserSettings:showSettings.html.twig');
    }
}

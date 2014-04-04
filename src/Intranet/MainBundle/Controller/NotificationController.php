<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotificationController extends Controller
{
    public function showNotificationsAction()
    {
        return $this->render('IntranetMainBundle:Notification:showNotifications.html.twig');
    }
}

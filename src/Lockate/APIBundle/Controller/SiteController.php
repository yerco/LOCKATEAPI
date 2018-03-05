<?php
namespace Lockate\APIBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller
{
    public function insideAction() {
        return $this->render('@LockateAPI/Default/inside.html.twig');
    }
}

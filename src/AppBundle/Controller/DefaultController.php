<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Service\FilterConstructor;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="all")
     */
    public function indexAction(Request $request, ObjectManager $em)
    {
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('default/userlist.twig', [
            'title' => 'All users',
            'users' => $users
        ]);
    }

    /**
     * @Route("/filter", name="filter")
     */
    public function filterAction(ObjectManager $em, FilterConstructor $fc)
    {
        $userRepository = $em->getRepository(User::class);

        $filter = $fc->eq('country', 'Albania');

        $users = $userRepository->findByFilter($filter);

        return $this->render('default/userlist.twig', [
            'title' => 'Filtered users',
            'users' => $users
        ]);
    }
}

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
    public function filterAction(Request $request, ObjectManager $em, FilterConstructor $fc)
    {
        $userRepository = $em->getRepository(User::class);

        switch ($request->get('id')) {
            case 1:
                $title = "(ID = 10) ИЛИ (Страна != Россия)";
                $filter = $fc->orX(
                    $fc->eq('id', 10),
                    $fc->neq('country', 'Россия')
                );
                break;
            case 2:
                $title = "(Страна = Россия) И (Состояние пользователя != active)";
                $filter = $fc->andX(
                    $fc->eq('country', 'Россия'),
                    $fc->neq('state', 'active')
                );
                break;
            case 3:
                $title = "(((Страна != Россия) ИЛИ (Состояние пользователя = active)) И (E-Mail = reichel.zetta@hotmail.com)) ИЛИ (Имя != Юра)";
                $filter = $fc->orX(
                    $fc->andX(
                        $fc->orX(
                            $fc->neq('country', 'Россия'),
                            $fc->eq('state', 'active')
                        ),
                        $fc->eq('email', 'reichel.zetta@hotmail.com')
                    ),
                    $fc->neq('firstname', 'Юра')
                );
                break;
        }

        $users = $userRepository->findByFilter($filter);

        return $this->render('default/userlist.twig', [
            'title' => $title,
            'users' => $users
        ]);
    }
}

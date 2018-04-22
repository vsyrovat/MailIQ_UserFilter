<?php declare(strict_types=1);

namespace AppBundle\Service;

use AppBundle\Entity\UserAbout;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilterConstructorTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->qb = $this->em->createQueryBuilder();
    }
    
    protected function fc()
    {
        return new FilterConstructor($this->em);
    }

    public function testEq()
    {
        self::assertEquals("u.id = '123'", $this->fc()->eq('id', 123)->__toString());
        self::assertEquals("u.email = 'vasya@pupkin.com'", $this->fc()->eq('email', 'vasya@pupkin.com')->__toString());
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='country') = 'Россия'",
            $this->fc()->eq('country', 'Россия')->__toString()
        );
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='firstname') = 'Иван'",
            $this->fc()->eq('firstname', 'Иван')->__toString()
        );
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='state') = 'active'",
            $this->fc()->eq('state', 'active')->__toString()
        );
    }

    public function testNeq()
    {
        self::assertEquals("u.id <> '123'", $this->fc()->neq('id', 123)->__toString());
        self::assertEquals("u.email <> 'vasya@pupkin.com'", $this->fc()->neq('email', 'vasya@pupkin.com')->__toString());
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='country') <> 'Россия'",
            $this->fc()->neq('country', 'Россия')->__toString()
        );
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='firstname') <> 'Иван'",
            $this->fc()->neq('firstname', 'Иван')->__toString()
        );
        self::assertEquals(
            "(SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='state') <> 'active'",
            $this->fc()->neq('state', 'active')->__toString()
        );
    }

    public function testAnd()
    {
        $fc = $this->fc();
        self::assertEquals(
            "u.id = '123' AND u.email <> 'vasya@pupkin.net'",
            $fc->andX(
                $fc->eq('id', 123),
                $fc->neq('email', 'vasya@pupkin.net')
            )->__toString()
        );

        $fc = $this->fc();
        self::assertEquals(
            "(u.id = '123'"
            ." AND ((SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='state') <> 'active')"
            .") AND ((SELECT ua1.value FROM ".UserAbout::class." ua1 WHERE ua1.user=u.id AND ua1.item='country') <> 'Россия')",
            $fc->andX(
                $fc->andX(
                    $fc->eq('id', 123),
                    $fc->neq('state', 'active')
                ),
                $fc->neq('country', 'Россия')
            )->__toString()
        );
    }

    public function testOr()
    {
        $fc = $this->fc();
        self::assertEquals(
            "u.id = '123' OR u.email <> 'vasya@pupkin.net'",
            $fc->orX(
                $fc->eq('id', 123),
                $fc->neq('email', 'vasya@pupkin.net')
            )->__toString()
        );

        $fc = $this->fc();
        self::assertEquals(
            "(u.id = '123'"
            ." OR ((SELECT ua0.value FROM ".UserAbout::class." ua0 WHERE ua0.user=u.id AND ua0.item='state') <> 'active')"
            .") OR ((SELECT ua1.value FROM ".UserAbout::class." ua1 WHERE ua1.user=u.id AND ua1.item='country') <> 'Россия')",
            $fc->orX(
                $fc->orX(
                    $fc->eq('id', 123),
                    $fc->neq('state', 'active')
                ),
                $fc->neq('country', 'Россия')
            )->__toString()
        );
    }
}

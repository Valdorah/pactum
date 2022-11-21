<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public const USER_REFERENCE = 'user';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $roles = array('ROLE_ADMIN');

        $user = new User();
        $user->setUserName('Alexis');
        $user->setDescription('Etudiant en licence professionnelle : Développement d\'application web.');
        $user->setAvatar('https://gitlab.iut-clermont.uca.fr/uploads/-/system/user/avatar/44/avatar.png?width=400');
        $user->setEmail('alexisgoncalves1811@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '4815162342'
        ));
        $user->setRoles($roles);

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}

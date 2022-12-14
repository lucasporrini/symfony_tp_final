<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $annonce = new Annonce();
            $annonce->setTitle('Ceci est le titre de ma '.($i+1).'ème annonce ');
            $annonce->setDescription('Ceci est la description de ma '.($i+1).'ème annonce');
            $annonce->setPrice(mt_rand(10, 100));
            $annonce->setCreatedAt(new \DateTime());
            $annonce->setUpdatedAt(new \DateTime());
            // $annonce->setUser($this->getReference('user'));
            // $annonce->setCategorie($this->getReference('categorie'));
            $manager->persist($annonce);
        }
        $manager->flush();
    }
}

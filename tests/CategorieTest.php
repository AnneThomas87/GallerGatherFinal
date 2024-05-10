<?php

namespace App\Tests;

use App\Entity\Categorie;
use App\Entity\SamplingMethods;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieTest extends KernelTestCase
{ // Méthode de test pour un nom valide
    public function testValidName(): void
    { // Démarrer le kernel de Symfony
        self::bootKernel();
        // Récupérer le conteneur de services de l'application
        $container = static::getContainer();
        // Créer une nouvelle instance de l'entité SamplingMethods
        $categorie  = new Categorie();
        // Définir le nom Categorie comme 'spectacle'
        $categorie->setNom('Spectacle');
        // Valider l'entité avec le validateur de Symfony
        $errors = $container->get('validator')->validate($categorie);
        // Vérifier que le nombre d'erreurs est égal à zéro
        $this->assertCount(0, $errors);
    }
    // Méthode de test pour un nom invalide
    public function testInvalidName(): void
    { // Démarrer le kernel de Symfony
        self::bootKernel();
        // Récupérer le conteneur de services de l'application
        $container = static::getContainer();
        // Créer une nouvelle instance de l'entité Categorie
        $categorie  = new Categorie();
        // Définir le nom de la méthode d'échantillonnage comme une chaîne vide
        $categorie->setNom('');
        // Vérifier que le nombre d'erreurs est égal à 1
        $errors = $container->get('validator')->validate($categorie);
        $this->assertCount(1, $errors);
    }
}

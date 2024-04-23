<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Profil;
use App\Form\MediaType;
use App\Form\ProfilFormType;
use App\Repository\MediaRepository;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/pro', name: 'app_pro_'), IsGranted('ROLE_PRO')]
class ProController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {

        $user = $this->getUser();
        if (empty($user->getProfil())) {
            return $this->redirectToRoute('app_pro_create_profil');
        }
        // Check si user->getLieu() === vide 
        // redirect user vers la création du lieu (formulaire)

         return $this->render('index/index.html.twig', [
            'controller_name' => 'ProController',
        ]);
    }

  #[Route('/ajouter-un-profil', name: 'create_profil')]
    public function addProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profil = new Profil();
        $form = $this->createForm(ProfilFormType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profil->setArtiste($this->getUser());
    
            $entityManager->persist($profil);
            $entityManager->flush();

            // Rediriger l'utilisateur après la soumission du formulaire
            return $this->redirectToRoute('app_pro_create_media', ['id' => $profil->getId()]);
        }

        return $this->render('pro/profil-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

#[Route('/create-media/{id}', name: 'create_media')]
public function media(EntityManagerInterface $em, Request $request,int $id,ProfilRepository $profilRepo)
{
     $media = new Media();
     $profil =  $profilRepo->findOneBy(['id' => $id]);

      $form = $this->createForm(MediaType::class,$media);


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $images = $form->get('mediaLieu')->getData();

        foreach($images as $image)
        {
           $ext = $image->guessExtension();

            $folder = $this->getParameter('media_lieu_public');
            $filename = bin2hex(random_bytes(20)) . '.' . $ext ;
            $image->move($folder, $filename);
            $media->setMedia($this->getParameter('media_lieu') . '/' . $filename);
            $media->setProfil($profil);
            $em->persist($media);
            $em->flush();
        }
   

        $this->addFlash('success', 'Vous avez ajoutez vos images');
       
    
    }
    return $this->render('media/profilMedia.html.twig', [
        'form' => $form->createView(),
        'profil' => $profil
    ]);
}

#[Route(path: '/artiste/{id}', name: 'proView')]
public function artiste(UserRepository $userRepos,MediaRepository $mediaRepo,ProfilRepository $profilRepo,int $id)
{


    $user = $this->getUser();
    $userId = $user->getId();


     $user = $userRepos->find($id);


     $profilId = $profilRepo->findOneBy(['artiste' => $user]);

     $medias = $mediaRepo->findBy(['profil' => $profilId]);


    $user =  $userRepos->findBy(['id' => $userId]);
    return $this->render('pro/proView.html.twig',[
        'users' => $user,
        'medias' => $medias

    ]);



}


#[Route(path: '/editProfilMedia/{id}', name: 'editProfilMedia')]
public function editMedia (UserRepository $userRepos,ProfilRepository $profilRepo    ,Request $request,EntityManagerInterface $em,MediaRepository $mediaRepos,int $id)
{



    $user = $this->getUser();
    $userId = $user->getId();


     $user = $userRepos->find($id);


     $profilId = $profilRepo->findOneBy(['artiste' => $userId]);

     $images = $mediaRepos->findBy(['profil' => $profilId]);


    
    $this->addFlash('success','Vous avez bien modifiez vos medias');
    
    return $this->render('pro/editProfilMedia.html.twig',[
       'images' => $images
    ]);
}



#[Route(path: '/deleteMedia/{id}', name: 'deleteProMedia')]
    public function deleteMedia (EntityManagerInterface $em,int $id,MediaRepository $mediaRepos)
    {


        $media =  $mediaRepos->find($id);

        $user = $this->getUser();
        $userId = $user->getId();

        if ($user) {
            $em->remove($media);
            $em->flush();
            return $this->redirectToRoute('app_pro_proView', [ 'id' => $userId]);
        }

       
        

        
        $this->addFlash('success','Vous avez bien modifiez vos medias');
    
    }
}


?>
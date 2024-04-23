<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Media;
use App\Form\MediaType;
use App\Form\LieuFormType;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;

#[Route('/orga', name: 'app_orga_'), IsGranted('ROLE_ORGA')]
class OrgaController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {

        $user = $this->getUser();
        if (empty($user->getLieu())) {
            return $this->redirectToRoute('app_orga_create_place');
        }
        // Check si user->getLieu() === vide 
        // redirect user vers la création du lieu (formulaire)

         return $this->render('index/index.html.twig', [
            'controller_name' => 'OrgaController',
        ]);
    }

  #[Route('/ajouter-un-lieu', name: 'create_place')]
    public function addPlace(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuFormType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieu->setOrganisateur($this->getUser());
            
            $entityManager->persist($lieu);
            $entityManager->flush();

            // Rediriger l'utilisateur après la soumission du formulaire
            return $this->redirectToRoute('app_orga_create_media', ['id' => $lieu->getId()]);
        }
        return $this->render('orga/place-form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/create-media/{id}', name: 'create_media')]
    public function media(Request $request,EntityManagerInterface $em,LieuRepository $lieuRepo,int $id)
    {
         $media = new Media();




         $lieu =  $lieuRepo->findOneBy(['id' => $id]);


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
                $media->setLieu($lieu);
                $em->persist($media);
                $em->flush();
            }
       

            $this->addFlash('success', 'Vous avez ajoutez vos images');
           
        
        }
        return $this->render('media/media.html.twig', [
            'form' => $form->createView(),
            'lieu' => $lieu
        ]);
    }

    #[Route(path: '/organisateur/{id}', name: 'orgaView')]
    public function organisateur (MediaRepository $mediaRepos,int $id,UserRepository $userRepos){


        $user = $this->getUser();
        $userId = $user->getId();

        $user =  $userRepos->findBy(['id' => $userId]);
        return $this->render('orga/orgaView.html.twig',[
            'users' => $user
        ]);

    }
    #[Route(path: '/editProfil/{id}', name: 'editProfil')]
    public function edit (UserRepository $userRepos, int $id,LieuRepository $lieuRepo,Request $request,EntityManagerInterface $em, MediaRepository $mediaRepos)
    {


        $user = $this->getUser();
        $userId = $user->getId();

        $user =  $userRepos->findBy(['id' => $userId]);


        $lieu = $lieuRepo->findOneBy(['id' => $id ]);

       $form = $this->createForm(LieuFormType::class,$lieu);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em->flush();
            return $this->redirectToRoute('app_orga_orgaView', [ 'id' => $userId]);
        }

        $this->addFlash('success','Vous avez bien modifiez vos infos');
        

    
    
        return $this->render('orga/editProfil.html.twig',[
           'form' => $form
        ]);
    }
    #[Route(path: '/editMedia/{id}', name: 'editMedia')]
    public function editMedia (UserRepository $userRepos,Request $request,EntityManagerInterface $em,MediaRepository $mediaRepos,int $id)
    {


        $user = $this->getUser();
        $userId = $user->getId();


        $user =  $userRepos->findBy(['id' => $userId]);
        

            $images =  $mediaRepos->findBy(['id' => $id]) ;
        
        $this->addFlash('success','Vous avez bien modifiez vos medias');
        

    
    
        return $this->render('orga/editMedia.html.twig',[
           'images' => $images
        ]);
    }
    #[Route(path: '/deleteMedia/{id}', name: 'deleteMedia')]
    public function deleteMedia (EntityManagerInterface $em,int $id,MediaRepository $mediaRepos)
    {


        $media =  $mediaRepos->find($id);

        $user = $this->getUser();
        $userId = $user->getId();

        if ($user) {
            $em->remove($media);
            $em->flush();
            return $this->redirectToRoute('app_orga_orgaView', [ 'id' => $userId]);
        }

       
        

        
        $this->addFlash('success','Vous avez bien supprimez votre medias');
    
    }
}

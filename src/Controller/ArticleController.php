<?php

namespace App\Controller;

/**  rajout des deux USE commentaires pour inclure cela dans l'article**/
use App\Entity\Commentaire;
use App\Form\CommentaireType;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {   
        // rajout perso, pour forcer la connexion utilisateur pour ne pas voir la liste si on est pas connecté
        if ( !$this->getUser()){

            $this->addFlash('error','Vous devez être connecté pour accéder à cette page');

            return $this->redirectToRoute('app_login');
        }
        else{
            
            /** rajout pour afficher juste quelques articles et pas tous**/
            $entityManager = $this->getDoctrine()->getManager();

            // affichage de deux articles seulement
            $articles = $entityManager->getRepository(Article::class)->getLastInserted('App:Article', 2);

            //var_dump($articles);die;

            return $this->render('article/index.html.twig',['articles' => $articles]);
            /****/
            
            // avant, cela etait : return $this->render('article/index.html.twig',['articles' => $articleRepository->findAll(),]);
        }
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {   
        if ( !$this->getUser()){

            $this->addFlash('error','Vous devez être connecté pour accéder à cette page');
            
            return $this->redirectToRoute('article_index');
        }
        
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // on dit que le nouvel article sera en lien avec l'id courant du user / dans PHPMyAdmin cela rajoute un user_id
            $article->setUser($this->getUser());

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success','Bravo! votre article est bien publié');

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET", "POST"})
     */
    public function show(Article $article, Request $request): Response
    // rajout de Request $request provenant du CommentaireController + rajout de "POST" dans la méthode ci-dessus

    {
        /**Ajout du formulaire commentaire **/

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        // on sort cette entity du if pour pouvoir l'utiliser
        $entityManager = $this->getDoctrine()->getManager();

        /** Rajout de la condition provenant de CommentaireController **/
        if ($form->isSubmitted() && $form->isValid()) {
            

            // on connecte le commentaire à l'utlisateur connecté, utilisateur courant
            $commentaire->setUser($this->getUser());

            // rajouter setter article pour faire le mapping
            $commentaire->setArticle($article);

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('article_show', array('id' =>$article->getId()));
        }

        // creation d'une variable pour afficher les commentaires
        $commentaires = $entityManager->getRepository('App:Commentaire')->findBy(["article" => $article]);

        /****/

        return $this->render('article/show.html.twig', [
            'article' => $article,
            // rajout affichage du formulaire provenant de CommentaireController:
            'form' => $form->createView(),
            // rajout variable
            'commentaires' =>$commentaires,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}

<?php
namespace Drupal\cp_blog_post\Controller;
use Drupal;
use Drupal\Core\Controller\ControllerBase;
/**
 * An example controller.
 */
class BlogController extends ControllerBase
{
    /**
     * Returns a render-able array for a test page.
     */
    public function content()
    {
        $limit = 5;
        $build = [
            '#markup' => '<p>' . $this->t('Pas de résultat') . '</p>', // $this->t() permet de traduire le contenu !important
        ];
        $entityTypeManager = Drupal::entityTypeManager();
        $nodeStorage = $entityTypeManager->getStorage('node');
        $query = $nodeStorage->getQuery();
        $query
            ->condition('type', 'blog_post')
            ->condition('status', true)
            ->sort('changed', 'DESC');
        $query->pager($limit);
        $nids = $query->execute();
        if (!empty($nids)) {
            $nodeObjects = $nodeStorage->loadMultiple($nids);
            $nodeViewBuilder = $entityTypeManager->getViewBuilder('node');
            $view_mode = 'teaser';
            //$nodes = $nodeViewBuilder->viewMultiple($nodeObjects, $view_mode);
            $nodes = [];
            foreach ($nodeObjects as $node) {
                $nodes[] = $nodeViewBuilder->view($node, $view_mode);
            }
            $build = [
                'wrapper' => [
                    // tableau de renu
                    '#type' => 'container', // ajoute une <div> avec l'attribut 'class= BlogList-wrapper
                    '#attributes' => [
                        'class' => ['BlogList-wrapper'],
                    ],
                    'nodes' => [
                        // tableau de renu
                        '#theme' => 'cp_blog_post_blog_list',
                        '#nodes' => $nodes,
                    ],
                    'pager' => [
                        // tableau de renu
                        '#type' => 'pager',
                    ],
                ],
            ];
        }
        return $build;
    }
}
// $nodes = Drupal::entityTypeManager()
//     ->getStorage('node')
//     ->loadMultiple($nodeIds);
// dpm($nodeStorage);
// $query = $nodeStorage->getQuery();
// $query->condition('type', 'blog_post');
// $nodes = $query->execute();
// dpm($nodeStorage);
/* 1/ methode : filtre de selection pour affichage de type "blog" */
// $nodes = $nodeStorage->loadByProperties([
//     'type' => 'blog_post',
//     'status' => true,
// ]);
// dpm($nodes);

/* 1/ methode : filtre de selection pour affichage de type "blog" */
// $query = $nodeStorage->getQuery();
// $query->condition('type', 'blog_post');
// $nodes = $query->execute();

// if (!empty($nids)) {
//     $nodes = $nodeStorage->loadMultiple($nids);
//     dpm($nodes);
// }

// $countQuery = clone $query;
// $count = $countQuery->count()->execute();
// $nids = $query->execute();
// dpm($count);

// // Do something with your variables here.
// $myText = 'toto titi tata !';
// $myNumber = 44;
// $myArray = [1, 2, 3];

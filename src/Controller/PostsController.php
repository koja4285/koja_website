<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @author    Kohei Koja
 */

namespace App\Controller;

use App\Controller\AppController;

class PostsController extends AppController
{

    /**
     * Initializer
     */
    public function initialize() : void
    {
        parent::initialize();
        $this->loadComponent('Paginator');

        // Authentication removal
        $this->Authentication->addUnauthenticatedActions(['index', 'view']);

        // Configure Authorization actions
        $this->Authorization->skipAuthorization(['index', 'view']);

    }

    /**
     * The head page of blog's page.
     * Present list of blogs and archives.
     */
    public function index()
    {
        // Authentication: Check if user is logged in
        $result = $this->Authentication->getResult();
        if ($result->isValid())
        {
            $thisUser = $this->request->getAttribute('identity')->getOriginalData();
            $this->set(compact('thisUser'));
        }


        // Takes up to three posts within last week.
        // The posts are used as latest posts.
        // "\" specifies default namespace.
        $today = new \DateTime('now', new \DateTimeZone('America/New_York'));
        $aWeekAgo = $today->sub(new \DateInterval('P7D'))->format('Y-m-d');
        $latests = $this->Posts->find()
            ->limit(3)
            ->order(['created' => 'DESC'])
            ->where(['created >=' => $aWeekAgo])
            ->toArray();
        $this->set(compact('latests'));




        $posts = $this->Paginator->paginate(
            $this->Posts->find()
                ->order(['created' => 'DESC'])
        );
        $this->set(compact('posts'));
    }

    /**
     * View method.
     * Anybody can view any blog.
     */
    public function view($slug = null)
    {
        $post = $this->Posts->findBySlug($slug)->firstOrFail();
        $this->set(compact('post'));
    }


    /**
     * Add method.
     * Only admin can add a post.
     */
    public function add()
    {
        // Authorization: Check if the user is admin
        $thisUser = $this->request->getAttribute('identity')->getOriginalData();
        $this->Authorization->authorize($thisUser, 'beAdmin');

        $post = $this->Posts->newEmptyEntity();

        if ($this->request->is('post'))
        {
            // Set up slug.
            // Slug is hyphen-based title instead of space-based.
            // e.g.) title: "this is title" => slug: "this-is-title"
            $data = $this->request->getData();
            $data['slug'] = str_replace(' ', '-', trim(strtolower($data['title'])));

            // Uppercase the first character of each word in a title
            $data['title'] = ucwords($data['title']);

            $post = $this->Posts->patchEntity($post, $data);
            if ($this->Posts->save($post))
            {
                $this->Flash->success(__('Successfully added a new post!'));
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error(__('Could not add a new post'));
            }
        }

        $this->set(compact('post'));

    }

    /**
     * Edit method.
     * Only admin can edit a post.
     *
     * @param integer|null $id field name to change.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        
    }

}

?>
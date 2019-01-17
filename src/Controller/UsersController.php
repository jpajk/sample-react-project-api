<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow([
            'add', 'register', 'token'
        ]);
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                $this->set('token', $user->generateToken());
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function login()
    {
        $this->request->allowMethod('post');

        $user = $this->Auth->identify();

        if ($user) {
            /** @var \App\Model\Entity\User $userObject */
            $userObject = TableRegistry::getTableLocator()
                ->get('Users')
                ->get($user['id']);

            $this->set('token',  $userObject->generateToken());
        } else {
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        $this->Flash->success(__('You have been successfully logged out.'));
    }
}

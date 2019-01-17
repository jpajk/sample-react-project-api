<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Crud\Controller\ControllerTrait;

class AppController extends Controller
{
    use ControllerTrait;
    /**
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.Add',
                'Crud.Edit',
                'Crud.View',
                'Crud.Delete'
            ]
        ]);

        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'storage' => 'Memory',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                    ]
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' => 'Users',
                    'fields' => [
                        'username' => 'id',
                    ],
                    'parameter' => 'token',
                    'queryDatasource' => true,
                ]
            ],
            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',
        ]);
    }

    public function beforeRender(Event $event)
    {
        $this->RequestHandler->renderAs($this, 'json');

        $user = null;

        if (isset($this->Auth)) {
            $user = $this->Auth->user();
        }

        $this->viewVars = [
            '_serialize' => true,
            'user' => $user,
            'messages' => $this->getFlashMessages(),
            'data' => (object) $this->viewVars
        ];

        $this->request->getSession()->delete('Flash');
    }

    public function getFlashMessages()
    {
        $from_session = $this->request->getSession()->read('Flash');

        if (!$from_session) {
            return [];
        }

        return array_values(array_shift($from_session));
    }
}

<?php
namespace AuditLog\Model\Table;

use Cake\Network\Request;

trait CurrentUserTrait 
{
    public function currentUser()
    {
        $request = Request::createFromGlobals();
        $session = $request->session();
        $username = $session->read('Auth.User.username');

        return [
            'id' => $username,
            'ip' => $request->env('REMOTE_ADDR'),
            'url' => $request->here(),
            'description' => h(sprintf('Action by %s', $username)),
        ];
    }

    public function getDeleteEventDescription() {
        $session = Request::createFromGlobals()->session();
        $description = $session->consume('Auditable.auditDescription');
        if (!$description) {
            return h(sprintf('Action by %s', $session->read('Auth.User.username')));
        }
        return $description;
    }
}
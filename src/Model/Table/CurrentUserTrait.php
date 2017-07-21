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

        if ($session->check('Auditable.auditDescription')) {
            $description = $session->consume('Auditable.auditDescription');
        } else {
            $description = h(sprintf('Action by %s', $username));
        }

        return [
            'id' => $username,
            'ip' => $request->env('REMOTE_ADDR'),
            'url' => $request->here(),
            'description' => $description,
        ];
    }
}
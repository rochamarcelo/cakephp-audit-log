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

        $description = h(sprintf('Action by %s', $username));

        if ($message = $session->read('Auditable__auditDescription')) {
            $description = h($message);
            $session->consume('Auditable__auditDescription');
        }
        
        return [
            'id' => $username,
            'ip' => $request->env('REMOTE_ADDR'),
            'url' => $request->here(),
            'description' => $description,
        ];
    }
}
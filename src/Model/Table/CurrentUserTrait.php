<?php
namespace AuditLog\Model\Table;

use Cake\Http\ServerRequestFactory;

trait CurrentUserTrait 
{
    public function currentUser()
    {
        $request = ServerRequestFactory::fromGlobals();
        $session = $request->getSession();
        $username = $session->read('Auth.User.username');

        return [
            'id' => $username,
            'ip' => $request->getEnv('REMOTE_ADDR'),
            'url' => $request->getAttribute('here'),
            'description' => h(sprintf('Action by %s', $username)),
        ];
    }

    public function getDeleteEventDescription() {
        $session = ServerRequestFactory::fromGlobals()->getSession();
        $description = $session->consume('Auditable.auditDescription');
        if (!$description) {
            return h(sprintf('Action by %s', $session->read('Auth.User.username')));
        }
        return $description;
    }
}
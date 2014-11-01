<?php
namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class AuthService
 * @package User\Controller\Plugin
 */
class AuthService extends AbstractPlugin
{
    /**
     * @var \User\Authentication\Auth
     */
    protected $authService;

    public function __invoke()
    {
        if (! $this->authService) {
            $this->authService = $this->getController()->getServiceLocator()->get('AuthService');
        }

        return $this->authService;
    }
}

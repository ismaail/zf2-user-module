<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\LoginForm;
use User\Form\LoginFilter;

/**
 * Class UserControlelr
 * @package User
 */
class UserController extends AbstractActionController
{
    /**
     * Login
     *
     * @return \Zend\Http\Response|ViewModel
     */
    public function loginAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);

        $form = new LoginForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter((new LoginFilter())->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                if ($this->signIn($form->getData())) {
                    // Redirect to requested url
                    return $this->redirect()->toUrl($this->getRequest()->getRequestUri());

                } else {
                    $view->setVariable('loginResponse', array('Incorrect email or password.', 'Try again!'));
                }
            }

            // clear password
            $form->get('password')->setValue('');
        }

        return $view->setVariables(array(
            'form' => $form,
        ));
    }

    /**
     * Sign-in the user
     *
     * @param $data
     */
    protected function signIn($data)
    {
        // sign-in
        $this->authService()->setIdentityValue($data['email']);
        $this->authService()->setCredentialValue($data['password']);

        $authentication = $this->authService()->authenticate(
            array('email', 'password'),
            true
        );

        if ($data['rememberMe']) {
            $this->authService()->getStorage()->rememberMe(604800); // 1 week
        }

        return $authentication->isValid();
    }
}

<?php
namespace Ilcfrance\SecurityBundle\Controller;

use Ilcfrance\SecurityBundle\Form\LoginTForm as LoginTForm;
use Ilcfrance\ResBundle\Controller\IlcfranceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LoginController extends IlcfranceController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'security');
	}

	public function loginAction(Request $request)
	{
		if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_homepage'));
		}

		$session = $this->getSession();
		if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
			$request->attributes->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->addFlash('error', $msg);
		} elseif ($session->has(Security::AUTHENTICATION_ERROR)) {
			$error = $session->get(Security::AUTHENTICATION_ERROR);
			$session->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->addFlash('error', $msg);
		}

		$lastUsername = $session->get('_security.last_username');
		$referer = $this->getReferer();

		$loginForm = $this->createForm(LoginTForm::class);

		$loginForm->get('username')->setData($lastUsername);
		$loginForm->get('target_path')->setData($referer);
		$loginForm->get('remember_me')->setData(true);

		$this->addTwigVar('LoginForm', $loginForm->createView());

		$this->addTwigVar('pageTitle', 'Authentification');
		$this->setHtmlHeadPageTitle('Authentification - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceSecurityBundle:Login:login.html.twig', $this->getTwigVars());
	}
}
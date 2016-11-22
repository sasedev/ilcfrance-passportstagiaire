<?php
namespace Ilcfrance\StagiairePassportBundle\Controller;

use Ilcfrance\ResBundle\Controller\IlcfranceController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends IlcfranceController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'admin');
		$this->addTwigVar('admmenu_active', 'home');
	}

	public function indexAction(Request $request)
	{
		return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
	}
}

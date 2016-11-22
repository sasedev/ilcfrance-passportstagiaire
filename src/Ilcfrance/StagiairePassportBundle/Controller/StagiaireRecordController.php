<?php
namespace Ilcfrance\StagiairePassportBundle\Controller;

use Ilcfrance\ResBundle\Controller\IlcfranceController;
use Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord\UpdateCommentsTForm as StagiaireRecordUpdateCommentsTForm;
use Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord\UpdateHomeworksTForm as StagiaireRecordUpdateHomeworksTForm;
use Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord\UpdateRecordDateTForm as StagiaireRecordUpdateRecordDateTForm;
use Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord\UpdateWorksCoveredTForm as StagiaireRecordUpdateWorksCoveredTForm;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class StagiaireRecordController extends IlcfranceController
{

	/**
	 *
	 * @param string $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}
		$em = $this->getEntityManager();
		try {
			$stagiaireRecord = $em->getRepository('IlcfranceDataBundle:StagiaireRecord')->find($id);

			if (null == $stagiaireRecord) {
				$this->addFlash('warning', $this->translate('StagiaireRecord.notfound'));
			} else {
				if (!$this->isGranted('ROLE_ADMIN')) {
					$user = $this->getSecurityTokenStorage()->getToken()->getUser();
					if ($user->getId() != $stagiaireRecord->getTeacher()->getId()) {
						$this->addFlash('error', $this->translate('StagiaireRecord.delete.failure'));
						return $this->redirect($urlFrom);
					}
				}

				$em->remove($stagiaireRecord);
				$em->flush();

				$this->addFlash('success', $this->translate('StagiaireRecord.delete.success', array(
					'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
					'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
					'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->addFlash('error', $this->translate('StagiaireRecord.delete.failure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editGetAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}

		$em = $this->getEntityManager();
		try {
			$stagiaireRecord = $em->getRepository('IlcfranceDataBundle:StagiaireRecord')->find($id);
			if (!$this->isGranted('ROLE_ADMIN')) {
				$user = $this->getSecurityTokenStorage()->getToken()->getUser();
				if ($user->getId() != $stagiaireRecord->getTeacher()->getId()) {
					$this->addFlash('error', $this->translate('StagiaireRecord.delete.failure'));
					return $this->redirect($urlFrom);
				}
			}

			if (null == $stagiaireRecord) {
				$this->addFlash('warning', $this->translate('StagiaireRecord.notfound'));
			} else {
				$stagiaireRecordUpdateCommentsForm = $this->createForm(StagiaireRecordUpdateCommentsTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateHomeworksForm = $this->createForm(StagiaireRecordUpdateHomeworksTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateRecordDateForm = $this->createForm(StagiaireRecordUpdateRecordDateTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateWorksCoveredForm = $this->createForm(StagiaireRecordUpdateWorksCoveredTForm::class, $stagiaireRecord);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');

				$this->addTwigVar('stagiaireRecord', $stagiaireRecord);
				$this->addTwigVar('StagiaireRecordUpdateCommentsForm', $stagiaireRecordUpdateCommentsForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateHomeworksForm', $stagiaireRecordUpdateHomeworksForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateRecordDateForm', $stagiaireRecordUpdateRecordDateForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateWorksCoveredForm', $stagiaireRecordUpdateWorksCoveredForm->createView());

				$this->addTwigVar('admmenu_active', 'stagiaires_edit');
				$this->addTwigVar('pageTitle', $this->translate('StagiaireRecord.pageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
					'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
					'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
				)));
				$this->setHtmlHeadPageTitle($this->translate('StagiaireRecord.htmlHeadPageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
					'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
					'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:StagiaireRecord:edit.html.twig', $this->getTwigVars());
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}

		$em = $this->getEntityManager();
		try {
			$stagiaireRecord = $em->getRepository('IlcfranceDataBundle:StagiaireRecord')->find($id);
			if (!$this->isGranted('ROLE_ADMIN')) {
				$user = $this->getSecurityTokenStorage()->getToken()->getUser();
				if ($user->getId() != $stagiaireRecord->getTeacher()->getId()) {
					$this->addFlash('error', $this->translate('StagiaireRecord.delete.failure'));
					return $this->redirect($urlFrom);
				}
			}

			if (null == $stagiaireRecord) {
				$this->addFlash('warning', $this->translate('StagiaireRecord.notfound'));
			} else {
				$stagiaireRecordUpdateCommentsForm = $this->createForm(StagiaireRecordUpdateCommentsTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateHomeworksForm = $this->createForm(StagiaireRecordUpdateHomeworksTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateRecordDateForm = $this->createForm(StagiaireRecordUpdateRecordDateTForm::class, $stagiaireRecord);
				$stagiaireRecordUpdateWorksCoveredForm = $this->createForm(StagiaireRecordUpdateWorksCoveredTForm::class, $stagiaireRecord);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');
				$reqData = $request->request->all();

				if (isset($reqData['StagiaireRecordUpdateCommentsForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireRecordUpdateCommentsForm->handleRequest($request);
					if ($stagiaireRecordUpdateCommentsForm->isValid()) {
						$em->persist($stagiaireRecord);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaireRecord);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));
					}
				} elseif (isset($reqData['StagiaireRecordUpdateHomeworksForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireRecordUpdateHomeworksForm->handleRequest($request);
					if ($stagiaireRecordUpdateHomeworksForm->isValid()) {
						$em->persist($stagiaireRecord);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaireRecord);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));
					}
				} elseif (isset($reqData['StagiaireRecordUpdateRecordDateForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireRecordUpdateRecordDateForm->handleRequest($request);
					if ($stagiaireRecordUpdateRecordDateForm->isValid()) {
						$em->persist($stagiaireRecord);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaireRecord);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));
					}
				} elseif (isset($reqData['StagiaireRecordUpdateWorksCoveredForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireRecordUpdateWorksCoveredForm->handleRequest($request);
					if ($stagiaireRecordUpdateWorksCoveredForm->isValid()) {
						$em->persist($stagiaireRecord);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaireRecord);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
							'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
							'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
						)));
					}
				}

				$this->addTwigVar('stagiaireRecord', $stagiaireRecord);
				$this->addTwigVar('StagiaireRecordUpdateCommentsForm', $stagiaireRecordUpdateCommentsForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateHomeworksForm', $stagiaireRecordUpdateHomeworksForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateRecordDateForm', $stagiaireRecordUpdateRecordDateForm->createView());
				$this->addTwigVar('StagiaireRecordUpdateWorksCoveredForm', $stagiaireRecordUpdateWorksCoveredForm->createView());

				$this->addTwigVar('admmenu_active', 'stagiaires_edit');
				$this->addTwigVar('pageTitle', $this->translate('StagiaireRecord.pageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
					'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
					'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
				)));
				$this->setHtmlHeadPageTitle($this->translate('StagiaireRecord.htmlHeadPageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaireRecord->getStagiaire()->getFullName(),
					'%dtStart%' => $stagiaireRecord->getRecordDate()->format('Y-m-d'),
					'%hStart%' => $stagiaireRecord->getRecordDate()->format('H:i:s')
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:StagiaireRecord:edit.html.twig', $this->getTwigVars());
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}
}
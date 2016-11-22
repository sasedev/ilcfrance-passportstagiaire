<?php
namespace Ilcfrance\StagiairePassportBundle\Controller;

use Ilcfrance\StagiairePassportBundle\Form\Doc\AddTForm as DocAddTForm;
use Ilcfrance\StagiairePassportBundle\Form\Doc\UpdateContentTForm as DocUpdateContentTForm;
use Ilcfrance\StagiairePassportBundle\Form\Doc\UpdateDescriptionTForm as DocUpdateDescriptionTForm;
use Ilcfrance\StagiairePassportBundle\Form\Doc\UpdateOriginalNameTForm as DocUpdateOriginalNameTForm;
use Ilcfrance\ResBundle\Controller\IlcfranceController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Ilcfrance\DataBundle\OrmEntity\Doc;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocController extends IlcfranceController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'admin');
		$this->addTwigVar('admmenu_active', 'docs');
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request)
	{
		$em = $this->getEntityManager();
		$docs = $em->getRepository('IlcfranceDataBundle:Doc')->getAll();
		$this->addTwigVar('docs', $docs);

		$this->addTwigVar('admmenu_active', 'docs_list');
		$this->addTwigVar('pageTitle', $this->translate('Doc.pageTitle.admin.list'));
		$this->setHtmlHeadPageTitle($this->translate('Doc.htmlHeadPageTitle.admin.list') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Doc:list.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addGetAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_list'));
		}
		$doc = new Doc();
		$docAddForm = $this->createForm(DocAddTForm::class, $doc);
		$this->addTwigVar('doc', $doc);
		$this->addTwigVar('DocAddForm', $docAddForm->createView());

		$this->addTwigVar('admmenu_active', 'docs_add');
		$this->addTwigVar('pageTitle', $this->translate('Doc.pageTitle.admin.add'));
		$this->setHtmlHeadPageTitle($this->translate('Doc.htmlHeadPageTitle.admin.add') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Doc:add.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addPostAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_addGet'));
		}
		$doc = new Doc();
		$docAddForm = $this->createForm(DocAddTForm::class, $doc);

		$reqData = $request->request->all();

		if (isset($reqData['DocAddForm'])) {
			$docAddForm->handleRequest($request);
			if ($docAddForm->isValid()) {

				$em = $this->getEntityManager();

				$docFile = $docAddForm['file']->getData();

				$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

				$originalName = $docFile->getClientOriginalName();
				$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
				$mimeType = $docFile->getMimeType();
				$docFile->move($docDir, $fileName);

				$size = filesize($docDir . '/' . $fileName);
				$md5 = md5_file($docDir . '/' . $fileName);

				$doc->setFileName($fileName);
				$doc->setOriginalName($originalName);
				$doc->setSize($size);
				$doc->setMimeType($mimeType);
				$doc->setMd5($md5);

				$em->persist($doc);
				$em->flush();
				$this->addFlash('success', $this->translate('Doc.add.success', array(
					'%doc%' => $doc->getOriginalName()
				)));

				return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_editGet', array(
					'id' => $doc->getId()
				)));
			} else {
				$this->addFlash('error', $this->translate('Doc.add.failure'));
			}
		}
		$this->addTwigVar('doc', $doc);
		$this->addTwigVar('DocAddForm', $docAddForm->createView());

		$this->addTwigVar('admmenu_active', 'docs_add');
		$this->addTwigVar('pageTitle', $this->translate('Doc.pageTitle.admin.add'));
		$this->setHtmlHeadPageTitle($this->translate('Doc.htmlHeadPageTitle.admin.add') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Doc:add.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @param string $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_doc_list');
		}
		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('IlcfranceDataBundle:Doc')->find($id);

			if (null == $doc) {
				$this->addFlash('warning', $this->translate('Doc.notfound'));
			} else {
				$em->remove($doc);
				$em->flush();

				$this->addFlash('success', $this->translate('Doc.delete.success', array(
					'%doc%' => $doc->getOriginalName()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->addFlash('error', $this->translate('Doc.delete.failure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_doc_list');
		}
		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('IlcfranceDataBundle:Doc')->find($id);

			if (null == $doc) {
				$this->addFlash('warning', $this->translate('Doc.download.notfound'));
			} else {
				$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
				$fileName = $doc->getFileName();

				try {
					$dlFile = new File($docDir . '/' . $fileName);
					$response = new StreamedResponse(function () use ($dlFile) {
						$handle = fopen($dlFile->getRealPath(), 'r');
						while (!feof($handle)) {
							$buffer = fread($handle, 1024);
							echo $buffer;
							flush();
						}
						fclose($handle);
					});

					$response->headers->set('Content-Type', $doc->getMimeType());
					$response->headers->set('Cache-Control', '');
					$response->headers->set('Content-Length', $doc->getSize());
					$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $doc->getDtUpdate()->getTimestamp()));
					$fallback = $this->normalize($doc->getOriginalName());

					$contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $doc->getOriginalName(), $fallback);
					$response->headers->set('Content-Disposition', $contentDisposition);

					$doc->setNbrDownloads($doc->getNbrDownloads() + 1);
					$em->persist($doc);
					$em->flush();

					return $response;
				} catch (FileNotFoundException $fnfex) {
					$this->addFlash('warning', $this->translate('Doc.download.notfound'));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
			$this->addFlash('warning', $this->translate('Doc.download.notfound'));
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
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_doc_list');
		}

		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('IlcfranceDataBundle:Doc')->find($id);

			if (null == $doc) {
				$this->addFlash('warning', $this->translate('Doc.notfound'));
			} else {
				$docUpdateDescriptionForm = $this->createForm(DocUpdateDescriptionTForm::class, $doc);
				$docUpdateContentForm = $this->createForm(DocUpdateContentTForm::class, $doc);
				$docUpdateOriginalNameForm = $this->createForm(DocUpdateOriginalNameTForm::class, $doc);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');

				$this->addTwigVar('doc', $doc);
				$this->addTwigVar('DocUpdateDescriptionForm', $docUpdateDescriptionForm->createView());
				$this->addTwigVar('DocUpdateContentForm', $docUpdateContentForm->createView());
				$this->addTwigVar('DocUpdateOriginalNameForm', $docUpdateOriginalNameForm->createView());

				$this->addTwigVar('admmenu_active', 'docs_edit');
				$this->addTwigVar('pageTitle', $this->translate('Doc.pageTitle.admin.edit', array(
					'%doc%' => $doc->getOriginalName()
				)));
				$this->setHtmlHeadPageTitle($this->translate('Doc.htmlHeadPageTitle.admin.edit', array(
					'%doc%' => $doc->getOriginalName()
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:Doc:edit.html.twig', $this->getTwigVars());
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
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_doc_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_doc_list');
		}

		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('IlcfranceDataBundle:Doc')->find($id);

			if (null == $doc) {
				$this->addFlash('warning', $this->translate('Doc.notfound'));
			} else {
				$docUpdateDescriptionForm = $this->createForm(DocUpdateDescriptionTForm::class, $doc);
				$docUpdateContentForm = $this->createForm(DocUpdateContentTForm::class, $doc);
				$docUpdateOriginalNameForm = $this->createForm(DocUpdateOriginalNameTForm::class, $doc);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');
				$reqData = $request->request->all();

				if (isset($reqData['DocUpdateDescriptionForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$docUpdateDescriptionForm->handleRequest($request);
					if ($docUpdateDescriptionForm->isValid()) {
						$em->persist($doc);
						$em->flush();
						$this->addFlash('success', $this->translate('Doc.edit.success', array(
							'%doc%' => $doc->getOriginalName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($doc);

						$this->addFlash('error', $this->translate('Doc.edit.failure', array(
							'%doc%' => $doc->getOriginalName()
						)));
					}
				} elseif (isset($reqData['DocUpdateOriginalNameForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$docUpdateOriginalNameForm->handleRequest($request);
					if ($docUpdateOriginalNameForm->isValid()) {
						$em->persist($doc);
						$em->flush();
						$this->addFlash('success', $this->translate('Doc.edit.success', array(
							'%doc%' => $doc->getOriginalName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($doc);

						$this->addFlash('error', $this->translate('Doc.edit.failure', array(
							'%doc%' => $doc->getOriginalName()
						)));
					}
				} elseif (isset($reqData['DocUpdateContentForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$docUpdateContentForm->handleRequest($request);
					if ($docUpdateContentForm->isValid()) {

						$docFile = $docUpdateContentForm['file']->getData();

						$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

						$originalName = $docFile->getClientOriginalName();
						$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
						$mimeType = $docFile->getMimeType();
						$docFile->move($docDir, $fileName);

						$size = filesize($docDir . '/' . $fileName);
						$md5 = md5_file($docDir . '/' . $fileName);

						$doc->setFileName($fileName);
						$doc->setOriginalName($originalName);
						$doc->setSize($size);
						$doc->setMimeType($mimeType);
						$doc->setMd5($md5);

						$em->persist($doc);
						$em->flush();
						$this->addFlash('success', $this->translate('Doc.edit.success', array(
							'%doc%' => $doc->getOriginalName()
						)));

						return $this->redirect($urlFrom);
					} else {

						$em->refresh($doc);

						$this->addFlash('error', $this->translate('Doc.edit.failure', array(
							'%doc%' => $doc->getOriginalName()
						)));
					}
				}

				$this->addTwigVar('doc', $doc);
				$this->addTwigVar('DocUpdateDescriptionForm', $docUpdateDescriptionForm->createView());
				$this->addTwigVar('DocUpdateContentForm', $docUpdateContentForm->createView());
				$this->addTwigVar('DocUpdateOriginalNameForm', $docUpdateOriginalNameForm->createView());

				$this->addTwigVar('admmenu_active', 'docs_edit');
				$this->addTwigVar('pageTitle', $this->translate('Doc.pageTitle.admin.edit', array(
					'%doc%' => $doc->getOriginalName()
				)));
				$this->setHtmlHeadPageTitle($this->translate('Doc.htmlHeadPageTitle.admin.edit', array(
					'%doc%' => $doc->getOriginalName()
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:Doc:edit.html.twig', $this->getTwigVars());
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}
}
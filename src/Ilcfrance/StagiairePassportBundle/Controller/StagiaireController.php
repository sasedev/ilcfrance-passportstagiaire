<?php
namespace Ilcfrance\StagiairePassportBundle\Controller;

use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\AddTForm as StagiaireAddTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\ImportTForm as StagiaireImportTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateAddressTForm as StagiaireUpdateAddressTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateCoursesTForm as StagiaireUpdateCoursesTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateFirstNameTForm as StagiaireUpdateFirstNameTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateJobTForm as StagiaireUpdateJobTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateLastNameTForm as StagiaireUpdateLastNameTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateLevelTForm as StagiaireUpdateLevelTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateNeedsTForm as StagiaireUpdateNeedsTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdateTownTForm as StagiaireUpdateTownTForm;
use Ilcfrance\StagiairePassportBundle\Form\Stagiaire\UpdatePhoneTForm as StagiaireUpdatePhoneTForm;
use Ilcfrance\StagiairePassportBundle\Form\StagiaireRecord\AddTForm as StagiaireRecordAddTForm;
use Ilcfrance\DataBundle\OrmEntity\Stagiaire;
use Ilcfrance\DataBundle\OrmEntity\StagiaireRecord;
use Ilcfrance\ResBundle\Controller\IlcfranceController;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class StagiaireController extends IlcfranceController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'admin');
		$this->addTwigVar('admmenu_active', 'stagiaires');
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function listAction(Request $request)
	{
		$em = $this->getEntityManager();
		$stagiaires = $em->getRepository('IlcfranceDataBundle:Stagiaire')->getAll();
		$this->addTwigVar('stagiaires', $stagiaires);

		$this->addTwigVar('admmenu_active', 'stagiaires_list');
		$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.list'));
		$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.list') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:list.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addGetAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
		}
		$stagiaire = new Stagiaire();
		$stagiaireAddForm = $this->createForm(StagiaireAddTForm::class, $stagiaire);
		$this->addTwigVar('stagiaire', $stagiaire);
		$this->addTwigVar('StagiaireAddForm', $stagiaireAddForm->createView());

		$this->addTwigVar('admmenu_active', 'stagiaires_add');
		$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.add'));
		$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.add') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:add.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addPostAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_addGet'));
		}
		$stagiaire = new Stagiaire();
		$stagiaireAddForm = $this->createForm(StagiaireAddTForm::class, $stagiaire);

		$reqData = $request->request->all();

		if (isset($reqData['StagiaireAddForm'])) {
			$stagiaireAddForm->handleRequest($request);
			if ($stagiaireAddForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($stagiaire);
				$em->flush();
				$this->addFlash('success', $this->translate('Stagiaire.add.success', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)));

				return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_editGet', array(
					'id' => $stagiaire->getId()
				)));
			} else {
				$this->addFlash('error', $this->translate('Stagiaire.add.failure'));
			}
		}
		$this->addTwigVar('stagiaire', $stagiaire);
		$this->addTwigVar('StagiaireAddForm', $stagiaireAddForm->createView());

		$this->addTwigVar('admmenu_active', 'stagiaires_add');
		$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.add'));
		$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.add') . ' - ' . $this->getParameter('sitename'));
		return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:add.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function importGetAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}
		$stagiaireImportForm = $this->createForm(StagiaireImportTForm::class);

		$this->addTwigVar('StagiaireImportForm', $stagiaireImportForm->createView());

		$this->addTwigVar('admmenu_active', 'stagiaires_import');
		$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.import'));
		$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.import') . ' - ' . $this->getParameter('sitename'));

		return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:import.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function importPostAction(Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}
		$stagiaireImportForm = $this->createForm(StagiaireImportTForm::class);

		$reqData = $request->request->all();

		if (isset($reqData['StagiaireImportForm'])) {
			$stagiaireImportForm->handleRequest($request);
			if ($stagiaireImportForm->isValid()) {

				ini_set('memory_limit', '4096M');
				ini_set('max_execution_time', '0');
				$extension = $stagiaireImportForm['excel']->getData()->guessExtension();
				if ($extension == 'zip') {
					$extension = 'xlsx';
				}

				$filename = uniqid() . '.' . $extension;
				$stagiaireImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
				$fullfilename = $this->getParameter('adapter_files');
				$fullfilename .= '/' . $filename;

				$excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

				$log = '';

				$iterator = $excelObj->getWorksheetIterator();

				$activeSheetIndex = -1;
				$i = 0;

				foreach ($iterator as $worksheet) {
					$worksheetTitle = $worksheet->getTitle();
					$highestRow = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

					$log .= "Feuille : '" . $worksheetTitle . "' trouvée contenant " . $highestRow . ' lignes et ' . $highestColumnIndex . ' colonnes avec comme plus grand index ' . $highestColumn . ' <br>';
					if (\trim($worksheetTitle) == 'Stagiaires') {
						$activeSheetIndex = $i;
					}
					$i++;
				}
				if ($activeSheetIndex == -1) {
					$log .= "Aucune Feuille de Titre 'Stagiaires' trouvée tentative d'import depuis le première Feuille<br>";
					$activeSheetIndex = 0;
				}

				$excelObj->setActiveSheetIndex($activeSheetIndex);
				$worksheet = $excelObj->getActiveSheet();
				$highestRow = $worksheet->getHighestRow();
				$lineRead = 0;
				$lineUnprocessed = 0;
				$stagiaireNew = 0;
				$lineError = 0;
				$em = $this->getEntityManager();

				for ($row = 3; $row <= $highestRow; $row++) {
					$lineRead++;

					$lastName = \strtolower(\trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue())));
					$firstName = \strtolower(\trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue())));
					$job = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
					$initLevel = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$town = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
					$courses = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));

					if ($lastName != '' and $firstName != '') {
						$stagiaire = $em->getRepository('IlcfranceDataBundle:Stagiaire')->findOneBy(array(
							'lastName' => $lastName,
							'firstName' => $firstName
						));
						if (null == $stagiaire) {
							$stagiaireNew++;

							$stagiaire = new Stagiaire();
							$stagiaire->setFirstName($firstName);
							$stagiaire->setLastName($lastName);
							$stagiaire->setJob($job);
							$stagiaire->setInitLevel($initLevel);
							$stagiaire->setTown($town);
							$stagiaire->setCourses($courses);

							$em->persist($stagiaire);
						} else {
							$lineUnprocessed++;
							$log .= "le Stagiaire " . $lastName . ' ' . $firstName . ' existe déjà<br>';
						}
					} else {
						$lineError++;
						$log .= 'la ligne ' . $row . ' contient des erreurs<br>';
					}
				}
				$em->flush();

				$log .= $lineRead . ' lignes lues<br>';
				$log .= $stagiaireNew . " nouveaux Stagiaires<br>";
				$log .= $lineUnprocessed . " Stagiaires déjà dans la base<br>";
				$log .= $lineError . ' lignes contenant des erreurs<br>';

				$this->addFlash('log', $log);

				$this->addFlash('success', $this->translate('Stagiaire.import.success'));

				return $this->redirect($urlFrom);
			}
		}

		$this->addTwigVar('StagiaireImportForm', $stagiaireImportForm->createView());

		$this->addTwigVar('admmenu_active', 'stagiaires_import');
		$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.import'));
		$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.import') . ' - ' . $this->getParameter('sitename'));

		return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:import.html.twig', $this->getTwigVars());
	}

	/**
	 *
	 * @param string $id
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		if (!$this->isGranted('ROLE_ADMIN')) {
			return $this->redirect($this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list'));
		}
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('ilcfrance_stagiaire_passport_stagiaire_list');
		}
		$em = $this->getEntityManager();
		try {
			$stagiaire = $em->getRepository('IlcfranceDataBundle:Stagiaire')->find($id);

			if (null == $stagiaire) {
				$this->addFlash('warning', $this->translate('Stagiaire.notfound'));
			} else {
				$em->remove($stagiaire);
				$em->flush();

				$this->addFlash('success', $this->translate('Stagiaire.delete.success', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->addFlash('error', $this->translate('Stagiaire.delete.failure'));
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
			$stagiaire = $em->getRepository('IlcfranceDataBundle:Stagiaire')->find($id);

			if (null == $stagiaire) {
				$this->addFlash('warning', $this->translate('Stagiaire.notfound'));
			} else {
				$stagiaireUpdateAddressForm = $this->createForm(StagiaireUpdateAddressTForm::class, $stagiaire);
				$stagiaireUpdateCoursesForm = $this->createForm(StagiaireUpdateCoursesTForm::class, $stagiaire);
				$stagiaireUpdateFirstNameForm = $this->createForm(StagiaireUpdateFirstNameTForm::class, $stagiaire);
				$stagiaireUpdateJobForm = $this->createForm(StagiaireUpdateJobTForm::class, $stagiaire);
				$stagiaireUpdateLastNameForm = $this->createForm(StagiaireUpdateLastNameTForm::class, $stagiaire);
				$stagiaireUpdateLevelForm = $this->createForm(StagiaireUpdateLevelTForm::class, $stagiaire);
				$stagiaireUpdateNeedsForm = $this->createForm(StagiaireUpdateNeedsTForm::class, $stagiaire);
				$stagiaireUpdateTownForm = $this->createForm(StagiaireUpdateTownTForm::class, $stagiaire);
				$stagiaireUpdatePhoneForm = $this->createForm(StagiaireUpdatePhoneTForm::class, $stagiaire);

				$stagiaireRecord = new StagiaireRecord();
				$teacher = $this->getSecurityTokenStorage()->getToken()->getUser();
				$stagiaireRecord->setTeacher($teacher);
				$stagiaireRecord->setTeacherName($teacher->getFullName());
				$stagiaireRecord->setStagiaire($stagiaire);
				$now = new \DateTime();
				$hour = \date('H');
				$now->setTime($hour, 0, 0);
				$stagiaireRecord->setRecordDate($now);

				$stagiaireRecordAddForm = $this->createForm(StagiaireRecordAddTForm::class, $stagiaireRecord);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');

				$this->addTwigVar('stagiaire', $stagiaire);
				$this->addTwigVar('StagiaireUpdateAddressForm', $stagiaireUpdateAddressForm->createView());
				$this->addTwigVar('StagiaireUpdateCoursesForm', $stagiaireUpdateCoursesForm->createView());
				$this->addTwigVar('StagiaireUpdateFirstNameForm', $stagiaireUpdateFirstNameForm->createView());
				$this->addTwigVar('StagiaireUpdateJobForm', $stagiaireUpdateJobForm->createView());
				$this->addTwigVar('StagiaireUpdateLastNameForm', $stagiaireUpdateLastNameForm->createView());
				$this->addTwigVar('StagiaireUpdateLevelForm', $stagiaireUpdateLevelForm->createView());
				$this->addTwigVar('StagiaireUpdateNeedsForm', $stagiaireUpdateNeedsForm->createView());
				$this->addTwigVar('StagiaireUpdateTownForm', $stagiaireUpdateTownForm->createView());
				$this->addTwigVar('StagiaireUpdatePhoneForm', $stagiaireUpdatePhoneForm->createView());
				$this->addTwigVar('StagiaireRecordAddForm', $stagiaireRecordAddForm->createView());

				$this->addTwigVar('admmenu_active', 'stagiaires_edit');
				$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)));
				$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:edit.html.twig', $this->getTwigVars());
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
			$stagiaire = $em->getRepository('IlcfranceDataBundle:Stagiaire')->find($id);

			if (null == $stagiaire) {
				$this->addFlash('warning', $this->translate('Stagiaire.notfound'));
			} else {
				$stagiaireUpdateAddressForm = $this->createForm(StagiaireUpdateAddressTForm::class, $stagiaire);
				$stagiaireUpdateCoursesForm = $this->createForm(StagiaireUpdateCoursesTForm::class, $stagiaire);
				$stagiaireUpdateFirstNameForm = $this->createForm(StagiaireUpdateFirstNameTForm::class, $stagiaire);
				$stagiaireUpdateJobForm = $this->createForm(StagiaireUpdateJobTForm::class, $stagiaire);
				$stagiaireUpdateLastNameForm = $this->createForm(StagiaireUpdateLastNameTForm::class, $stagiaire);
				$stagiaireUpdateLevelForm = $this->createForm(StagiaireUpdateLevelTForm::class, $stagiaire);
				$stagiaireUpdateNeedsForm = $this->createForm(StagiaireUpdateNeedsTForm::class, $stagiaire);
				$stagiaireUpdateTownForm = $this->createForm(StagiaireUpdateTownTForm::class, $stagiaire);
				$stagiaireUpdatePhoneForm = $this->createForm(StagiaireUpdatePhoneTForm::class, $stagiaire);

				$stagiaireRecord = new StagiaireRecord();
				$teacher = $this->getSecurityTokenStorage()->getToken()->getUser();
				$stagiaireRecord->setTeacher($teacher);
				$stagiaireRecord->setTeacherName($teacher->getFullName());
				$stagiaireRecord->setStagiaire($stagiaire);
				$now = new \DateTime();
				$hour = \date('H');
				$now->setTime($hour, 0, 0);
				$stagiaireRecord->setRecordDate($now);
				$stagiaireRecordAddForm = $this->createForm(StagiaireRecordAddTForm::class, $stagiaireRecord);

				$this->addTwigVar('tabActive', $this->getSession()->get('tabActive', 1));
				$this->getSession()->remove('tabActive');
				$reqData = $request->request->all();

				if (isset($reqData['StagiaireUpdateAddressForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateAddressForm->handleRequest($request);
					if ($stagiaireUpdateAddressForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateCoursesForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateCoursesForm->handleRequest($request);
					if ($stagiaireUpdateCoursesForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateFirstNameForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateFirstNameForm->handleRequest($request);
					if ($stagiaireUpdateFirstNameForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateJobForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateJobForm->handleRequest($request);
					if ($stagiaireUpdateJobForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateLastNameForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateLastNameForm->handleRequest($request);
					if ($stagiaireUpdateLastNameForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateLevelForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateLevelForm->handleRequest($request);
					if ($stagiaireUpdateLevelForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateNeedsForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateNeedsForm->handleRequest($request);
					if ($stagiaireUpdateNeedsForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdateTownForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdateTownForm->handleRequest($request);
					if ($stagiaireUpdateTownForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireUpdatePhoneForm'])) {
					$this->addTwigVar('tabActive', 2);
					$this->getSession()->set('tabActive', 2);
					$stagiaireUpdatePhoneForm->handleRequest($request);
					if ($stagiaireUpdatePhoneForm->isValid()) {
						$em->persist($stagiaire);
						$em->flush();
						$this->addFlash('success', $this->translate('Stagiaire.edit.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('Stagiaire.edit.failure', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
					}
				} elseif (isset($reqData['StagiaireRecordAddForm'])) {
					$this->addTwigVar('tabActive', 3);
					$this->getSession()->set('tabActive', 3);
					$stagiaireRecordAddForm->handleRequest($request);
					if ($stagiaireRecordAddForm->isValid()) {
						$em->persist($stagiaireRecord);
						$em->flush();
						$this->addFlash('success', $this->translate('StagiaireRecord.add.success', array(
							'%stagiaire%' => $stagiaire->getFullName()
						)));
						$this->addTwigVar('tabActive', 1);
						$this->getSession()->set('tabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stagiaire);

						$this->addFlash('error', $this->translate('StagiaireRecord.add.failure'));
					}
				}

				$this->addTwigVar('stagiaire', $stagiaire);
				$this->addTwigVar('StagiaireUpdateAddressForm', $stagiaireUpdateAddressForm->createView());
				$this->addTwigVar('StagiaireUpdateCoursesForm', $stagiaireUpdateCoursesForm->createView());
				$this->addTwigVar('StagiaireUpdateFirstNameForm', $stagiaireUpdateFirstNameForm->createView());
				$this->addTwigVar('StagiaireUpdateJobForm', $stagiaireUpdateJobForm->createView());
				$this->addTwigVar('StagiaireUpdateLastNameForm', $stagiaireUpdateLastNameForm->createView());
				$this->addTwigVar('StagiaireUpdateLevelForm', $stagiaireUpdateLevelForm->createView());
				$this->addTwigVar('StagiaireUpdateNeedsForm', $stagiaireUpdateNeedsForm->createView());
				$this->addTwigVar('StagiaireUpdateTownForm', $stagiaireUpdateTownForm->createView());
				$this->addTwigVar('StagiaireUpdatePhoneForm', $stagiaireUpdatePhoneForm->createView());
				$this->addTwigVar('StagiaireRecordAddForm', $stagiaireRecordAddForm->createView());

				$this->addTwigVar('admmenu_active', 'stagiaires_edit');
				$this->addTwigVar('pageTitle', $this->translate('Stagiaire.pageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)));
				$this->setHtmlHeadPageTitle($this->translate('Stagiaire.htmlHeadPageTitle.admin.edit', array(
					'%stagiaire%' => $stagiaire->getFullName()
				)) . ' - ' . $this->getParameter('sitename'));

				return $this->render('IlcfranceStagiairePassportBundle:Stagiaire:edit.html.twig', $this->getTwigVars());
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}
}
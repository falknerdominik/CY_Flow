<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use Doctrine\Common\Util\Debug;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use BBS\ConnectYou\Domain\Model\Project;
use TYPO3\Flow\Error\Debugger;

class MarketplaceController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \BBS\ConnectYou\Domain\Repository\ProjectRepository
	 */
	protected $projectRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Context
     */
    protected $securityContext;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\ClientRepository
     */
    protected $clientRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\TeacherRepository
     */
    protected $teacherRepository;

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\StudentRepository
     */
    protected $studentRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * Diese Funktion wird immer aufgerufen wenn eine View dieses Controllers aufgerufen wird, d.h. wird sie dazu
     * verwenden 'globale' Variablen für die Views festzusetzen (z.b.: Nutzername für etc wird den Views immer übergeben)
     *
     * @return void
     */
    protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view){
        // finde das zugewiesene Projekt wenn vorhanden und der Nutzer kein Lehrer ist
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles()) || !in_array("BBS.ConnectYou:Client", $this->securityContext->getAccount()->getRoles())){
            $view->assign('userproject', $this->findUserProject());
        }else {
            $view->assign('userproject', NULL);
        }

        // Benutzername für Benutzermenü
        $view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());
    }

    /**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('projects', $this->projectRepository->findActiveProjects());
        $this->view->assign('archivedProjects', $this->projectRepository->findArchivedProjects());
    }

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function showAction(\BBS\ConnectYou\Domain\Model\Project $project) {
        $this->view->assign('project', $project);

        // Prüft ob der Auftraggeber in der Show.html angezeigt werden
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles())){

            if(in_array("BBS.ConnectYou:Client", $this->securityContext->getAccount()->getRoles())){
                if($project->getClient() == $this->securityContext->getParty()){
                    $this->view->assign('isuserproject', TRUE);
                } else {
                    $this->view->assign('isuserproject', FALSE);
                }
            } else if(in_array("BBS.ConnectYou:Student", $this->securityContext->getAccount()->getRoles())){
                    if(in_array($this->securityContext->getParty(), $project->getTeam()->toArray())){
                        $this->view->assign('isuserproject', TRUE);
                    } else {
                        $this->view->assign('isuserproject', FALSE);
                    }
            } else{
                $this->view->assign('isuserproject', FALSE);
            }
        }
	}

	/**
     * Zeigt das Erstellformular an
     *
	 * @return void
	 */
	public function newAction() { // zeigt die Erstell Form an
        // findet den zugehörigen Auftraggeber und leitet an die view weiter
        $this->view->assign('client', $this->securityContext->getAccount()->getParty());
	}



	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $newProject
	 * @return void
	 */
	public function createAction(\BBS\ConnectYou\Domain\Model\Project $newProject) {
        // Aktuelles Datum
        $curYear = date('Y');
        $nextYear = date('Y') + 1;
        $newProject->setYear($curYear . "/" . substr($nextYear,2));

        echo 'test';

        try{
            $this->projectRepository->add($newProject);

        } catch(\Exception $e){
            echo $e->getMessage();
        }
	    $this->addFlashMessage('Das Projekt "' . $newProject->getName() . '" ');
		$this->redirect('index');
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function editAction(\BBS\ConnectYou\Domain\Model\Project $project) {
		$this->view->assign('project', $project);

        $this->view->assign('clients', $this->clientRepository->findAll());
        $this->view->assign('students', $this->studentRepository->findStudentsWithoutProjectsAndStudentsOfProject($project));
        $this->view->assign('teachers', $this->teacherRepository->findAll());

        // Prüft ob der Auftraggeber in der Show.html angezeigt werden
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles())){

            if(in_array("BBS.ConnectYou:Client", $this->securityContext->getAccount()->getRoles())){
                if($project->getClient() == $this->securityContext->getParty()){
                    $this->view->assign('isuserproject', TRUE);
                }
            } else if(in_array("BBS.ConnectYou:Student", $this->securityContext->getAccount()->getRoles())){
                if(in_array($this->securityContext->getParty(), $project->getTeam()->toArray())){
                    $this->view->assign('isuserproject', TRUE);
                }
            } else{
                $this->view->assign('isuserproject', FALSE);
            }
        }
	}

    /**
     * Initaliziert die UpdateAction .. hier kann man die PropertyMappingConfiguration einstellen
     */
    public function initializeUpdateAction(){
        $pmc = $this->arguments['project']->getPropertyMappingConfiguration();
            $pmc->forProperty('team.*') // Sagt den Converter er soll zu Collection konvertieren
                ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_TARGET_TYPE, '\Doctrine\Common\Collections\Collection');
            $pmc->forProperty('team.*') // Sagt es darf ein neue Collection erstellt werden
                ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
            $pmc->forProperty('team.*')
                ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE);
    }

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function updateAction(\BBS\ConnectYou\Domain\Model\Project $project) {
        $project->getClient()->setProject($project);
        $this->clientRepository->update($project->getClient());
        $this->projectRepository->update($project);
		$this->addFlashMessage('Projekt "' . $project->getName() . '" wurde geupdatet.');
		$this->redirect('index');
	}

	/**
	 * @param \BBS\ConnectYou\Domain\Model\Project $project
	 * @return void
	 */
	public function deleteAction(\BBS\ConnectYou\Domain\Model\Project $project) {
        // Bei einer solchen DB-Beziehung muss zuerst das Projekt bei den Studenten entfernt werden
        foreach($project->getTeam() as $t){
            $t->removeProject();
            $this->studentRepository->update($t);
        }

        // Nachdem die Beziehung aufgelöst wird kann das Projekt entfernt werden.
		$this->projectRepository->remove($project);

        // Ist nötig da Flow nichts persisted was nicht über POST(statt GET) gemacht wird (leider noch keine möglichkeit über f:link.action)
        $this->persistenceManager->persistAll();

        $this->addFlashMessage('Projekt "' . $project->getName() . '" wurde gelöscht.');
		$this->redirect('index');
	}

    /**
     * Archiviert ein Projekt
     * @param \BBS\ConnectYou\Domain\Model\Project $project
     * @return void
     */
    public function archiveprojectAction(\BBS\ConnectYou\Domain\Model\Project $project) {
        $project->setArchived(TRUE);
        $this->projectRepository->update($project);

        // Ist nötig da Flow nichts persisted was nicht über POST(statt GET) gemacht wird (leider noch keine möglichkeit über f:link.action)
        // link: http://stackoverflow.com/questions/18355310/flow3-definitive-guide-cant-save-blog-in-database
        $this->persistenceManager->persistAll();

        $this->addFlashMessage('Projekt "' . $project->getName() . '" wurde archiviert.');
        $this->redirect('index');
    }

    /**
     * Zeigt die Archivierten Projekte in Tabellenform an (durchsuchbar)
     * @return void
     */
    public function archiveAction(){
        $this->view->assign('projects', $this->projectRepository->findArchivedProjects());
    }

    /**
     * Finde ob User Teil eines Projektes ist (Wenn ja wird ein Link zu einer Pinnwand gerendert)
     */
    protected function findUserProject(){
        $projectDesUsers = NULL;
        // Zuerst der Party
        $party = $this->securityContext->getAccount()->getParty();

        // Initalisiert die Variable für spätere nutzung
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles())){
            $projectDesUsers = $party->getProject();
        }


        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>
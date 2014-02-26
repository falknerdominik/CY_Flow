<?php
namespace BBS\ConnectYou\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use BBS\ConnectYou\Utility as Utility;

/**
 * PDF Generation mithilfe von https://github.com/mneuhaus/Famelo.PDF
 * Class AdminController
 * @package BBS\ConnectYou\Controller
 */
class AdminController extends \TYPO3\Flow\Mvc\Controller\ActionController {

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
     * Diese Funktion wird immer aufgerufen wenn eine View dieses Controllers aufgerufen wird, d.h. wird sie dazu
     * verwenden 'globale' Variablen für die Views festzusetzen (z.b.: Nutzername für etc wird den Views immer übergeben)
     *
     * @return void
     */
    protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view){
        // finde das zugewiesene Projekt wenn vorhanden und der Nutzer kein Lehrer ist
        if(!in_array("BBS.ConnectYou:Teacher", $this->securityContext->getAccount()->getRoles()) || !in_array("BBS.ConnectYou:Client", $this->securityContext->getAccount()->getRoles())){
            $view->assign('userproject', $this->findUserProject());
        }

        // Benutzername für Benutzermenü
        $view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());
    }

    /**
     * Zeigt eine Übesicht an
     *
	 * @return void
	 */
	public function indexAction() {

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        $this->view->assign('projects', $this->projectRepository->findAll());


	}

    /**
     * Generiert mithilfe von Famelo.PDF(https://github.com/mneuhaus/Famelo.PDF) eine PDF übericht über aktuelle Projekte und gibt diese als download aus
     *
     * @return void
     */
    public function activeprojectspdfAction() {
        $pdf = new \Famelo\PDF\Document("BBS.ConnectYou:ProjectList");
        $pdf->assign('projects', $this->projectRepository->findActiveProjects());
        $pdf->assign('date', date('d.m.Y'));
        // finde das zugewiesene Projekt wenn vorhanden

        $pdf->download('ProjectList' . date('d.m.Y') . '.pdf');

        $this->redirect('index', 'Admin');
    }

    /**
     * Generiert mithilfe von Famelo.PDF(https://github.com/mneuhaus/Famelo.PDF) eine PDF übericht über archivirten Projekte und gibt diese als download aus
     *
     * @return void
     */
    public function archivedprojectspdfAction() {
        $pdf = new \Famelo\PDF\Document("BBS.ConnectYou:ProjectList");
        $pdf->assign('projects', $this->projectRepository->findArchivedProjects());
        $pdf->assign('date', date('d.m.Y'));
        // finde das zugewiesene Projekt wenn vorhanden

        $pdf->download('ProjectList' . date('d.m.Y') . '.pdf');

        $this->redirect('index', 'Admin');
    }

    /**
     * Generiert mithilfe von Famelo.PDF(https://github.com/mneuhaus/Famelo.PDF) eine PDF übericht über aktuelle Projekte und gibt diese als download aus
     *
     * @return void
     */
    public function clientspdfAction() {
        $pdf = new \Famelo\PDF\Document("BBS.ConnectYou:ClientList");
        $pdf->assign('clients', $this->clientRepository->findAll());
        $pdf->assign('date', date('d.m.Y'));
        $pdf->download('ClientList' . date('d.m.Y') . '.pdf');

        $this->redirect('index', 'Admin');
    }

    /**
     * exportiert die ausgewählten Projekte
     *
     * @param string Host
     * @param string User
     * @param string Passwort
     * @param string Datenbankname
     * @param string Tabellenname
     * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Project> Projekte
     * @return void
     */
    public function exportprojectsAction($host, $user, $pass, $dbname, $tbname, $projects){
        $link = mysql_connect($host, $user, $pass);
        mysql_select_db($dbname, $link);

        // Querys
        $statements = array();

        // Check connection
        if (mysqli_connect_errno())
        {
            $this->addFlashMessage("Fehler beim Verbinden: " . mysqli_connect_error());
        }

        // Query Zusammenstellen
        foreach($projects as $project){
            $statements[] = 'INSERT INTO ' . $tbname . ' (name, beschreibung, typ) VALUES ("' . $project->getName() . '","' . $project->getDescription() . '","' . $project->getType() . '");';
        }

        foreach($statements as $statement){
            mysql_query($statement, $link);
        }
        mysql_close($link);

        $this->addFlashMessage("In Datenbank verspeichert");
        $this->redirect('export');
    }


    /**
     * Finde ob User Teil eines Projektes ist (Wenn ja wird ein Link zu einer Pinnwand gerendert)
     */
    protected function findUserProject(){
        // Zuerst der Party
        $party = $this->securityContext->getAccount()->getParty();

        // Initalisiert die Variable für spätere nutzung
        $projectDesUsers = $party->getProject();

        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>
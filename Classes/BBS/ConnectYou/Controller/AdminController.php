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
     * Zeigt eine Übesicht an
     *
	 * @return void
	 */
	public function indexAction() {

        // Für jede View - Anzeigen des Benutzernamens .. Link zur Pinnwand
        $this->view->assign('username', $this->securityContext->getAccount()->getAccountIdentifier());

        // finde das zugewiesene Projekt wenn vorhanden
        $userproject = $this->findUserProject();
        if($userproject){
            $this->view->assign('userproject', $userproject);
        }


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
     * Zeigt die View an um Projekt zu Exportieren (in andere Datenbank)
     *
     * @return void
     */
    public function exportAction(){
        $this->view->assign('projects', $this->projectRepository->findAll());
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

        $this->addFlashMessage("Geupdatet!");
        $this->redirect('export');
    }


    /**
     * Finde ob User Teil eines Projektes ist (Wenn ja wird ein Link zu einer Pinnwand gerendert)
     */
    protected function findUserProject(){
        // Zuerst der Party
        $account = $this->securityContext->getAccount()->getParty();

        // Alle Projekte Laden
        $projects = $this->projectRepository->findAll();

        // Initalisiert die Variable für spätere nutzung
        $projectDesUsers = NULL;

        if($projects){ // Prüft ob Projekte vorhanden sind
            // Wenn der User ein Client ist
            foreach ($projects as $project) {// Gehe durch alle Projekte
                if($project->getClient() && $project->getClient()->getName() == $account->getName()) // Client != NULL und der Name des Clienten mit dem eingeloggten User Überinstimmt
                    $projectDesUsers = $project; // Das Projekt in dem der User teilnimmt
            }



            // Wenn der User ein Student ist
            foreach ($projects as $project) { // Gehe durch alle Projekte
                if(count($project->getTeam(), COUNT_RECURSIVE) > 1){ // Prüft ob die ArrayCollection mehr als 1 Objekt enthält (obj->isEmpty() funktioniert nicht
                    foreach($project->getTeam() as $teammember){ // Gehe durch alle Teammitglieder
                        if($teammember->getName() == $account->getName())
                            $projectDesUsers = $project; // Das Projekt in dem der User teilnimmt
                    }
                }
            }
        }

        // Returned das Projekt ... Wenn Gefunden Sonst NULL
        return $projectDesUsers;
    }

}

?>
<?php
namespace BBS\ConnectYou\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "BBS.ConnectYou".        *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class StudentRepository extends Repository {

    /**
     * @Flow\Inject
     * @var \BBS\ConnectYou\Domain\Repository\ProjectRepository
     */
    protected $projectRepository;

	// add customized methods here
    /**
     * Gibt alle Studenten ohne Projekt zurück und alle die Bereits am Projekt teilnehmen
     *
     * @param \BBS\ConnectYou\Domain\Model\Project $project
     * @return array
     */
    public function findStudentsWithoutProjectsAndStudentsOfProject($project){
        // Alle Studenten ohne Projekte
        $searchedStudents = $this->findStudentsWithoutProjects();

        // Hinzufügen der Studenten im Projekt
        foreach($project->getTeam() as $t){
            $searchedStudents[] = $t;
        }

        // return Projects
        return $searchedStudents;
    }

    public function findStudentsWithoutProjects(){
        $searchedStudents = array();
        $allStudents = $this->findAll();

        foreach($allStudents as $s){
            if(!$s->getProject()){
                $searchedStudents[] = $s;
            }
        }

        return $searchedStudents;
    }
}
?>
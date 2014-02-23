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
class ProjectRepository extends Repository {

	// add customized methods here
    /**
     * Findet alle Aktiven Projekte und gibt diese in einem Array zurück
     * @return array
     */
    public function findActiveProjects() {
        $allProjects = $this->findAll();
        $activeProjects = array();

        foreach($allProjects as $project){
            if($project->getArchived() == FALSE)
                $activeProjects[] = $project;
        }

        return $activeProjects;

    }

    /**
     * Findet alle archivierten Projekte und gibt diese in einem Array zurück
     * @return array
     */
    public function findArchivedProjects(){
        $allProjects = $this->findAll();
        $activeProjects = array();

        foreach($allProjects as $project){
            if($project->getArchived() == TRUE)
                $activeProjects[] = $project;
        }

        return $activeProjects;
    }

}
?>
<?php
namespace BBS\ConnectYou\ViewHelpers;
/**
 * Dieser ViewHelper rendert ein Array von Widgets eines Projektes
 *
 * = Examples =
 *
 * <code title="Example">
 * <CY:widget>{project.widgetsPublic}</CY:widget>
 * </code>
 * <output>
 *
 * </output>
 *
 * @api
 */
class WidgetViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * @param \Doctrine\Common\Collections\Collection<\BBS\ConnectYou\Domain\Model\Widget> $widgets
     * @return string HTML Output
     */
    public function render($widgets = NULL){

        if($widgets){
            return "TODO Implement";
        }else{
            return '<h1 class="bgMessage">Noch nichts vorhanden</h1>';
        }

    }
}

?>
<?php
/**
 * PageSliceController.php
 *
 * @author Bram de Leeuw
 * Date: 7/10/16
 */


/**
 * PageSliceController
 */
class PageSliceControllerExtension extends Extension
{

    /**
     * @var array
     */
    private static $allowed_actions = array(
        'handleSlice'
    );


    /**
     * Handle the slice
     *
     * @return bool
     */
    public function handleSlice()
    {
        if (!$id = $this->owner->getRequest()->param('ID')) {
            return false;
        }

        $sliceRelations = array();
        if (!$hasManyRelations = $this->owner->data()->hasMany()) {
            return false;
        }

        foreach ($hasManyRelations as $relationName => $relationClass) {
            if ($relationClass == 'PageSlice' || is_subclass_of($relationClass, 'PageSlice')) {
                $sliceRelations[] = $relationName;
            }
        }

        $slice = null;
        foreach ($sliceRelations as $sliceRelation) {
            if ($slice) break;
            $slice = $this->owner->data()->$sliceRelation()->find('ID', $id);
        }

        if (!$slice) user_error('No slice found', E_USER_ERROR);

        return $slice->getController();
    }
}
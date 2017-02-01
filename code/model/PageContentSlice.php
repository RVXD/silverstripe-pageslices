<?php

use Broarm\Silverstripe\PageSlices\PageSlice;
use Broarm\Silverstripe\PageSlices\PageSliceController;

/**
 * Class PageContentSlice
 *
 * @package Broarm\Silverstripe\PageSlices
 *
 * @method \Page Parent
 */
class PageContentSlice extends PageSlice
{
    private static $db = array();

    private static $has_one = array();

    private static $slice_image = 'pageslices/images/TextSlice.png';

    private static $defaults = array(
        'Title' => 'Page content'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Title');

        $notice = _t(
            'PageContentSlice.ABOUT',
            'This section holds the content of the parent page. To edit, simply edit the parent\'s content field'
        );
        $fields->addFieldsToTab('Root.Main', array(
            LiteralField::create(
                'Notification',
                "<p class='message notice'>{$notice}</p>"
            )
        ));

        return $fields;
    }
}


/**
 * Class PageContentSlice_Controller
 *
 * @package Broarm\Silverstripe\PageSlices
 */
class PageContentSlice_Controller extends PageSliceController
{
    private static $allowed_actions = array();

    public function init()
    {
        parent::init();
    }

    /**
     * Look for content slices that match any of the parents class ancestry
     * The slice name is composed of the class name + 'ContentSlice'
     *
     * @return \HTMLText
     */
    public function getTemplate()
    {
        $sliceAncestry = explode(',', implode('ContentSlice,', array_reverse($this->Parent()->getClassAncestry())));
        array_pop($sliceAncestry);

        return $this->Parent()->renderWith($sliceAncestry, array('ID' => $this->ID));
    }
}
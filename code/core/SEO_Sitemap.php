<?php

/**
 * Adds the save button to the SEO admin CMS form
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_Sitemap {

    /**
     * @since version 1.2
     *
     * @var
     **/
	private $html;

    /**
     * 
     *
     * @since version 1.2
     *
     * @return
     **/
	public function getSitemapHTML()
    {
        $pages = SiteTree::get()->filter(array(
            'ClassName:not' => 'ErrorPage',
            'ParentID'      => 0
        ))->Sort('Sort','ASC');

        $this->getChildPages($pages);

        return $this->html;
    }

    /**
     * 
     *
     * @since version 1.2
     *
     * @param 
     *
     * @return
     **/
    private function getChildPages($pages)
    {
        $this->html .= '<ul>';

        foreach($pages as $page):
            $this->html .= '<li><a href="'.$this->URL.$page->Link().'">'.$page->Title.'</a>';

            $children = SiteTree::get()->filter(array(
                'ParentID' => $page->ID
            ))->Sort('ID','ASC');

            $this->getChildPages($children);

            $this->html .= '</li>';
        endforeach;

        $this->html .= '</ul>';

    }
}
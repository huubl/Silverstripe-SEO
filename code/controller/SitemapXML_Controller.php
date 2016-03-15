<?php

/**
 * XML sitemap controller
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SitemapXML_Controller extends Page_Controller {

    /**
     * @since version 1.2
     *
     * @var array $url_handlers Push requests to sitemap.xml to the getSitemap method
     **/
    private static $url_handlers = array(
        '' => 'getSitemap'
    );

    /**
     * @since version 1.2
     *
     * @var array $objects An array of objects with pages to include in the sitemap
     **/
    private $objects;

    /**
     * @since version 1.2
     *
     * @var string $xml The XML to return
     **/
    private $xml;

    /**
     * @since version 1.2
     *
     * @var string $url The URL to use for the current sitemap page
     **/
    private $url;

    /**
     * Set properties for this class and call the sitemap render method
     *
     * @since version 1.2
     *
     * @return void
     **/
    public function init()
    {
        parent::init();
        
        header("Content-Type: application/xml");

        $this->objects = Config::inst()->get('SitemapXML_Controller', 'objects');

        $this->url = substr(Director::AbsoluteBaseURL(),0,-1);

        $this->getSitemap();

        echo $this->xml;
        die;
    }

    /**
     * Return an encoded string compliant with XML sitemap standards
     *
     * @since version 1.2
     *
     * @param string $value A sitemap value to encode
     *
     * @return string
     **/
    public function Encode($value)
    {
        return trim(urlencode($value));
    }

    /**
     * Loops through the various page objects and sets the sitemap XML
     *
     * @since version 1.2
     *
     * @return void
     **/
    private function getSitemap()
    {
        $this->xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
        foreach($this->objects as $object){
            foreach($object::get()->Sort('Priority DESC') as $page){
                $this->xml .= $this->getPageXML($page);
            }
        }
        $this->xml .= '</urlset>';
    }

    /**
     * Checks if this page should be indexed, if so renders a page object SEO 
     * values into a XML sitemap entry 
     *
     * @since version 1.2
     *
     * @param object $page An object with the SEO extension attached
     *
     * @return string
     **/
    private function getPageXML($page)
    {
        if($page->Robots !== 'noindex,nofollow'){
            return $this->customise(array(
                'Page' => $page,
                'URL'  => $this->url
            ))->renderWith('SitemapXML');
        }
    }
}
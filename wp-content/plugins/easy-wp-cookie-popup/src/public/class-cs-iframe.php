<?php

namespace cookimize;

class CS_Iframe
{
    /**
     * CS_Iframe constructor.
     */
    public function __construct()
    {
    }
    
    /**
     * return instance from class
     */
    public static function get_instance()
    {
        new CS_Iframe();
    }
    
    /**
     * @param $content
     *
     * @return null|string|string[]
     */
    public function search_frames( $content )
    {
    }
    
    /**
     * @param $tags
     *
     * @return string
     */
    public function replace_with_alternate_text( $tags )
    {
    }

}
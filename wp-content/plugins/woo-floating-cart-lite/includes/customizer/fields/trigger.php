<?php

Kirki::add_field( self::$config_id, array(
    'settings' => self::field_id( 'trigger_features' ),
    'section'  => self::section_id( 'trigger' ),
    'type'     => 'xt-premium',
    'default'  => array(
    'type'  => 'image',
    'value' => self::$parent->plugin_url() . 'includes/customizer/assets/images/trigger.png',
    'link'  => self::$parent->fs()->get_upgrade_url(),
),
) );
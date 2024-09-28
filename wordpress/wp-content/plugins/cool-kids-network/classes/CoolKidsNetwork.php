<?php

class CoolKidsNetwork
{
    public function __construct()
    {

        register_activation_hook(COOL_KIDS_PLUGIN_PATH . 'index.php', array($this, 'activate_plugin'));

        register_deactivation_hook(COOL_KIDS_PLUGIN_PATH . 'index.php', array($this, 'deactivate_plugin'));

    }

    /**
     * Create custom roles when activating the plugin.
     *
     * @return void
     */
    public function activate_plugin(): void
    {
        add_role('cool_kid', 'Cool Kid', array('read' => true));
        add_role('cooler_kid', 'Cooler Kid', array('read' => true));
        add_role('coolest_kid', 'Coolest Kid', array('read' => true));
    }

    /**
     * Delete custom roles when deactivating the plugin.
     *
     * @return void
     */
    public function deactivate_plugin(): void
    {
        remove_role('cool_kid');
        remove_role('cooler_kid');
        remove_role('coolest_kid');
    }

}

if (class_exists('CoolKidsNetwork')) {
    $coolKidsNetwork = new CoolKidsNetwork();
}

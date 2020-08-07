<?php
namespace KMA\Modules\KMASocial;

class SocialIcons extends \KeriganSolutions\SocialMedia\SocialSettingsPage {

    public function use()
    {
        if (is_admin()) {
            parent::createPage();
        }
    }
}
<?php
namespace KMA\Modules\KMAServices;

class SocialIcons extends \KeriganSolutions\SocialMedia\SocialSettingsPage {

    public function use()
    {
        if (is_admin()) {
            parent::createPage();
        }
    }
}
<?php


namespace TUDublin;

use TUDublin\dbObjects\CoffeeshopOwner;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;

class OwnerController extends Controller
{

    private $ownerRepo;

    public function __construct()
    {
        parent::__construct();

        $this->ownerRepo = new CoffeeshopOwnerRepository();
    }

    public function ownerProfilePage()
    {
        $owner = $this->ownerRepo->find($this->getOwnerId());

        $template = 'ownerProfile.html.twig';
        $args = [
            'owner' => $owner,
        ];

        $this->renderPage($template, $args);
    }

    public function editOwnerProfilePage()
    {
        $owner = $this->ownerRepo->find($this->getOwnerId());

        $template = 'editOwnerProfile.html.twig';
        $args = [
            'owner' => $owner,
        ];

        $this->renderPage($template, $args);
    }

    public function updateOwnerProfile()
    {
        $ownerName = filter_input(INPUT_POST, 'owner_name');
        $ownerBio = filter_input(INPUT_POST, 'owner_bio');
        $inputValidated = true;

        if (strlen($ownerName) > 120) {
            $inputValidated = false;
            $this->logError('Your name is too long');
        }
        if (strlen($ownerBio) > 500){
            $inputValidated = false;
            $this->logError('Your Bio is too long');
        }

        if ($inputValidated) {
            $o = $this->ownerRepo->find($this->getOwnerId());

            $o->setName($ownerName);
            $o->setBio($ownerBio);

            $result = $this->ownerRepo->update($o);
        }

        if ($result) {
            $this->logMessage('Successfully updated your owner profile');
        } else {
            $this->logError('Could not edit profile, please try again later');
        }

        $this->redirect('/', [
            'page' => 'owners_profile',
        ]);
    }

    private function getOwnerId()
    {
        return $_SESSION['owner_id'];
    }

}
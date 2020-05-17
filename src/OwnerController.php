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

    public function updateOwnerProfile(){
        $ownerName = filter_input(INPUT_POST, 'owner_name');
        $ownerBio = filter_input(INPUT_POST, 'owner_bio');

        $o = $this->ownerRepo->find($this->getOwnerId());
        $o->setName($ownerName);
        $o->setBio($ownerBio);
        $this->ownerRepo->update($o);

        $this->redirect('/', [
            'page'=>'owners_profile',
        ]);
    }

    private function getOwnerId(){
        return $_SESSION['owner_id'];
    }

}
<?php


namespace TUDublin;


use TUDublin\dbObjects\Coffeeshop;
use TUDublin\dbObjects\CoffeeshopAddressRepository;
use TUDublin\dbObjects\CoffeeshopCommentRepository;
use TUDublin\dbObjects\CoffeeshopOwnerRepository;
use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\CoffeeshopReviewRepository;
use TUDublin\dbObjects\MenuItemRepository;
use TUDublin\dbObjects\PictureRepository;
use TUDublin\dbObjects\UserRepository;

class CoffeeshopController extends Controller
{
    private $csRepo;
    private $csAddressRepo;
    private $csCommentRepo;
    private $csOwnerRepo;
    private $csReviewRepo;
    private $menuItemRepo;
    private $pictureRepo;
    private $userRepo;

    public function __construct()
    {
        parent::__construct();

        $this->csRepo = new CoffeeshopRepository();
        $this->csAddressRepo = new CoffeeshopAddressRepository();
        $this->csCommentRepo = new CoffeeshopCommentRepository();
//        $this->csOwnerRepo = new CoffeeshopOwnerRepository();
        $this->csReviewRepo = new CoffeeshopReviewRepository();
        $this->menuItemRepo = new MenuItemRepository();
//        $this->pictureRepo = new PictureRepository();
//        $this->userRepo = new UserRepository();

    }


    public function coffeeshopPage()
    {
        $csid = filter_input(INPUT_GET, 'csid');

        $cs = $this->csRepo->find($csid);
        $reviews = $this->csReviewRepo->getAllReviewsForCoffeeshop($csid);
        $comments = $this->csCommentRepo->getPublishedCommentsForCoffeeshop($csid);

        $template = 'coffeeshop.html.twig';
        $args = [
            'coffeeshop' => $cs,
            'reviews' => $reviews,
            'comments' => $comments,
        ];

         $this->renderPage($template, $args);
    }

    public function coffeeshopsListPage()
    {
        $coffeeshops = $this->csRepo->findAll();

        $template = 'coffeeshoplist.html.twig';
        $args = [
            'coffeeshop_list' => $coffeeshops,
        ];

        $this->renderPage($template, $args);
    }

    public function editCoffeeshopPage()
    {
        $csid = filter_input(INPUT_GET, 'csid');

        $cs = $this->csRepo->find($csid);
        $cs_mi = $this->menuItemRepo->getAllMenuItems($cs->getMenuId());


        $template = 'editcoffeeshop.html.twig';
        $args = [
            'coffeeshop' => $cs,
            'menuitems' => $cs_mi,
        ];

        $this->renderPage($template, $args);
    }

    public function ownersCoffeeshopsPage(){
        $owner_id = $_SESSION['owner_id'];

        if ($owner_id){
            $cs = $this->csRepo->getAllCoffeeshopsFor($owner_id);
//            $cs_mi = $this->menuItemRepo->getAllMenuItems($cs->getMenuId());
//            $owners_mi = [] ; //getMenuItemsForOwner($cs->getOwnerId());


            $template = 'ownerscoffeeshops.html.twig';
            $args = [
                'coffeeshops' => $cs,
            ];

            $this->renderPage($template, $args);
        }else {
            $this->logError('We could not verify your ownership');
            $this->redirect('/');
        }
    }

    public function updateCoffeeshop()
    {
        $csId = filter_input(INPUT_POST, 'coffeeshop_id');
        $csName = filter_input(INPUT_POST, 'coffeeshop_name');
        $csSummary = filter_input(INPUT_POST, 'coffeeshop_summary');

        $cs = $this->csRepo->find($csId);

        $cs->setName($csName);
        $cs->setSummary($csSummary);

        $csUpdateResult =  $this->csRepo->update($cs);

        $addressStreet1 = filter_input(INPUT_POST, 'address_street1');
        $addressStreet2 = filter_input(INPUT_POST, 'address_street2');
        $addressCity = filter_input(INPUT_POST, 'address_city');
        $addressCounty = filter_input(INPUT_POST, 'address_county');
        $addressPostcode = filter_input(INPUT_POST, 'address_postcode');

        $adrs = $this->csAddressRepo->find($cs->getAddressId());
        $adrs->setStreet1($addressStreet1);
        $adrs->setStreet2($addressStreet2);
        $adrs->setCity($addressCity);
        $adrs->setCounty($addressCounty);
        $adrs->setPostcode($addressPostcode);

        $addressUpdateResult = $this->csAddressRepo->update($adrs);

        if ($csUpdateResult && $addressUpdateResult) {
            $this->logMessage('Update Successful');
        }else {
            if (!$csUpdateResult) {
                $this->logError('Could not update Coffee Shop');
            }
            if (!$addressUpdateResult){
                $this->logError('Could not update address');
            }
        }

        $this->redirect('/', [
            'page'=>'shop',
            'csid'=>$csId,
        ]);

    }

    public function addCoffeeshop($coffeeshopName)
    {
        $cs = new Coffeeshop();
        $cs->setName($coffeeshopName);
        $this->csRepo->create($cs);
    }

    /* DEPRACATED FUNCTIONS TODO remove after cleanup */

    /**
     * @return array
     * @deprecated
     */
    public function getCoffeeshops()
    {
        return $this->csRepo->findAll();
    }

    /**
     * @param $coffeeshopId
     * @return mixed|null
     * @deprecated
     */
    public function getCoffeeshop($coffeeshopId)
    {
        return $this->csRepo->find($coffeeshopId);
    }

    /**
     * @param $ownerId
     * @return array
     * @deprecated
     */
    public function getAllCoffeeshopsForOwner($ownerId)
    {
        return $this->csRepo->getAllCoffeeshopsFor($ownerId);
    }
}
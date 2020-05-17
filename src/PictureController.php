<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\PictureRepository;

class PictureController extends Controller
{

    const UPLOAD_DIR = __DIR__ . '/../public_html/img/uploads/';

    private $pictureRepo;
    private $csRepo;

    public function __construct()
    {
        parent::__construct();

        $this->csRepo = new CoffeeshopRepository();
        $this->pictureRepo = new PictureRepository();

    }

    public function savePictureForCoffeeshop()
    {

        $csId = filter_input(INPUT_GET, 'csid');
        $uploadfile = self::UPLOAD_DIR . basename($_FILES['coffeeshop_picture']['name']);

        $isSafeToUpload = true;
        if ($isSafeToUpload){
            if (move_uploaded_file($_FILES['coffeeshop_picture']['tmp_name'], $uploadfile)) {
                $this->logMessage('Upload Successful');
                $this->redirect('/',[
                    'page'=>'shop',
                    'csid'=>$csId,
                ]);

            } else {
                $this->logError('could not upload the file');
            }
        }

        $this->redirect('/',[
            'page'=>'edit_coffeeshop',
            'csid'=>$csId,
        ]);

    }

    private function runFileCheck()
    {
        // Checking $_FILES['upfile']['error'] value.
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->logError('No file detected');
                return false;
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $this->logError('Filesize is too large');
                return false;
                break;
            default:
                $this->logError('Unknown errors.');
                return false;
                break;
        }

        // Checking filesize here.
        if ($_FILES['upfile']['size'] > 1000000) {
            $this->logError('Exceeded filesize limit.');
            return false;
        }

        // Checking MIME Type.
        $finfo = new finfo(FILEINFO_MIME_TYPE);;

        if (false === array_search(
                $finfo->file($_FILES['upfile']['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                ),
                true
            )) {
            $this->logError('Invalid file format.');
            return false;
        }

        return true;
    }
}
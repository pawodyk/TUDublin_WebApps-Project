<?php


namespace TUDublin;


use TUDublin\dbObjects\CoffeeshopRepository;
use TUDublin\dbObjects\Picture;
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

        $isSafeToUpload = true; //$this->runFileCheck()'

        if ($_FILES['coffeeshop_picture']['size'] > 1000000) {
            $this->logError('Exceeded filesize limit.');
            $isSafeToUpload = false;
        }

        if ($isSafeToUpload){
            if (move_uploaded_file($_FILES['coffeeshop_picture']['tmp_name'], $uploadfile)) {

                try {
                    $cs = $this->csRepo->find($csId);

                    $p = new Picture();
                    $p->setName('Photo of Coffee Shop ID' . $csId);
                    $p->setFilename($_FILES['coffeeshop_picture']['name']);

                    $cs->setPictureId($this->pictureRepo->create($p));
                    $this->csRepo->update($cs);

                    $this->logMessage('Upload Successful');
                } catch (Exception $e){
                    $this->logError('could not save the file');
                }


                $this->redirect('/',[
                    'page'=>'shop',
                    'csid'=>$csId,
                ]);

            } else {
                $this->logError('could not upload the file');
            }
        } else{
            $this->logError('File upload is unsafe');
        }

        $this->redirect('/',[
            'page'=>'edit_coffeeshop',
            'csid'=>$csId,
        ]);

    }


    private function generateRandomName(){
        $output = '';
        for ($i = 0; $i < 10; $i++){
            $output .= rand(0,9);
        }
        return $output;
    }

    private function runFileCheck($file)
    {
        // Checking $file['error'] value.
        switch ($file['error']) {
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
        if ($file['size'] > 1000000) {
            $this->logError('Exceeded filesize limit.');
            return false;
        }

        // Checking MIME Type.
        $finfo = new finfo(FILEINFO_MIME_TYPE);;

        if (false === array_search(
                $finfo->file($file['tmp_name']),
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
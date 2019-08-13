<?php


namespace App\Tests\Controller;


use App\Controller\CreatePhoneImageAction;
use App\Entity\Phone;
use App\Entity\PhoneImage;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;

class CreatePhoneImageActionTest extends TestCase
{
    public function testDummy()
    {
        $this->assertEquals(1,1);
    }

    //TODO: Test on errors
    // -Null file
    // -no phone

    public function testCreationPhoneImage()
    {

        $phone = new Phone();

        //"Mocking" the file upload and placing it into the fileBag
        $tmpFile = $this->createTempFile();
        $bag = new FileBag(array('imageFile' => array(
            'name' => basename($tmpFile),
            'type' => 'text/plain',
            'tmp_name' => $tmpFile,
            'error' => 0,
            'size' => 100,
        )));


        $phoneRepositoryMock = $this->createMock(PhoneRepository::class);
        $phoneRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($phone);


        $requestMock = $this->createMock(Request::class);
        $requestMock->files = $bag;

        $controller = new CreatePhoneImageAction($phoneRepositoryMock);
        $resultImage = $controller($requestMock);

        //TODO: Add asserts to check that all is OK
        var_dump($resultImage);

    }

    /**
     * create a temporary file
     * @return bool|string
     */
    protected function createTempFile()
    {
        //create a file in the temporary directory.
        // need the @ in front to avoid PHP7 warnings that fail the test
        return @tempnam(sys_get_temp_dir().'/form_test', 'FormTest');
    }


}
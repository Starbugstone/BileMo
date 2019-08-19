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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePhoneImageActionTest extends TestCase
{
    protected $phoneRepositoryMock;
    protected $requestMock;

    protected function setUp()
    {
        $this->phoneRepositoryMock = $this->createMock(PhoneRepository::class);
        $this->requestMock = $this->createMock(Request::class);
    }

    protected function tearDown()
    {
        $this->phoneRepositoryMock = null;
        $this->requestMock = null;
    }

    public function testCreationPhoneImage()
    {

        $phone = new Phone();
        $phone->setName('DummyPhone');

        $this->phoneRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($phone);

        //Adding the image to the request
        $this->requestMock->files = $this->createFileBag();

        $controller = new CreatePhoneImageAction($this->phoneRepositoryMock);
        $resultImage = $controller($this->requestMock);

        $this->assertInstanceOf(PhoneImage::class, $resultImage);
        $this->assertInstanceOf(Phone::class, $resultImage->getPhone());

        //Make sure we are getting the proper phone back
        $this->assertEquals('DummyPhone', $resultImage->getPhone()->getName());
    }

    public function testNullPhone()
    {
        $this->phoneRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);

        //Adding the image to the request
        $this->requestMock->files = $this->createFileBag();

        $controller = new CreatePhoneImageAction($this->phoneRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $controller($this->requestMock);
    }

    public function testNullImage()
    {

        $phone = new Phone();

        //Creating a null file upload
        $bag = new FileBag(array('imageFile' => array(
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'error' => UPLOAD_ERR_NO_FILE,
            'size' => 0,
        )));


        $this->phoneRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($phone);

        //Adding the image to the request
        $this->requestMock->files = $bag;

        $controller = new CreatePhoneImageAction($this->phoneRepositoryMock);

        $this->expectException(BadRequestHttpException::class);
        $controller($this->requestMock);
    }

    /**
     * create a temporary file
     * @return bool|string
     */
    protected function createTempFile()
    {
        //create a file in the temporary directory.
        // need the @ in front to avoid PHP7 warnings that fail the test
        return @tempnam(sys_get_temp_dir() . '/form_test', 'FormTest');
    }

    protected function createFileBag(): FileBag
    {
        $tmpFile = $this->createTempFile();
        $bag = new FileBag(array('imageFile' => array(
            'name' => basename($tmpFile),
            'type' => 'text/plain',
            'tmp_name' => $tmpFile,
            'error' => 0,
            'size' => 100,
        )));

        return $bag;
    }


}
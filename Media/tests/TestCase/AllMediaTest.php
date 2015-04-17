<?php

namespace Media\Test\TestCase;

use Media\Controller\MediasController;
use Cake\TestSuite\TestCase;
use Cake\TestSuite\TestSuite;
use Cake\TestSuite\CakeTestSuite;
use Cake\View\View;
use Cake\ORM\Table;

class Members extends Table{}
class Page extends Table{}


class TestMediasController extends MediasController {
	public function canUploadMedias($ref, $ref_id){
        if($ref_id == '2'){
            return false;
        }
        return true;
    }

}

function test_move_uploaded_file($filename, $destination){
    return copy($filename, $destination);
}


class AllMediaTest extends TestSuite {
    public static function suite() {
        $suite = new TestSuite('Plugin Media');
        $suite->addTestDirectoryRecursive(TESTS . 'TestCase');
        return $suite;
    }
}

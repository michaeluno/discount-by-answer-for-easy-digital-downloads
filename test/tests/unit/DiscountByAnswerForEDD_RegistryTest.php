<?php

/*
 $this->assertEquals()
$this->assertContains()
$this->assertFalse()
$this->assertTrue()
$this->assertNull()
$this->assertEmpty()
*/

class DiscountByAnswerForEDD_RegistryTest extends \Codeception\Test\Unit {

    public function testGetPluginURL() {

    }

    public function testSetAdminNotice() {

    }

    public function testSetUp() {

        DiscountByAnswerForEDD_Registry::$sDirPath = '';

        DiscountByAnswerForEDD_Registry::setUp();
        $this->assertEquals(
            dirname( DiscountByAnswerForEDD_Registry::$sFilePath ),
            DiscountByAnswerForEDD_Registry::$sDirPath
        );

    }

    public function testReplyToShowAdminNotices() {

    }

    public function testRegisterClasses() {

        $_aClassFiles = $this->getStaticAttribute( 'DiscountByAnswerForEDD_Registry', '___aAutoLoadClasses' );
        DiscountByAnswerForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertAttributeEquals( $_aClassFiles , '___aAutoLoadClasses', 'DiscountByAnswerForEDD_Registry' );

        $_aClassFiles = array( 'SomeClass' => 'SomeClass.php' );
        DiscountByAnswerForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertAttributeNotEquals(
            $_aClassFiles ,
            '___aAutoLoadClasses',
            'DiscountByAnswerForEDD_Registry'
        );

        $this->assertArrayHasKey(
            'SomeClass',
            $this->getStaticAttribute( 'DiscountByAnswerForEDD_Registry', '___aAutoLoadClasses' ),
            'The key just set does not exist.'
        );

    }

    public function testReplyToLoadClass() {

        $this->assertFalse(
            class_exists( 'JustAClass' ),
            'The JustAClass class must not exist at this stage.'
        );
        include( codecept_root_dir() . '/tests/include/class-list.php' );
        DiscountByAnswerForEDD_Registry::registerClasses( $_aClassFiles );
        $this->assertTrue(
            class_exists( 'JustAClass' ),
            'The class auto load failed with the DiscountByAnswerForEDD_Registry::registerClasses() method.'
        );

    }

}

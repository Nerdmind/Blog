<?php
require_once 'core/functions.php';

class BlogTest extends PHPUnit_Framework_TestCase
# Example from https://github.com/travis-ci-examples/php
{
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp()
    {
        $this->pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE hello (what VARCHAR(50) NOT NULL)");
    }

    public function tearDown()
    {
        $this->pdo->query("DROP TABLE hello");
    }

    public function testmakeSlugURL()
    {
        $test = makeSlugURL('http://Test.de');

        $this->assertEquals('test.de', $test);
    }
} 
?>

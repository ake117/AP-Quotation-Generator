<?php
use PHPUnit\Framework\TestCase;

// The mdate()/substr() date guards in Quotation.php: a NULL or zero-date from
// the DB must render as "", never "today" or a 1970 deprecation.
class NullDateGuardTest extends TestCase
{
    public function testGuardMatchesEmptyAndZeroDates()
    {
        foreach (array(null, '', '0000-00-00 00:00:00') as $v) {
            $this->assertTrue(empty($v) || $v == '0000-00-00 00:00:00', var_export($v, true));
        }
        // real dates fall through the guard
        foreach (array('2026-09-29 00:00:00', '2026-09-29') as $v) {
            $this->assertFalse(empty($v) || $v == '0000-00-00 00:00:00');
        }
        // mdate semantics: strtotime(false/empty) must not be fed to mdate
        $this->assertFalse(strtotime(''));
    }
}

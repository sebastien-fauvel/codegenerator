<?php

namespace TestsCodeGenerator;

use CodeGenerator\Block;
use CodeGenerator\FileBlock;
use CodeGeneratorHelpers\TestHelper;

class CG_BlockTest extends \PHPUnit_Framework_TestCase {

    public function testOutdent() {
        $block = new FileBlock();
        $cases = array(
            "    foo"      => "foo",
            "foo"        => "foo",
            "    foo\nbar" => "foo\nbar",
            "            foo"  => "        foo",
        );
        foreach ($cases as $input => $expected) {
            $output = TestHelper::invokeMethod($block, '_outdent', array($input));
            $this->assertSame($expected, $output);
        }
    }

    public function testOutdentUntilSafe() {
        $block = new FileBlock();
        $cases = array(
            "    foo\nbar"     => "    foo\nbar",
            "        foo\n    bar" => "    foo\nbar",
            "            foo"      => "foo",
        );
        foreach ($cases as $input => $expected) {
            $output = TestHelper::invokeMethod($block, '_outdent', array($input, true));
            $this->assertSame($expected, $output);
        }
    }

    public function testIndent() {
        $block = new FileBlock();
        $cases = array(
            "foo\nbar"     => "    foo\n    bar",
            "    foo\n    bar" => "        foo\n        bar",
        );
        foreach ($cases as $input => $expected) {
            $output = TestHelper::invokeMethod($block, '_indent', array($input, true));
            $this->assertSame($expected, $output);
        }
    }

    public function testSetIndentation() {
        Block::setIndentation('  ');
        $block = new FileBlock();

        $output = TestHelper::invokeMethod($block, '_indent', array("foo", true));
        $this->assertSame("  foo", $output);
        $output = TestHelper::invokeMethod($block, '_outdent', array("  foo\n    bar", true));
        $this->assertSame("foo\n  bar", $output);

        Block::setIndentation('    ');
    }
}

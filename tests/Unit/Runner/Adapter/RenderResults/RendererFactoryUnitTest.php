<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\PackageRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestCaseRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RendererFactory;
use ThomasNordahlDk\Tester\TestCase;

class RendererFactoryUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . RendererFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testCreatePackageRenderer();
        $this->testCreateTestCaseRenderer();
        $this->testCreateAssertionResultRenderer();
    }

    private function testCreatePackageRenderer(): void
    {
        $tester = $this->tester;

        $expected = new PackageRenderer();
        $factory = new RendererFactory();

        $tester->assertEqual($expected, $factory->createPackageRenderer(), "package renderer");
    }

    private function testCreateTestCaseRenderer()
    {
        $tester = $this->tester;

        $expected = new TestCaseRenderer();
        $factory = new RendererFactory();
        $tester->assertEqual($expected, $factory->createTestCaseRenderer(), "test case renderer");

        $expected = new TestCaseRenderer(true);
        $factory = new RendererFactory(true);
        $tester->assertEqual($expected, $factory->createTestCaseRenderer(), "test case renderer, verbose");
    }

    private function testCreateAssertionResultRenderer()
    {
        $tester = $this->tester;

        $expected = new AssertionResultRenderer();
        $factory = new RendererFactory();
        $tester->assertEqual($expected, $factory->createAssertionResultRenderer(), "assertion result renderer");

        $expected = new AssertionResultRenderer(true);
        $factory = new RendererFactory(true);
        $tester->assertEqual($expected, $factory->createAssertionResultRenderer(), "assertion result renderer, verbose");
    }
}

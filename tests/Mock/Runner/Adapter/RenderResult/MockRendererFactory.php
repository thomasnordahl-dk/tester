<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult;


use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\PackageRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestCaseRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RendererFactory;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer\MockAssertionResultRenderer;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer\MockPackageRenderer;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer\MockTestCaseRenderer;

class MockRendererFactory extends RendererFactory
{
    public function createAssertionResultRenderer(): AssertionResultRenderer
    {
        return new MockAssertionResultRenderer();
    }

    public function createTestCaseRenderer(): TestCaseRenderer
    {
        return new MockTestCaseRenderer();
    }

    public function createPackageRenderer(): PackageRenderer
    {
        return new MockPackageRenderer();
    }
}

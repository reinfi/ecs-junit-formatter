<?php

declare(strict_types=1);

namespace Reinfi\EasyCodingStandard;

use Symplify\EasyCodingStandard\Console\Output\ExitCodeResolver;
use Symplify\EasyCodingStandard\Console\Style\EasyCodingStandardStyle;
use Symplify\EasyCodingStandard\Contract\Console\Output\OutputFormatterInterface;
use Symplify\EasyCodingStandard\ValueObject\Configuration;
use Symplify\EasyCodingStandard\ValueObject\Error\ErrorAndDiffResult;
use Symplify\EasyCodingStandard\ValueObject\Error\SystemError;

final class JUnitOutputFormatter implements OutputFormatterInterface
{
    public const NAME = 'junit';

    private readonly EasyCodingStandardStyle $easyCodingStandardStyle;

    private readonly ExitCodeResolver $exitCodeResolver;

    public function __construct(EasyCodingStandardStyle $easyCodingStandardStyle, ExitCodeResolver $exitCodeResolver)
    {
        $this->easyCodingStandardStyle = $easyCodingStandardStyle;
        $this->exitCodeResolver = $exitCodeResolver;
    }

    public function report(ErrorAndDiffResult $errorAndDiffResult, Configuration $configuration): int
    {
        $xml = $this->createXmlOutput($errorAndDiffResult);
        $this->easyCodingStandardStyle->writeln($xml);

        return $this->exitCodeResolver->resolve($errorAndDiffResult, $configuration);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    private function createXmlOutput(ErrorAndDiffResult $errorAndDiffResult): string
    {
        $result = '<?xml version="1.0" encoding="UTF-8"?>';

        $totalFailuresCount = $errorAndDiffResult->getErrorCount();
        $totalTestsCount = $errorAndDiffResult->getFileDiffsCount();

        $result .= sprintf(
            '<testsuite failures="%d" name="easy-coding-standard" tests="%d" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://raw.githubusercontent.com/junit-team/junit5/r5.5.1/platform-tests/src/test/resources/jenkins-junit.xsd">',
            $totalFailuresCount,
            $totalTestsCount
        );

        foreach ($errorAndDiffResult->getErrors() as $codingStandardError) {
            $fileName = $codingStandardError->getRelativeFilePath();
            $result .= $this->createTestCase(
                sprintf('%s:%s', $fileName, $codingStandardError->getLine()),
                $codingStandardError->getMessage()
            );
        }

        foreach ($errorAndDiffResult->getSystemErrors() as $systemError) {
            if ($systemError instanceof SystemError) {
                $result .= $this->createTestCase($systemError->getFileWithLine(), $systemError->getMessage());
            }
        }

        foreach ($errorAndDiffResult->getFileDiffs() as $codingStandardError) {
            $fileName = $codingStandardError->getRelativeFilePath();
            $result .= $this->createTestCase($fileName, $codingStandardError->getDiff());
        }
        $result .= '</testsuite>';

        return $result;
    }

    /**
     * Format a single test case
     */
    private function createTestCase(string $reference, string $message = null): string
    {
        $result = sprintf('<testcase name="%s">', $this->escape($reference));
        if ($message !== null) {
            $result .= sprintf('<failure type="%s" message="%s" />', 'ERROR', $this->escape($message));
        }
        $result .= '</testcase>';

        return $result;
    }

    /**
     * Escapes values for using in XML
     */
    private function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }
}

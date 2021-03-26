<?php


namespace App\Service\Mailer;


use RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BinaryRenderer implements RenderInterface
{

    private string $bin;

    public function __construct(string $bin)
    {
        $this->bin = $bin;
    }

    public function render(string $content): string
    {
        $this->checkExistBin();

        $arguments = [
            $this->bin,
            '-i',
            '-s',
            '--config.validationLevel',
            '--config.minify'
        ];

        $process = new Process($arguments);
        $process->setInput($content);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            throw new RuntimeException('Unable to compile MJML. Stack error: ' . $exception->getMessage());
        }

        return $process->getOutput();
    }

    private function checkExistBin(): bool
    {
        if (file_exists($this->bin)) {
            return true;
        } else {
            throw new RuntimeException('The file does not exist, please install it with the command `npm install mjml` or `yarn add mjml`.');
        }
    }
}
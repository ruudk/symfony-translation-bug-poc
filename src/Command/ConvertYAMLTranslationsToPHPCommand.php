<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Translation\Dumper\PhpFileDumper;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;

final class ConvertYAMLTranslationsToPHPCommand extends Command
{
    private $loader;

    private string $directory;

    public function __construct(LoaderInterface $loader, string $directory)
    {
        parent::__construct();

        $this->loader = $loader;
        $this->directory = $directory;
    }

    protected function configure() : void
    {
        $this->setName('convert-yaml-translations-to-php');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : ?int
    {
        $output->writeln('Converting all YAML translations to PHP...');

        $dumper = new PhpFileDumper();

        /**
         * @var Finder|SplFileInfo[] $finder
         */
        $finder = Finder::create()
            ->in($this->directory)
            ->files()
            ->name('*.yaml');

        $filesystem = new Filesystem();

        $count = 0;
        foreach ($finder as $file) {
            [$locale, $filename] = explode('/', $file->getRelativePathname());
            [$domain]            = explode('.', $filename);

            $catalogue = $this->loader->load($file, $locale, $domain);

            $dumper->dump($catalogue, ['path' => $file->getPath()]);

            $filesystem->remove($file->getPathname());

            ++$count;
        }

        $output->writeln(sprintf('Converted %d files.', $count));

        return 0;
    }
}


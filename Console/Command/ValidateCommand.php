<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Console\Command;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Module\Dir;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use JsonSchema\Validator;
use Symfony\Component\Yaml\Yaml;

class ValidateCommand extends Command
{
    private const NAME = 'lapis:validate';

    public function __construct(
        private Filesystem $filesystem,
        private Dir $moduleDirReader,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Validate generated LAPIS RLD files against schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $etcDir = $this->moduleDirReader->getDir('Fr3on_Lapis', Dir::MODULE_DOC_DIR);
        $schemaPath = $etcDir . DIRECTORY_SEPARATOR . 'rld-schema.json';

        if (!file_exists($schemaPath)) {
            $output->writeln('<error>Schema not found at ' . $schemaPath . '</error>');
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        $varDir = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $lapisPath = 'lapis';

        if (!$varDir->isExist($lapisPath)) {
            $output->writeln('<error>No generated files found in var/lapis/</error>');
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        $files = $varDir->readDirectory($lapisPath);
        $validator = new Validator();
        $hasError = false;

        foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $contentRaw = $varDir->readFile($file);
            
            $data = ($ext === 'yaml') 
                ? Yaml::parse($contentRaw, Yaml::PARSE_OBJECT_FOR_MAP)
                : json_decode($contentRaw);

            $validator->validate($data, (object)['$ref' => 'file://' . $schemaPath]);

            if ($validator->isValid()) {
                $output->writeln('<info>[PASS]</info> ' . $file);
            } else {
                $output->writeln('<error>[FAIL]</error> ' . $file);
                foreach ($validator->getErrors() as $error) {
                    $output->writeln(sprintf('  - [%s] %s', $error['property'], $error['message']));
                }
                $hasError = true;
            }
            $validator->reset();
        }

        return $hasError ? \Magento\Framework\Console\Cli::RETURN_FAILURE : \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}

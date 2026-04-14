<?php
/**
 * Copyright © fr3on. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fr3on\Lapis\Console\Command;

use Fr3on\Lapis\Model\Generator\RldGenerator;
use Fr3on\Lapis\Model\Generator\YamlSerializer;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    private const NAME = 'lapis:generate';

    public function __construct(
        private RldGenerator $generator,
        private YamlSerializer $serializer,
        private Filesystem $filesystem,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Generate LAPIS RLD declarations for all resources')
            ->addOption(
                'resource',
                'r',
                InputOption::VALUE_OPTIONAL,
                'Generate for specific resource only'
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Output format (yaml|json)',
                'yaml'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resourceOpt = $input->getOption('resource');
        $format = $input->getOption('format');
        
        $output->writeln('<info>Generating LAPIS declarations...</info>');

        $allData = $this->generator->generateAll();
        $varDir = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $lapisPath = 'lapis';
        
        if (!$varDir->isExist($lapisPath)) {
            $varDir->create($lapisPath);
        }

        foreach ($allData as $id => $data) {
            if ($resourceOpt && $id !== $resourceOpt) {
                continue;
            }

            $content = ($format === 'json') 
                ? json_encode($data, JSON_PRETTY_PRINT) 
                : $this->serializer->serialize($data);
            
            $filename = $lapisPath . DIRECTORY_SEPARATOR . $id . '.' . ($format === 'json' ? 'json' : 'yaml');
            $varDir->writeFile($filename, $content);
            
            $output->writeln(sprintf(
                '  - Generated <comment>%s</comment> (%d states, %d transitions)',
                $filename,
                count($data['states']),
                count($data['transitions'])
            ));
        }

        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }
}

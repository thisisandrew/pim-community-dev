<?php

namespace Pim\Bundle\CatalogBundle\Command;

use Akeneo\Bundle\StorageUtilsBundle\DependencyInjection\AkeneoStorageUtilsExtension;
use Pim\Component\Catalog\Query\Filter\Operators;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command that refreshes normalizedData for MongoDB Product collection
 *
 * @author    Remy Betus <remy.betus@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MongoDBRefreshNormalizedDataCommand extends ContainerAwareCommand
{
    /** @const int defines the batch size */
    const BATCH_SIZE = 100;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pim:product:refresh-mongodb-normalized-data')
            ->setDescription(
                'Refresh MongoDB normalizedData field for products '
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $storageDriver = $this->getContainer()->getParameter('pim_catalog_product_storage_driver');

        if (AkeneoStorageUtilsExtension::DOCTRINE_MONGODB_ODM !== $storageDriver) {
            $output->writeln('<error>This command could be only launched on mongodb storage</error>');

            return -1;
        }

        $saver = $this->getProductSaver();
        $detacher = $this->getDetacher();

        $pqbFactory = $this->getProductQueryBuilderFactory();
        $pqb = $pqbFactory->create();
        $products = $pqb->execute();

        $updatedProducts = [];
        $productNumber = count($products);
        $progress = new ProgressBar($output, $productNumber);
        $progress->start();
        $i = 0;
        foreach ($products as $product) {
            $normalizedData = $this->getMongoProductNormalizer()->normalize($product, 'mongodb_json');
            $product->setNormalizedData($normalizedData);
            $updatedProducts[] = $product;
            if (0 == $i % self::BATCH_SIZE || $i === $productNumber) {
                $saver->saveAll($updatedProducts);
                $detacher->detachAll($updatedProducts);
                $updatedProducts = [];
            }
            $i++;
            $progress->advance();
        }
        $progress->finish();

        return 0;
    }

    /**
     * @return \Pim\Bundle\CatalogBundle\MongoDB\Normalizer\Document\ProductNormalizer
     */
    public function getMongoProductNormalizer()
    {
        return $this->getContainer()->get('pim_catalog.mongodb.normalizer.normalized_data.product');
    }

    /**
     * Retrieves product query builder factory
     *
     * @return \Pim\Component\Catalog\Query\ProductQueryBuilderFactory
     */
    public function getProductQueryBuilderFactory()
    {
        return $this->getContainer()->get('pim_catalog.query.product_query_builder_factory');
    }

    /**
     * Retrieves product saver
     *
     * @return \Pim\Bundle\CatalogBundle\Doctrine\MongoDBODM\Saver\ProductSaver
     */
    public function getProductSaver()
    {
        return $this->getContainer()->get('pim_catalog.saver.product');
    }

    /**
     * Retrieves entity detacher
     *
     * @return \Akeneo\Bundle\StorageUtilsBundle\Doctrine\Common\Detacher\ObjectDetacher
     */
    public function getDetacher()
    {
        return $this->getContainer()->get('akeneo_storage_utils.doctrine.object_detacher');
    }
}

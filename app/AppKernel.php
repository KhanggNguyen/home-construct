<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;


class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            # Assetic
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

            # PDF Bundle
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),

            # SENSIO
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            # FOS
            new FOS\UserBundle\FOSUserBundle(),

            # MakerBundle
            new \Symfony\Bundle\MakerBundle\MakerBundle(),

            # KNP
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            # Rollerworks
            new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),

            # Bundle de base
            new Symracine\TemplateBundle\SymracineTemplateBundle(),
            new Symracine\FoBundle\SymracineFoBundle(),
            new Symracine\MaintenanceBundle\SymracineMaintenanceBundle(),
            new Symracine\MailBundle\SymracineMailBundle(),

            # Bundle Application
            new HomeConstruct\BuildBundle\HomeConstructBuildBundle(),
            new HomeConstruct\UserBundle\HomeConstructUserBundle(),
            new HomeConstruct\FoBundle\HomeConstructFoBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            //Pour afficher la barre profiler symfony en dev
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle();

            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setParameter('container.autowiring.strict_mode', true);
            $container->setParameter('container.dumper.inline_class_loader', true);

            $container->addObjectResource($this);
        });
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}


UPGRADE FROM Pre-Release to 1.0
===============================

### General Changes

- Renamed the Bundle class
```
  - Hostnet\Bundle\EntityTrackerBundle\EntityTrackerBundle
  + Hostnet\Bundle\EntityTrackerBundle\HostnetEntityTrackerBundle
```
- Renamed the Extension class
```
  - Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\EntityTrackerExtension
  + Hostnet\Bundle\EntityTrackerBundle\DependencyInjection\HostnetEntityTrackerExtension
```
  
> Changes are made to stay in line with the [Symfony2 Best Practices for Bundles](http://symfony.com/doc/current/cookbook/bundles/best_practices.html)

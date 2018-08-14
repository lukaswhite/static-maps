# Static Maps

A PHP library for generating static maps.

> Full Documentation to follow

## Quick Example

```php
$center = new LatLng( 53.48095000, -2.23743000 );
$markers = new MarkerFactory( );
$marker = $markers->create( 'pin', $center );

$map = new Map( );

$map->setCenter( $center )
    ->setZoom( 18 )
    ->addMarker( $marker );

$map->save( '/path/to/some/directory' );
```

The result:

![Manchester](https://raw.githubusercontent.com/lukaswhite/static-maps/master/docs/assets/manchester.png)

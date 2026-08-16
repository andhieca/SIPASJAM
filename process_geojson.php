<?php

$inputPath = 'database/Desa.geojson';
$outputPath = 'public/geojson/pasirjambu.geojson';

if (!file_exists('public/geojson')) {
    mkdir('public/geojson', 0777, true);
}

$data = json_decode(file_get_contents($inputPath), true);

$pasirjambuFeatures = [];

if (isset($data['features'])) {
    foreach ($data['features'] as $feature) {
        $kecamatan = isset($feature['properties']['WADMKC']) ? $feature['properties']['WADMKC'] : '';
        // Some datasets might use different case
        if (strtolower($kecamatan) === 'pasirjambu') {
            $pasirjambuFeatures[] = $feature;
        }
    }
}

$data['features'] = $pasirjambuFeatures;

file_put_contents($outputPath, json_encode($data));

echo "Processed! Found " . count($pasirjambuFeatures) . " villages in Pasirjambu.\n";

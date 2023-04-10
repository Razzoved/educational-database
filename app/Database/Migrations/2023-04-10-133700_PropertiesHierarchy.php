<?php

namespace App\Database\Migrations;

use App\Models\PropertyModel;
use CodeIgniter\Database\Migration;


/**
 * Expects the property entity to be already edited. Should have:
 * - tag -> category (changed from property_tag).
 *
 * @author Jan Martinek
 */
class PropertiesHierarchy extends Migration
{
    public function up()
    {
        $model = model(PropertyModel::class, false, $this->db);

        $model->transStart();

        $tags = [];
        $properties = $model->findAll();

        // take categories from properties and insert them into properties
        foreach ($properties as $k => $property) {
            if (!in_array($property->property_tag, $tags)) {
                $insertID = $model->insert([
                    'property_tag' => 'NULL',
                    'property_value' => $property->property_tag,
                ]);
                $tags[$insertID] = $property->property_tag;
            }
        }

        // reassign property_tag to appropriate ids
        foreach ($properties as $property) {
            $tagIndex = array_search($property->property_tag, $tags);
            if ($tagIndex !== null) {
                $property->property_tag = $tagIndex;
                $model->update($property->id, $property);
            }
        }

        $model->transComplete();
    }

    public function down()
    {
        $model = model(PropertyModel::class, false, $this->db);

        $model->transStart();

        $tags = [];
        $properties = $model->findAll();

        // take categories from properties and delete them
        foreach ($properties as $k => $property) {
            if (!$property->property_tag) {
                $model->delete($property->id);
                $tags[$property->property_value] = $property->id;
                unset($properties[$k]);
            }
        }

        // reassign property_tag to appropriate values
        foreach ($properties as $property) {
            $tagIndex = array_search($property->property_tag, $tags);
            if ($tagIndex !== false) {
                $property->property_tag = $tagIndex;
                $model->update($property->id, $property);
            }
        }

        $model->transComplete();
    }
}

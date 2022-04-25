<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\EntityController;

class Relations extends EntityController
{
    public function get_relation_id($relation_name)
    {
        $relation = $this->get_element($relation_name, 'name');
        if (!$relation) {
            $relation = array('name' => $relation_name);
            $this->save($relation);
        }
        return $relation['id'];
    }

    public function save_elements_by_relation($relation_name, $elements, $related_element_id)
    {
        $relation_id = $this->get_relation_id($relation_name);
        $element_ids = implode(',', $elements ?: [0]);
        $result = 0;
        $before = Database::get_all_first("SELECT element_id FROM lite_related_elements WHERE relation_id=$relation_id AND related_element_id=$related_element_id") ?: [];

        if ($delete = array_diff($before, $elements)) {
            $ids = implode(',', $delete);
            Database::query("DELETE FROM lite_related_elements WHERE relation_id=$relation_id AND related_element_id=$related_element_id AND element_id IN ($ids)");
            $result -= Repository::$db->affected_rows;
        }
        if ($new = array_diff($elements, $before)) {
            foreach ($new as $id) {
                $position = Database::get_first("SELECT MAX(position) FROM lite_related_elements WHERE relation_id=$relation_id AND element_id=$id") ?: 0;
                $position++;
                Database::query("INSERT INTO lite_related_elements (relation_id, element_id, related_element_id, position) VALUES ($relation_id, $id, $related_element_id, $position)");
                $result += Repository::$db->affected_rows;
            }
        }
        return $result;
    }

    public function save_related_elements($relation_name, $element_id, $related_elements, $two_way = false)
    {
        $relation_id = $this->get_relation_id($relation_name);
        Database::query("DELETE FROM lite_related_elements WHERE relation_id=$relation_id AND element_id=$element_id");
        if ($two_way) {
            Database::query("DELETE FROM lite_related_elements WHERE relation_id=$relation_id AND related_element_id=$element_id");
        }
        $position = 0;
        $result = 0;
        foreach ($related_elements as $related_element_id) {
            $position++;
            Database::query("INSERT IGNORE INTO lite_related_elements (relation_id, element_id, related_element_id, position) VALUES ($relation_id, $element_id, $related_element_id, $position)");
            $result += Repository::$db->affected_rows;
        }
        return $result;
    }

    public function delete_elements_by_relation($relation_name, $related_element_id)
    {
        $relation_id = $this->get_relation_id($relation_name);
        Database::query("DELETE FROM lite_related_elements WHERE relation_id=$relation_id AND related_element_id=$related_element_id");
        $result = Repository::$db->affected_rows;
        return $result;
    }

    public function delete_related_elements($relation_name, $element_id)
    {
        $relation_id = $this->get_relation_id($relation_name);
        Database::query("DELETE FROM lite_related_elements WHERE relation_id=$relation_id AND element_id=$element_id");
        $result = Repository::$db->affected_rows;
        return $result;
    }

    public function get_elements_by_relation($relation_name, $related_element_id)
    {
        $relation_id = $this->get_relation_id($relation_name);
        $elements = Database::get_all_first("
			SELECT element_id
			FROM lite_related_elements
			WHERE relation_id=$relation_id AND related_element_id=$related_element_id
			ORDER BY position
		");
        return $elements;
    }

    public function get_related_elements($relation_name, $element_id, $format = false, $two_way = false)
    {
        $relation_id = $this->get_relation_id($relation_name);
        $related_elements = Database::get_all_first("
			SELECT related_element_id 
			FROM lite_related_elements 
			WHERE relation_id=$relation_id AND element_id=$element_id 
			ORDER BY position
		");
        if ($two_way) {
            $related_elements = array_unique(array_merge($related_elements, Database::get_all_first("
				SELECT element_id 
				FROM lite_related_elements 
				WHERE relation_id=$relation_id AND related_element_id=$element_id
				ORDER BY position
			")));
        }
        if ($format && $related_elements && method_exists($this, $method = "format_related_elements_$format")) {
            $related_elements = $this->$method($related_elements);
        }
        return $related_elements;
    }

    public function format_related_elements_swap($elements)
    {
        return array_combine(array_values($elements), array_fill(0, count($elements), true));
    }

    public function format_related_elements_products($elements)
    {
        $rez = Database::query("
			SELECT p.id, b.name brand_name, p.name product_name
			FROM lite_products p 
			LEFT JOIN lite_brands b ON p.brand_id=b.id
			WHERE p.id IN (".implode(',', $elements).")
		");
        // key -> id, value -> false
        $elements = array_combine(array_values($elements), array_fill(0, count($elements), false));
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            $names = array($row['brand_name'], $row['product_name']);
            $names = array_filter($names);
            $elements[$row['id']] = implode(' &rarr; ', $names);
        }
        $elements = array_filter($elements);
        return $elements;
    }

    public function format_related_elements_product_modifications($elements)
    {
        $rez = Database::query("
			SELECT pm.id, b.name brand_name, p.name product_name, pm.name modification_name
			FROM lite_product_modifications pm 
			JOIN lite_products p ON p.id=pm.product_id
			LEFT JOIN lite_brands b ON p.brand_id=b.id
			WHERE pm.id IN (".implode(',', $elements).")
		");
        $elements = array_combine(array_values($elements), array_fill(0, count($elements), false));
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            $names = array($row['brand_name'], $row['product_name'], $row['modification_name']);
            $names = array_filter($names);
            $elements[$row['id']] = implode(' &rarr; ', $names);
        }
        $elements = array_filter($elements);
        return $elements;
    }

    public function format_related_elements_product_items($elements)
    {
        $rez = Database::query($q="
			SELECT pi.id, b.name brand_name, p.name product_name, pm.name modification_name, pi.name item_name, pi.code
			FROM lite_product_items pi 
			JOIN lite_products p ON p.id=pi.product_id
			LEFT JOIN lite_product_modifications pm ON pm.id = pi.modification_id
			LEFT JOIN lite_brands b ON p.brand_id=b.id
			WHERE pi.id IN (".implode(',', $elements).")
		");
        $elements = array_combine(array_values($elements), array_fill(0, count($elements), false));
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            $names = array($row['brand_name'], $row['product_name'], $row['modification_name'], "$row[item_name] ($row[code])");
            $names = array_filter($names);
            $elements[$row['id']] = implode(' &rarr; ', $names);
        }
        $elements = array_filter($elements);
        return $elements;
    }
}

<?php
class Place_model extends CI_Model
{
    public function get_place_with_relations($id = null) {
        if ($id) {
            $this->db->where('id', $id);
        }
        $places = $this->db->get('places')->result_array();
        if (!$places) return [];
        $placeIds = array_column($places, 'id');
        $categoryRelations = $this->db
            ->where('target_table', 'places')
            ->where_in('target_id', $placeIds)
            ->get('category_relation')
            ->result_array();
        $categoryIds = array_column($categoryRelations, 'category_id');
        $categories = $this->db
            ->where_in('id', $categoryIds)
            ->get('category')
            ->result_array();
        $mediaRelations = $this->db
            ->where('target_table', 'places')
            ->where_in('target_id', $placeIds)
            ->get('media_relation')
            ->result_array();

        $mediaIds = array_column($mediaRelations, 'media_id');
        $medias = $this->db
            ->where_in('id', $mediaIds)
            ->get('media')
            ->result_array();
        $addressRelations = $this->db
            ->where('target_table', 'places')
            ->where_in('target_id', $placeIds)
            ->get('address_relation')
            ->result_array();
        $addressIds = array_column($addressRelations, 'address_id');
        $addresses = $this->db
            ->where_in('id', $addressIds)
            ->get('addresses')
            ->result_array();
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat;
        }
        $mediaMap = [];
        foreach ($medias as $media) {
            $mediaMap[$media['id']] = $media;
        }
        $addressMap = [];
        foreach ($addresses as $address) {
            $addressMap[$address['id']] = $address;
        }
        foreach ($places as &$place) {
            $place['categories'] = [];
            foreach ($categoryRelations as $rel) {
                if ($rel['target_id'] == $place['id']) {
                    $catId = $rel['category_id'];
                    if (isset($categoryMap[$catId])) {
                        $place['categories'][] = $categoryMap[$catId];
                    }
                }
            }
            $place['medias'] = [];
            foreach ($mediaRelations as $rel) {
                if ($rel['target_id'] == $place['id']) {
                    $mediaId = $rel['media_id'];
                    if (isset($mediaMap[$mediaId])) {
                        $place['medias'][] = $mediaMap[$mediaId];
                    }
                }
            }
            $place['addresses'] = [];
            foreach ($addressRelations as $rel) {
                if ($rel['target_id'] == $place['id']) {
                    $addressId = $rel['address_id'];
                    if (isset($addressMap[$addressId])) {
                        $place['addresses'][] = $addressMap[$addressId];
                    }
                }
            }
        }
        return $id ? $places[0] : $places;
    }
}
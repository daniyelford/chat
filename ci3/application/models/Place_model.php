<?php
class Place_model extends CI_Model
{
    public function get_place_with_relations($id = null) {
        if ($id) {
            $this->db->where('id', $id);
        }
        $places = $this->db->get('places')->result_array();
        if (!$places) return [];
        $placeIds = (!empty($places)?array_column($places, 'id'):[]);
        if(!empty($placeIds))
            $categoryRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('category_relation')
                ->result_array();
        $categoryIds =(!empty($categoryRelations) ? array_column($categoryRelations, 'category_id'):[]);
        if(!empty($categoryIds))
            $categories = $this->db
                ->where_in('id', $categoryIds)
                ->get('category')
                ->result_array();
        if(!empty($placeIds))
            $mediaRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('media_relation')
                ->result_array();
        $mediaIds =(!empty($mediaRelations)?array_column($mediaRelations, 'media_id'):[]);
        if(!empty($mediaIds))
            $medias = $this->db
                ->where_in('id', $mediaIds)
                ->get('media')
                ->result_array();
        if(!empty($placeIds))
            $addressRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('address_relation')
                ->result_array();
        $addressIds =(!empty($addressRelations) ? array_column($addressRelations, 'address_id') :[]);
        if(!empty($addressIds))
            $addresses = $this->db
                ->where_in('id', $addressIds)
                ->get('addresses')
                ->result_array();
        $categoryMap = [];
        if(!empty($categories))
        foreach ($categories as $cat) {
            $categoryMap[$cat['id']] = $cat;
        }
        $mediaMap = [];
        if(!empty($medias))
        foreach ($medias as $media) {
            $mediaMap[$media['id']] = $media;
        }
        $addressMap = [];
        if(!empty($addresses))
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
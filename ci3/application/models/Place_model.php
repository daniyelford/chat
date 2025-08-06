<?php
class Place_model extends CI_Model
{
        // درج مکان جدید
    public function insert_place($data) {
        // اینجا باید فیلدهای لازم رو در $data استخراج کنی
        // مثلا:
        $insertData = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            // بقیه فیلدهای places رو اضافه کن
        ];

        $this->db->insert('places', $insertData);
        $placeId = $this->db->insert_id();

        if ($placeId) {
            // افزودن روابط مثل دسته‌بندی‌ها، آدرس‌ها و رسانه‌ها اگر هست
            $this->save_relations($placeId, $data);
            return $placeId;
        }

        return false;
    }

    // به‌روزرسانی مکان موجود
    public function update_place($id, $data) {
        $updateData = [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            // بقیه فیلدهای places رو اضافه کن
        ];

        $this->db->where('id', $id);
        $updated = $this->db->update('places', $updateData);

        if ($updated) {
            // حذف روابط قبلی و افزودن مجدد روابط جدید
            $this->delete_relations($id);
            $this->save_relations($id, $data);
            return true;
        }

        return false;
    }
    public function get_place_with_relations($offset = 0, $limit = 10, $category_id = null) {
        $this->db->select('p.*');
        $this->db->from('places p');
        if ($category_id !== null) {
            $this->db->join('category_relation cr', 'cr.target_id = p.id AND cr.target_table = "places"', 'inner');
            $this->db->where('cr.category_id', $category_id);
        }
        $this->db->limit($limit, $offset);
        $places = $this->db->get()->result_array();
        if (!$places) return [];
        $placeIds = array_column($places, 'id');
        $categoryRelations = [];
        if (!empty($placeIds)) {
            $categoryRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('category_relation')
                ->result_array();
        }
        $categoryIds = array_column($categoryRelations, 'category_id');
        $categories = [];
        if (!empty($categoryIds)) {
            $categories = $this->db
                ->where_in('id', $categoryIds)
                ->get('category')
                ->result_array();
        }
        $mediaRelations = [];
        if (!empty($placeIds)) {
            $mediaRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('media_relation')
                ->result_array();
        }
        $mediaIds = array_column($mediaRelations, 'media_id');
        $medias = [];
        if (!empty($mediaIds)) {
            $medias = $this->db
                ->where_in('id', $mediaIds)
                ->get('media')
                ->result_array();
        }
        $addressRelations = [];
        if (!empty($placeIds)) {
            $addressRelations = $this->db
                ->where('target_table', 'places')
                ->where_in('target_id', $placeIds)
                ->get('address_relation')
                ->result_array();
        }
        $addressIds = array_column($addressRelations, 'address_id');
        $addresses = [];
        if (!empty($addressIds)) {
            $addresses = $this->db
                ->where_in('id', $addressIds)
                ->get('addresses')
                ->result_array();
        }
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
                if ($rel['target_id'] == $place['id'] && isset($categoryMap[$rel['category_id']])) {
                    $place['categories'][] = $categoryMap[$rel['category_id']];
                }
            }
            $place['medias'] = [];
            foreach ($mediaRelations as $rel) {
                if ($rel['target_id'] == $place['id'] && isset($mediaMap[$rel['media_id']])) {
                    $place['medias'][] = $mediaMap[$rel['media_id']];
                }
            }
            $place['addresses'] = [];
            foreach ($addressRelations as $rel) {
                if ($rel['target_id'] == $place['id'] && isset($addressMap[$rel['address_id']])) {
                    $place['addresses'][] = $addressMap[$rel['address_id']];
                }
            }
        }
        return $places;
    }
    public function count_places($category_id = null) {
        $this->db->from('places');
        if ($category_id !== null) {
            $this->db->join('category_relation cr', 'cr.target_id = places.id AND cr.target_table = "places"', 'inner');
            $this->db->where('cr.category_id', $category_id);
        }
        return $this->db->count_all_results();
    }
    public function get_categories($offset = 0, $limit = 20, $search = '') {
        if ($search !== '') {
            $this->db->like('title', $search);
        }
        $this->db->limit($limit, $offset);
        return $this->db->get('category')->result_array();
    }
    public function count_categories($search = '') {
        if ($search !== '') {
            $this->db->like('title', $search);
        }
        return $this->db->count_all_results('category');
    }
    private function save_relations($placeId, $data) {
        if (!empty($data['categories']) && is_array($data['categories'])) {
            foreach ($data['categories'] as $categoryId) {
                $this->db->insert('category_relation', [
                    'target_table' => 'places',
                    'target_id' => $placeId,
                    'category_id' => $categoryId
                ]);
            }
        }
        if (!empty($data['addresses']) && is_array($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                if (!empty($address['id'])) {
                    $this->db->insert('address_relation', [
                        'target_table' => 'places',
                        'target_id' => $placeId,
                        'address_id' => $address['id']
                    ]);
                }
            }
        }
        if (!empty($data['medias']) && is_array($data['medias'])) {
            foreach ($data['medias'] as $media) {
                if (!empty($media['id'])) {
                    $this->db->insert('media_relation', [
                        'target_table' => 'places',
                        'target_id' => $placeId,
                        'media_id' => $media['id']
                    ]);
                }
            }
        }
    }
    private function delete_relations($placeId) {
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('category_relation');
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('address_relation');
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('media_relation');
    }
}

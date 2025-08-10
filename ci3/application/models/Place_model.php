<?php
class Place_model extends CI_Model
{
    public $security;
    public $send;
    public $user;
    private $delete_extera_relations=false;
    public function get_place_with_relations($offset = 0, $limit = 10, $id = null, $category_id = null) {
        $fetchLimit = $limit + 1;
        $this->db->select('p.*');
        $this->db->from('places p');
        if(!empty($id)){
            $this->db->where('id',(int) $id);
        }else{
            if (!empty($category_id)) {
                $this->db->join('category_relation cr', 'cr.target_id = p.id AND cr.target_table = "places"', 'inner');
                $this->db->where('cr.category_id', (int) $category_id);
            }
            $this->db->order_by('id','DESC');
            $this->db->limit($fetchLimit, $offset);
        }
        $places = $this->db->get()->result_array();
        if (!$places) return ['data'=>[],'has_more'=>false];
        $hasMore = count($places) > $limit;
        if ($hasMore) array_pop($places);
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
        return ['data'=>$places,'has_more'=>$hasMore];
    }
    public function get_categories($offset = 0, $limit = 20, $get_all = false, $search = '') {
        if($get_all){
            $this->db->where('for_place', 'yes');
            if ($search !== '') {
                $this->db->like('title', $search);
            }
            return ['data'=>$this->db->get('category')->result_array(),'has_more'=>false];
        }else{
            $fetchLimit=$limit+1;
            $this->db->limit($fetchLimit, $offset);
            $relationQuery = $this->db
            ->distinct()
            ->select('category_id')
            ->where('target_table', 'places')
            ->get('category_relation')
            ->result_array();
            $has_more=(count($relationQuery) > $limit);
            if($has_more) array_pop($relationQuery);
            $categoryIds = array_column($relationQuery, 'category_id');
            if (empty($categoryIds)) {
                return ['data' => [], 'has_more' => false];
            }
            $this->db->where_in('id', $categoryIds);
            return ['data'=>$this->db->get('category')->result_array(),'has_more'=>$has_more];
        }
    }
    public function insert_place($data) {
        $insertData = [
            'title' => is_callable($this->security) ? ($this->security)($data['title'] ?? '') : $data['title']??'',
            'description' => is_callable($this->security) ? ($this->security)($data['description'] ?? '') : $data['description']??'',
        ];
        $this->db->insert('places', $insertData);
        $placeId = $this->db->insert_id();
        if ($placeId) {
            $this->save_relations($placeId, $data);
            return $placeId;
        }
        return false;
    }
    public function update_place($id, $data) {
        $updateData = [];
        $title = is_callable($this->security) ? ($this->security)($data['title'] ?? '') : $data['title']??'';
        $description = is_callable($this->security) ? ($this->security)($data['description'] ?? '') : $data['description']??'';
        if(!empty($title)) $updateData['title'] = $title;
        if(!empty($description)) $updateData['description'] = $description;
        if(!empty($updateData)){
            $this->db->where('id', $id);
            $this->db->update('places', $updateData);
        }
        $this->delete_relations($id);
        $this->save_relations($id, $data);
        return true;
    }
    public function delete_place($id){
        if(!empty($id) && intval($id)>0){
            $this->delete_extera_relations=true;
            $this->delete_where_id(intval($id));
            $this->delete_relations(intval($id));
            $this->delete_extera_relations=false;
            return true;
        }
        return false;
    }
    private function save_relations($placeId, $data) {
        if (!empty($data['category_id']) && is_array($data['category_id'])) {
            $category_ids=array_column($data['category_id'],'id');
            $category_data=[];
            foreach ($category_ids as $categoryId) {
                if (!empty($categoryId) && intval($categoryId)>0)
                    $category_data[] = [
                        'target_table' => 'places',
                        'target_id' => $placeId,
                        'category_id' => $categoryId
                    ];
            }
            $this->db->insert_batch('category_relation', $category_data);
        }
        if (!empty($data['media_id']) && is_array($data['media_id'])) {
            $media_data=[];
            foreach ($data['media_id'] as $media) {
                if (!empty($media) && intval($media)>0)
                    $media_data[]=[
                        'target_table' => 'places',
                        'target_id' => $placeId,
                        'media_id' => $media
                    ];
            }
            $this->db->insert_batch('media_relation', $media_data);
        }
        if (!empty($data['user_address']) && !empty($data['user_address']['value']))
            if(!empty($data['user_address']['value']['address_id']) && intval($data['user_address']['value']['address_id'])>0){
                $address_id=intval($data['user_address']['value']['address_id']);
            }elseif(!empty($data['user_address']['value']['total'])) {
                $this->db->insert('addresses',[
                    'address'=>is_callable($this->security) ? ($this->security)($data['user_address']['value']['total']['display_name'] ?? '') : $data['user_address']['value']['total']['display_name']??'',
                    'country'=>$data['user_address']['value']['total']['address']['country']??'',
                    'region'=>$data['user_address']['value']['total']['address']['province']??'',
                    'city'=>$data['user_address']['value']['total']['address']['city']??$data['user_address']['value']['total']['address']['town']??$data['user_address']['value']['total']['address']['village']??'',
                    'lat'=>$data['user_address']['value']['total']['lat']??'',
                    'lon'=>$data['user_address']['value']['total']['lon']??'',
                    'code_posti'=>$data['user_address']['value']['total']['address']['postcode']??'',
                ]);
                $address_id=$this->db->insert_id();
            }elseif(!empty($data['user_address']['value']['lat']) && !empty($data['user_address']['value']['lon']) && !empty($data['user_address']['value']['address'])){
                if($data['user_address']['value']['address']==="خطا در دریافت آدرس"){
                    $address=is_callable($this->send) ? ($this->send)($data['user_address']['value']['lat'],$data['user_address']['value']['lon']):0;
                    if(!empty($address)){
                        $this->db->insert('addresses',[
                            'address'=> $address['display_name']??'',
                            'country'=>$address['address']['country']??'',
                            'city'=>$address['address']['city']??$address['address']['town']??$address['address']['village']??'',
                            'lat'=>$address['lat']??'',
                            'lon'=>$address['lon']??'',
                            'region'=>$address['address']['province']??$address['address']['state']??$address['address']['municipality']??'',
                            'code_posti'=>$address['address']['postcode']??'',
                        ]);
                        $address_id=$this->db->insert_id();
                    }else{
                        $this->db->insert('addresses',[
                            'lat'=>$data['user_address']['value']['lon'],
                            'lon'=>$data['user_address']['value']['lon'],
                        ]);
                        $address_id=$this->db->insert_id();
                    }
                }else{
                    $this->db->insert('addresses',[
                        'address'=> $data['user_address']['value']['address'],                        
                        'lat'=>$data['user_address']['value']['lon'],
                        'lon'=>$data['user_address']['value']['lon'],
                    ]);
                    $address_id=$this->db->insert_id();
                }
            }else{
                $address_id=is_callable($this->user)?($this->user)():0;
            }
            if(intval($address_id)>0)
                return $this->db->insert('address_relation', [
                    'target_table' => 'places',
                    'target_id' => $placeId,
                    'address_id' => $address_id
                ]);
            return false;

    }
    private function delete_where_id($id){
        return (!empty($id) && intval($id)>0 && $this->db->delete('places', ['id'=>intval($id)]));
    }
    private function delete_relations($placeId) {
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('category_relation');
        if ($this->delete_extera_relations) {
            $addressIds = $this->db
                ->select('address_id')
                ->where('target_table', 'places')
                ->where('target_id', $placeId)
                ->get('address_relation')
                ->result_array();
            if (!empty($addressIds)) {
                $ids = array_column($addressIds, 'address_id');
                $this->db->where_in('id', $ids)->delete('addresses');
            }
        }
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('address_relation');
        if ($this->delete_extera_relations) {
            $mediaIds = $this->db
                ->select('media_id')
                ->where('target_table', 'places')
                ->where('target_id', $placeId)
                ->get('media_relation')
                ->result_array();
            if (!empty($mediaIds)) {
                $ids = array_column($mediaIds, 'media_id');
                $medias = $this->db
                    ->select('url')
                    ->where_in('id', $ids)
                    ->get('media')
                    ->result_array();
                $files = array_column($medias, 'url');
                foreach ($files as $file) {
                    if (is_file($file)) @unlink($file);
                }
                $this->db->where_in('id', $ids)->delete('media');
            }
        }
        $this->db->where('target_table', 'places');
        $this->db->where('target_id', $placeId);
        $this->db->delete('media_relation');
    }
}

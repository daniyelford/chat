<?php

class Order_model extends CI_Model
{
    public function get_discount_products_by_user_account($user_account_id)
    {
        if (!$this->isValidUserAccountId($user_account_id)) return [];

        $payment_ids = $this->getPaymentIdsByUserAccount($user_account_id);
        if (empty($payment_ids)) return [];

        return $this->getDiscountProductsByPaymentIds($payment_ids);
    }

    public function get_grouped_payments_by_user_account($user_account_id, $limit = 10, $offset = 0)
    {
        if (!$this->isValidUserAccountId($user_account_id)) return [];

        $rows = $this->fetchPaymentsWithRelations($user_account_id, $limit, $offset);
        if (empty($rows)) return [];

        return $this->buildGroupedPayments($rows);
    }

    public function get_report_tokens_by_news_owner($user_account_id)
    {
        if (!$this->isValidUserAccountId($user_account_id)) return [];

        $news_ids = $this->getTargetIdsByUserAccount('news', $user_account_id);
        if (empty($news_ids)) return [];

        $report_ids = $this->getReportListIdsByNewsIds($news_ids);
        if (empty($report_ids)) return [];

        $payments = $this->getPaymentsForReports($report_ids);
        if (empty($payments)) return [];

        $relation_ids = $this->extractRelationIdsFromPayments($payments);
        $token_changes = $this->getLatestTokenChanges($relation_ids);

        $grouped = $this->groupReportsByNews($payments, $token_changes);
        $this->attachMediaToNewsAndReports($grouped);
        $this->attachCategoriesToNews($grouped, $news_ids);

        return array_values($grouped);
    }

    private function isValidUserAccountId($id): bool
    {
        return !empty($id) && is_numeric($id);
    }

    private function getPaymentIdsByUserAccount($user_account_id): array
    {
        $rows = $this->db
            ->select('pay.id')
            ->from('payment pay')
            ->join('user_account_relations uar1', 'uar1.id = pay.pay_money_user_account_relation_id', 'left')
            ->join('user_account_relations uar2', 'uar2.id = pay.give_money_user_account_relation_id', 'left')
            ->group_start()
                ->where('uar1.user_account_id', $user_account_id)
                ->or_where('uar2.user_account_id', $user_account_id)
            ->group_end()
            ->get()
            ->result_array();

        return array_column($rows, 'id');
    }

    private function getDiscountProductsByPaymentIds(array $payment_ids): array
    {
        // return $this->db
        //     ->select('DISTINCT p.id, p.title, p.price, p.per_unit, p.stock_value')
        //     ->from('product_relation pr')
        //     ->join('product p', 'p.id = pr.product_id')
        //     ->where('pr.target_table', 'payment')
        //     ->where_in('pr.target_id', $payment_ids)
        //     ->where('p.is_discount', 1)
        //     ->get()
        //     ->result();
        return $this->db
        ->select('DISTINCT p.id, p.title, p.price, p.per_unit, p.stock_value')
        ->from('payment_order po')
        ->join('orders o', 'o.id = po.order_id')
        ->join('product_relation pr', 'pr.target_table = "order" AND pr.target_id = o.id')
        ->join('product p', 'p.id = pr.product_id')
        ->where_in('po.payment_id', $payment_ids)
        ->where('p.is_discount', 1)
        ->get()
        ->result();
    }

    private function getTargetIdsByUserAccount(string $table, int $user_account_id): array
    {
        $rows = $this->db
            ->select('target_id')
            ->from('user_account_relations')
            ->where('target_table', $table)
            ->where('user_account_id', $user_account_id)
            ->get()
            ->result_array();

        return array_column($rows, 'target_id');
    }

    // private function getReportListIdsByNewsIds(array $newsIds): array
    // {
    //     $rows = $this->db
    //         ->select('id')
    //         ->from('report_list')
    //         ->where_in('news_id', $newsIds)
    //         ->get()
    //         ->result_array();

    //     return array_column($rows, 'id');
    // }
    private function getReportListIdsByNewsIds(array $newsIds, int $chunkSize = 500): array
    {
        $result = [];

        foreach (array_chunk($newsIds, $chunkSize) as $chunk) {
            $rows = $this->db
                ->select('id')
                ->from('report_list')
                ->where_in('news_id', $chunk)
                ->get()
                ->result_array();

            $result = array_merge($result, $rows);
        }

        return array_column($result, 'id');
    }


    private function getPaymentsForReports(array $reportIds): array
    {
        return $this->db
            ->select('
                pay.id,
                pay.pay_money_user_account_relation_id,
                pay.give_money_user_account_relation_id,
                pay.amount,
                pay.factor,
                pay.status,
                pay.created_at,
                rl.id AS report_list_id,
                rl.description AS report_description,
                n.id AS news_id,
                n.description AS news_description
            ')
            ->from('payment pay')
            ->join('payment_order po', 'po.payment_id = pay.id', 'left')
            ->join('orders o', 'o.id = po.order_id', 'left')
            ->join('report_list rl', 'rl.id = o.report_list_id', 'left')
            ->join('news n', 'n.id = rl.news_id', 'left')
            ->where('pay.pay_type', 'add_token')
            ->where_in('rl.id', $reportIds)
            ->get()
            ->result_array();
    }

    private function extractRelationIdsFromPayments(array $payments): array
    {
        $ids = [];
        foreach ($payments as $p) {
            if (!empty($p['pay_money_user_account_relation_id'])) $ids[] = $p['pay_money_user_account_relation_id'];
            if (!empty($p['give_money_user_account_relation_id'])) $ids[] = $p['give_money_user_account_relation_id'];
        }
        return array_unique($ids);
    }

    private function getLatestTokenChanges(array $relationIds): array
    {
        if (empty($relationIds)) return [];

        $rows = $this->db
            ->select('pac.user_account_relation_id, pac.token')
            ->from('payment_account_changes pac')
            ->join('(
                SELECT user_account_relation_id, MAX(created_at) AS max_created
                FROM payment_account_changes
                GROUP BY user_account_relation_id
            ) sub', 'sub.user_account_relation_id = pac.user_account_relation_id AND sub.max_created = pac.created_at')
            ->where_in('pac.user_account_relation_id', $relationIds)
            ->get()
            ->result_array();

        $latest = [];
        foreach ($rows as $r) {
            $latest[$r['user_account_relation_id']] = $r['token'];
        }
        return $latest;
    }

    private function groupReportsByNews(array $payments, array $latestTokens): array
    {
        $grouped = [];

        foreach ($payments as $p) {
            $nid = $p['news_id'];
            $rid = $p['report_list_id'];

            if (!isset($grouped[$nid])) {
                $grouped[$nid] = [
                    'news' => [
                        'id' => $nid,
                        'description' => $p['news_description'],
                        'news_media' => [],
                        'categories' => [],
                    ],
                    'report_list' => [],
                ];
            }

            $grouped[$nid]['report_list'][] = [
                'id' => $rid,
                'description' => $p['report_description'],
                'token' => $p['report_token'],
                'report_media' => [],
                'payment' => [
                    'id' => $p['id'],
                    'amount' => $p['amount'],
                    'factor' => $p['factor'],
                    'status' => $p['status'],
                    'created_at' => $p['created_at'],
                    'pay_token' => $latestTokens[$p['pay_money_user_account_relation_id']] ?? null,
                    'give_token' => $latestTokens[$p['give_money_user_account_relation_id']] ?? null,
                ],
            ];
        }

        return $grouped;
    }

    private function attachCategoriesToNews(array &$result, array $newsIds): void
    {
        if (empty($newsIds)) return;

        $categoryRows = $this->db
            ->select('cr.target_id AS news_id, c.id, c.title')
            ->from('category_relation cr')
            ->join('category c', 'c.id = cr.category_id', 'left')
            ->where('cr.target_table', 'news')
            ->where_in('cr.target_id', $newsIds)
            ->get()
            ->result_array();

        foreach ($categoryRows as $row) {
            $newsId = (int) $row['news_id'];
            if (isset($result[$newsId])) {
                $result[$newsId]['news']['categories'][] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                ];
            }
        }
    }

    private function attachMediaToNewsAndReports(array &$grouped): void
    {
        $newsIds = array_column(array_column($grouped, 'news'), 'id');
        $reportIds = [];

        foreach ($grouped as $item) {
            foreach ($item['report_list'] as $r) {
                $reportIds[] = $r['id'];
            }
        }

        if (!empty($newsIds)) {
            $rows = $this->db
                ->select('mr.target_id AS news_id, m.url, m.type')
                ->from('media_relation mr')
                ->join('media m', 'm.id = mr.media_id')
                ->where('mr.target_table', 'news')
                ->where_in('mr.target_id', $newsIds)
                ->get()
                ->result();

            foreach ($rows as $row) {
                if (isset($grouped[$row->news_id])) {
                    $grouped[$row->news_id]['news']['news_media'][] = [
                        'url' => $row->url,
                        'type' => $row->type,
                    ];
                }
            }
        }

        if (!empty($reportIds)) {
            $rows = $this->db
                ->select('mr.target_id AS report_id, m.url, m.type')
                ->from('media_relation mr')
                ->join('media m', 'm.id = mr.media_id')
                ->where('mr.target_table', 'report_list')
                ->where_in('mr.target_id', $reportIds)
                ->get()
                ->result();

            foreach ($grouped as &$item) {
                foreach ($item['report_list'] as &$report) {
                    foreach ($rows as $row) {
                        if ($row->report_id == $report['id']) {
                            $report['report_media'][] = [
                                'url' => $row->url,
                                'type' => $row->type,
                            ];
                        }
                    }
                }
            }
        }
    }

    private function fetchPaymentsWithRelations($user_account_id, $limit, $offset)
    {
        return $this->db
            ->select('pay.*, o.id AS order_id, rl.id AS report_list_id, rl.description AS report_list_description, n.id AS news_id, n.description AS news_description')
            ->from('payment pay')
            ->join('payment_order po', 'po.payment_id = pay.id', 'left')
            ->join('orders o', 'o.id = po.order_id', 'left')
            ->join('report_list rl', 'rl.id = o.report_list_id', 'left')
            ->join('news n', 'n.id = rl.news_id', 'left')
            ->join('user_account_relations pay_uar', 'pay_uar.id = pay.pay_money_user_account_relation_id', 'left')
            ->join('user_account pay_ua', 'pay_ua.id = pay_uar.user_account_id', 'left')
            ->join('user_account_relations give_uar', 'give_uar.id = pay.give_money_user_account_relation_id', 'left')
            ->join('user_account give_ua', 'give_ua.id = give_uar.user_account_id', 'left')
            ->group_start()
                ->where('pay_ua.id', $user_account_id)
                ->or_where('give_ua.id', $user_account_id)
            ->group_end()
            ->limit($limit, $offset)
            ->order_by('pay.id', 'DESC')
            ->get()
            ->result();
    }

    private function buildGroupedPayments(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $pid = $row->id;
            $result[$pid] = [
                'payment' => [
                    'id' => $pid,
                    'amount' => $row->amount,
                    'factor' => $row->factor,
                    'status' => $row->status,
                    'created_at' => $row->created_at,
                ],
                'report_list' => [
                    'id' => $row->report_list_id,
                    'description' => $row->report_list_description,
                    'report_media' => [],
                ],
                'news' => [
                    'id' => $row->news_id,
                    'description' => $row->news_description,
                    'news_media' => [],
                    'categories' => [],
                ],
            ];
        }
        return $result;
    }
}

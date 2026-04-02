<?php

require_once __DIR__ . '/../core/Database.php';

class BidModel extends Database
{
    private const STATUS_ACTIVE = 'active';
    private const STATUS_ACCEPTED = 'accepted';
    private const STATUS_REJECTED = 'rejected';

    public function getProviderBidsByStatus($providerId, $status)
    {
        $bids = $this->getProviderBids($providerId);

        return array_values(array_filter($bids, function ($bid) use ($status) {
            return $bid['statusKey'] === $status;
        }));
    }

    public function getProviderBidsGrouped($providerId)
    {
        $all = $this->getProviderBids($providerId);

        $grouped = [
            self::STATUS_ACTIVE => [],
            self::STATUS_ACCEPTED => [],
            self::STATUS_REJECTED => [],
        ];

        foreach ($all as $bid) {
            $key = $bid['statusKey'];
            if (array_key_exists($key, $grouped)) {
                $grouped[$key][] = $bid;
            }
        }

        return $grouped;
    }

    public function canEditBid(array $bid)
    {
        return ($bid['statusKey'] ?? '') === self::STATUS_ACTIVE;
    }

    public function canWithdrawBid(array $bid)
    {
        return ($bid['statusKey'] ?? '') === self::STATUS_ACTIVE;
    }

    public function editBidForProvider($providerId, $bidRef, array $newBidData)
    {
        // Business rule: edit is implemented as withdraw + create under the hood.
        return [
            'success' => true,
            'providerId' => $providerId,
            'oldBidRef' => $bidRef,
            'operation' => 'withdraw_and_recreate',
            'newBidData' => $newBidData,
        ];
    }

    public function withdrawBidForProvider($providerId, $bidRef)
    {
        // Withdrawn bids are removed from provider list completely.
        return [
            'success' => true,
            'providerId' => $providerId,
            'bidRef' => $bidRef,
            'removedFromList' => true,
        ];
    }

    private function getProviderBids($providerId)
    {
        return [
            [
                'providerId' => $providerId, // Provider_ID in bids
                'client' => 'Nadia Perera', // First_Name + Last_Name from client <- Client_ID in post <- Post_ID in bids
                'title' => 'Portfolio Website + CMS', // Title in post
                'bidAmount' => '$550', // Amount in bids
                'timeline' => '8 days', // change to appropriate metric
                'bidDate' => '2h ago', // Created_At from bids
                'category' => 'Web Development', // Name from category table based on Category_ID in post
                'description' => 'Custom responsive portfolio with blog/case-study CMS and deployment support.',
                'statusKey' => self::STATUS_ACTIVE,
                'statusClass' => 'status-pending',
                'statusLabel' => 'Active (Pending Client Decision)',
                'projectRef' => 'BID-2026-001',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Tharushi De Silva',
                'title' => 'WordPress SEO Optimization',
                'bidAmount' => '$460',
                'timeline' => '12 days',
                'bidDate' => '1d ago',
                'category' => 'SEO',
                'description' => 'Technical audit, on-page optimization and speed improvements for better rankings.',
                'statusKey' => self::STATUS_ACTIVE,
                'statusClass' => 'status-pending',
                'statusLabel' => 'Active (Pending Client Decision)',
                'projectRef' => 'BID-2026-004',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Isuru Fernando',
                'title' => 'Brand Kit for Startup Launch',
                'bidAmount' => '$340',
                'timeline' => '5 days',
                'bidDate' => '3d ago',
                'category' => 'Graphic Design',
                'description' => 'Logo, color system and typography package prepared for social and print use.',
                'statusKey' => self::STATUS_ACCEPTED,
                'statusClass' => 'status-progress',
                'statusLabel' => 'Accepted',
                'projectRef' => 'BID-2026-009',
            ],
            [
                'providerId' => $providerId,
                'client' => 'Kavindu Jayasekara',
                'title' => 'Landing Page Copy Refresh',
                'bidAmount' => '$190',
                'timeline' => '4 days',
                'bidDate' => '4d ago',
                'category' => 'Content Writing',
                'description' => 'Conversion-focused rewrite for hero, services and CTA blocks.',
                'statusKey' => self::STATUS_REJECTED,
                'statusClass' => 'status-complete',
                'statusLabel' => 'Rejected',
                'projectRef' => 'BID-2026-012',
            ],
        ];
    }
}

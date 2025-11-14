<?php

namespace App\Dto\Response;

use Symfony\Component\Serializer\Attribute\Groups;

class LinkEmployeeResponse
{
    public function __construct(
        #[Groups(['link-employee:read'])]
        public string $status,
        #[Groups(['link-employee:read'])]
        public array $summary,
        #[Groups(['link-employee:read'])]
        public array $details,
        #[Groups(['link-employee:read'])]
        public string $message,
        #[Groups(['link-employee:read'])]
        public bool $refreshManagerData = false,
    ) {
    }
}

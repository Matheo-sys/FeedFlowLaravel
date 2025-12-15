<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class OrganizationDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $owner_id,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->validated('name'),
            owner_id: $request->user()->id,
        );
    }
}

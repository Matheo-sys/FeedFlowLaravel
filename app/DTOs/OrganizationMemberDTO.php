<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class OrganizationMemberDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $role,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            email: $request->input('email'),
            role: $request->input('role', 'member'),
        );
    }
}

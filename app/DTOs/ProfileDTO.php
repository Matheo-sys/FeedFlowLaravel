<?php

namespace App\DTOs;

use Illuminate\Http\Request;

final class ProfileDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            email: $request->validated('email'),
        );
    }
}

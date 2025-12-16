<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class SurveyDTO
{

    public function __construct(
        public readonly int $user_id,
        public readonly int $organization_id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly bool $is_anonymous
    ) {

    }

    public static function formRequest(Request $request) {
        return new self(
            user_id: $request->user()->id,
            organization_id: $request->user()->organization_id,
            title: $request->title,
            description: $request->description,
            start_date: $request->start_date,
            end_date: $request->end_date,
            is_anonymous: $request->is_anonymous ?? false,
        );
    }
}


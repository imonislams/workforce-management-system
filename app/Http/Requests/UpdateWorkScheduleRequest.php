<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'break_start' => ['nullable'],
            'break_end' => ['nullable'],
            'grace_period' => ['required', 'integer', 'min:0', 'max:120'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

<?php

namespace Tests\TestCase;

use Illuminate\Foundation\Http\FormRequest;

class TestFormRequest extends FormRequest
{
    protected $data;

    public function __construct(array $data = [])
    {
        parent::__construct();
        $this->data = $data;
    }

    public function rules(): array
    {
        return [];
    }

    public function all($keys = null): array
    {
        return $this->data;
    }

    public function authorize(): bool
    {
        return true;
    }
}

<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class InstrumentData extends Data
{

    public function __construct(
        public ?int                 $id,
        public int                  $user_id,
        public string               $name,
        public ?string              $symbol,
        public ?string              $isin,
        public ?float               $last_price,
        /** @var \App\Data\TradeData[] */
        public Optional|array       $trades,
        public Optional|string|null $created_at,
        public Optional|string|null $updated_at,
    )
    {
    }

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public static function authorize(): bool
    {
        return true;
    }

    public static function prepareForPipeline(array $properties): array
    {
        if (\request()->isMethod('POST')) {
            $properties['user_id'] = auth()->id();
        }
        return $properties;
    }

    public static function rules(): array
    {
        return [
            'user_id'    => ['required',
                'exists:users,id'],
            'name'       => ['required',
                'max:200'],
            'symbol'     => ['nullable',
                'max:10'],
            'isin'       => ['nullable',
                'max:15'],
            'last_price' => ['nullable',
                'decimal:0,2'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * @return array
     */
    public static function messages(): array
    {
        return [];
    }

    public static function attributes(): array
    {
        return [
            'user_id'    => 'User',
            'name'       => 'Name',
            'symbol'     => 'Symbol',
            'isin'       => 'ISIN',
            'last_price' => 'Last Price',
        ];
    }
}

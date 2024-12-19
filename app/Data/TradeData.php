<?php

namespace App\Data;

use DateTime;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TradeData extends Data
{

    public function __construct(
        public ?int                 $id,
        public int                  $instrument_id,
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d')]
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public ?DateTime            $purchase_date,
        public int                  $quantity,
        public ?float               $price,
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

    public static function rules(): array
    {
        return [
            'instrument_id' => ['required', 'exists:instruments,id'],
            'purchase_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'quantity'      => ['integer', 'gte:1'],
            'price'         => ['nullable', 'decimal:0,2'],
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
            'instrument_id' => 'Instrument',
            'purchase_date' => 'Purchase Date',
            'quantity'      => 'Quantity',
            'price'         => 'Price',
        ];
    }
}

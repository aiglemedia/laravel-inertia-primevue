declare namespace App.Data {
    export type InstrumentData = {
        id: number | null;
        user_id: number;
        name: string;
        symbol: string | null;
        isin: string | null;
        last_price: number | null;
        trades?: Array<App.Data.TradeData>;
        created_at?: string | null;
        updated_at?: string | null;
    };
    export type TradeData = {
        id: number | null;
        instrument_id: number;
        purchase_date: string | null;
        quantity: number;
        price: number | null;
        created_at?: string | null;
        updated_at?: string | null;
    };
}

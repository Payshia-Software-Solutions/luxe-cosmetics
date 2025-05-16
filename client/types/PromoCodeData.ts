
// Define interface for promo code data
export interface PromoCodeData {
    is_active: boolean;
    start_date: string;
    end_date: string;
    min_order_value: string;
    discount_type: "percentage" | "fixed";
    discount_value: string;
}
